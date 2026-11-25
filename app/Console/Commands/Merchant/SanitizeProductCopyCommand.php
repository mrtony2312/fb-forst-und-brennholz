<?php

namespace App\Console\Commands\Merchant;

use App\Domain\Merchant\Support\DescriptionSanitizer;
use App\Domain\Merchant\Support\GermanCopyNormalizer;
use App\Models\Product;
use Illuminate\Console\Command;

/**
 * Persist DE market shipping copy on product PDPs so Merchant crawlers
 * do not see outdated CH delivery claims (misrepresentation risk).
 */
class SanitizeProductCopyCommand extends Command
{
    protected $signature = 'merchant:sanitize-product-copy {--dry-run : Show how many rows would change}';

    protected $description = 'Rewrite CH shipping claims in product descriptions to DE free shipping (2–5 Werktage).';

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');
        $changed = 0;

        Product::query()->orderBy('id')->chunkById(100, function ($products) use ($dry, &$changed) {
            foreach ($products as $product) {
                $name = GermanCopyNormalizer::normalize((string) $product->name);
                $description = DescriptionSanitizer::html($product->description);
                $short = DescriptionSanitizer::html($product->short_description);

                if (
                    $name === (string) $product->name
                    && $description === (string) $product->description
                    && $short === (string) $product->short_description
                ) {
                    continue;
                }

                $changed++;
                if ($dry) {
                    continue;
                }

                $product->forceFill([
                    'name' => $name !== '' ? $name : $product->name,
                    'description' => $description !== '' ? $description : $product->description,
                    'short_description' => $short !== '' ? $short : $product->short_description,
                ])->save();
            }
        });

        $this->info(($dry ? 'Would update' : 'Updated')." {$changed} product(s).");

        return self::SUCCESS;
    }
}
