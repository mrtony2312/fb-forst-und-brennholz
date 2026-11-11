<?php

namespace App\Console\Commands\Merchant;

use App\Domain\Merchant\GoogleFeedValidator;
use App\Domain\Merchant\GoogleProductMapper;
use App\Models\Product;
use Illuminate\Console\Command;

/**
 * Crawl-style Merchant Center suspension audit for the DE catalog.
 */
class CrawlAuditCommand extends Command
{
    protected $signature = 'merchant:crawl-audit {--json : Machine-readable JSON}';

    protected $description = 'Crawl-style audit of suspension risks (feed, brand, DE market alignment).';

    public function handle(GoogleProductMapper $mapper, GoogleFeedValidator $validator): int
    {
        $findings = [
            'critical' => [],
            'high' => [],
            'medium' => [],
            'stats' => [
                'products' => 0,
                'eligible' => 0,
                'in_stock' => 0,
                'out_of_stock' => 0,
                'no_identifier' => 0,
                'pdp_ch_shipping_claims' => 0,
            ],
        ];

        $appName = (string) config('app.name');
        $legal = (string) config('merchant.nap.legal_name');
        $country = (string) config('merchant.target_country');
        $currency = (string) config('merchant.currency');
        $expectedCurrency = shop_currency();
        $appUrl = strtolower((string) config('app.url'));

        if ($country !== 'DE') {
            $findings['critical'][] = "MERCHANT_TARGET_COUNTRY=\"{$country}\" must be DE for this storefront.";
        }
        if ($currency !== $expectedCurrency) {
            $findings['critical'][] = "MERCHANT_CURRENCY=\"{$currency}\" must be {$expectedCurrency} (aligned with the storefront).";
        }
        if (stripos($legal, 'Casacuberta') !== false || stripos($appName, 'Casacuberta') !== false) {
            $findings['critical'][] = 'Spanish Casacuberta branding detected — must use F & B Forst und Brennholz GmbH.';
        }
        if (stripos($appName, 'Naturalenha') !== false || stripos($appName, 'Lenha') !== false) {
            $findings['critical'][] = 'Portuguese / legacy brand detected in APP_NAME — must use F & B Forst und Brennholz.';
        }
        if ($legal !== '' && stripos($legal, 'F & B Forst und Brennholz') === false) {
            $findings['high'][] = "merchant.nap.legal_name=\"{$legal}\" should be F & B Forst und Brennholz GmbH.";
        }
        if ((string) config('merchant.shipping.country') !== 'DE') {
            $findings['critical'][] = 'merchant.shipping.country must be DE.';
        }
        if (str_starts_with($appUrl, 'http://') && ! str_contains($appUrl, '127.0.0.1') && ! str_contains($appUrl, 'localhost')) {
            $findings['critical'][] = 'APP_URL must be https:// for Google Merchant Center production feeds.';
        }
        if ((string) config('merchant.nap.vat_id', '') === '') {
            $findings['medium'][] = 'SHOP_VAT_ID / merchant.nap.vat_id is empty — complete Impressum + Merchant business info for faster verification.';
        }

        $returnCost = (float) config('merchant.returns.return_shipping_cost', 0);
        $customerPays = (bool) config('merchant.returns.customer_pays_return_shipping', true);
        if ($customerPays && $returnCost <= 0) {
            $findings['high'][] = 'Return policy says customer pays shipping but MERCHANT_RETURN_SHIPPING_COST is 0.';
        }

        Product::with(['images', 'categories'])->orderBy('id')->chunkById(100, function ($products) use ($mapper, $validator, &$findings, $expectedCurrency) {
            foreach ($products as $product) {
                $findings['stats']['products']++;
                if ($product->in_stock) {
                    $findings['stats']['in_stock']++;
                } else {
                    $findings['stats']['out_of_stock']++;
                }

                $raw = mb_strtolower((string) $product->description.' '.(string) $product->short_description);
                if (str_contains($raw, 'innerhalb der schweiz') || str_contains($raw, 'in der schweiz') || preg_match('/lieferung in 1 bis 2 werktagen/u', $raw)) {
                    $findings['stats']['pdp_ch_shipping_claims']++;
                    $findings['critical'][] = "PDP still claims CH shipping: {$product->id} ".($product->name ?? '');
                }

                $dto = $mapper->map($product);

                if ($dto === null) {
                    $findings['high'][] = 'Ineligible for feed: '.$product->id.' '.($product->name ?? '');

                    continue;
                }

                $findings['stats']['eligible']++;

                foreach ($validator->validateDto($dto) as $issue) {
                    $findings['high'][] = "{$dto->id}: {$issue}";
                }

                if ($dto->targetCountry !== 'DE') {
                    $findings['critical'][] = "Product {$dto->id} target_country={$dto->targetCountry} (expected DE).";
                }

                if ($dto->currency !== $expectedCurrency) {
                    $findings['high'][] = "Product {$dto->id} currency={$dto->currency} (expected {$expectedCurrency}).";
                }

                if (! $dto->identifierExists) {
                    $findings['stats']['no_identifier']++;
                    $findings['medium'][] = "No GTIN/MPN: {$dto->id} ".($product->name ?? '');
                }
            }
        });

        if ($this->option('json')) {
            $this->line(json_encode($findings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        } else {
            $this->table(['Metric', 'Value'], collect($findings['stats'])->map(fn ($v, $k) => [$k, $v])->values()->all());
            foreach (['critical', 'high', 'medium'] as $level) {
                if ($findings[$level] === []) {
                    continue;
                }
                $this->newLine();
                $this->warn(strtoupper($level).' ('.count($findings[$level]).')');
                foreach (array_slice($findings[$level], 0, 40) as $line) {
                    $this->line(' - '.$line);
                }
                if (count($findings[$level]) > 40) {
                    $this->line(' - … '.(count($findings[$level]) - 40).' more');
                }
            }
            if ($findings['critical'] === [] && $findings['high'] === []) {
                $this->info('No critical/high suspension blockers in local DE catalog.');
            }
        }

        return $findings['critical'] === [] ? self::SUCCESS : self::FAILURE;
    }
}
