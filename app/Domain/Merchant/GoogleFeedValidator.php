<?php

namespace App\Domain\Merchant;

use App\Domain\Merchant\Support\Gtin;
use App\DTO\Merchant\GoogleProductData;
use App\Models\Product;
use Illuminate\Support\Str;

class GoogleFeedValidator
{
    /**
     * @return list<string>
     */
    public function validateDto(GoogleProductData $dto): array
    {
        $errors = [];

        if ($dto->id === '' || strlen($dto->id) > 50) {
            $errors[] = 'id invalid';
        }

        if ($dto->title === '' || mb_strlen($dto->title) > 150) {
            $errors[] = 'title length invalid';
        }

        if (mb_strlen($dto->description) < 25 || mb_strlen($dto->description) > 5000) {
            $errors[] = 'description length invalid';
        }

        if (trim($dto->brand) === '') {
            $errors[] = 'brand missing';
        }

        if ($dto->identifierExists && $dto->gtin === null && ($dto->mpn === null || $dto->mpn === '')) {
            $errors[] = 'identifier_exists true without gtin/mpn';
        }

        if ($dto->gtin !== null && ! Gtin::isValid($dto->gtin)) {
            $errors[] = 'gtin invalid';
        }
        if (mb_strlen($dto->title) > 150) {
            $errors[] = 'title too long';
        }
        if (! preg_match('/^\d+\.\d{2} [A-Z]{3}$/', $dto->price)) {
            $errors[] = 'price format';
        }
        if (! preg_match('#^https?://#i', $dto->link)) {
            $errors[] = 'link must be absolute http(s)';
        }
        if (! preg_match('#^https?://#i', $dto->imageLink)) {
            $errors[] = 'image_link must be absolute http(s)';
        }
        // Production storefront is https — flag plain http when APP_URL is https.
        if (str_starts_with(strtolower((string) config('app.url')), 'https://')) {
            if (str_starts_with(strtolower($dto->link), 'http://')) {
                $errors[] = 'link must be https absolute';
            }
            if (str_starts_with(strtolower($dto->imageLink), 'http://')) {
                $errors[] = 'image_link must be https absolute';
            }
        }
        if ($dto->currency !== '' && strtoupper($dto->currency) !== 'CHF') {
            $errors[] = 'currency must be CHF for CH feed';
        }
        if ($dto->targetCountry !== '' && strtoupper($dto->targetCountry) !== 'CH') {
            $errors[] = 'target_country must be CH';
        }
        if ($dto->shippingWeight !== null && $dto->shippingWeight !== ''
            && ! preg_match('/^\d+(?:\.\d+)?\s?(kg|g|lb|oz)$/i', $dto->shippingWeight)) {
            $errors[] = 'shipping_weight format invalid';
        }
        if (preg_match('/\b(SOLDE|PROMO|!!!)\b/u', $dto->title)) {
            $errors[] = 'title promo';
        }

        return $errors;
    }

    /**
     * @return list<string>
     */
    public function compareXmlItem(\SimpleXMLElement $item, GoogleProductData $dto): array
    {
        $g = $item->children('http://base.google.com/ns/1.0');
        $errors = [];

        if ((string) $g->id !== $dto->id) {
            $errors[] = 'xml id mismatch';
        }
        if ((string) $g->price !== $dto->price) {
            $errors[] = 'xml price mismatch';
        }
        if ((string) $g->availability !== $dto->availability) {
            $errors[] = 'xml availability mismatch';
        }

        return $errors;
    }

    /**
     * @return list<string>
     */
    public function compareLandingHtml(string $html, GoogleProductData $dto): array
    {
        $errors = [];
        $expected = number_format($dto->priceAmount, 2, '.', '');

        if (preg_match('/data-gmc-price="([^"]+)"/', $html, $m)) {
            if ($m[1] !== $expected) {
                $errors[] = 'dom price '.$m[1].' != '.$expected;
            }
        } else {
            $errors[] = 'dom price missing';
        }

        preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $matches);
        $productJson = null;
        foreach ($matches[1] ?? [] as $raw) {
            $json = json_decode(html_entity_decode($raw), true);
            if (($json['@type'] ?? null) === 'Product') {
                $productJson = $json;
                break;
            }
        }
        if (! $productJson) {
            $errors[] = 'jsonld missing';
        } else {
            $offerPrice = $productJson['offers']['price'] ?? null;
            if ((string) $offerPrice !== $expected) {
                $errors[] = 'jsonld price mismatch';
            }
            $avail = $productJson['offers']['availability'] ?? '';
            if ($dto->inStock && ! Str::contains((string) $avail, 'InStock')) {
                $errors[] = 'jsonld availability mismatch';
            }
        }

        return $errors;
    }

    public function productFromId(string $id): ?Product
    {
        return Product::with(['images', 'categories'])->find($id);
    }
}
