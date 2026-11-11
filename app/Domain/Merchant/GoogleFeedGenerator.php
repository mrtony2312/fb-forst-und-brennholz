<?php

namespace App\Domain\Merchant;

use App\Domain\Merchant\Support\PriceFormatter;
use App\DTO\Merchant\GoogleProductData;
use App\Models\Product;
use XMLWriter;

/**
 * Google Merchant Center RSS 2.0 product feed (DE / de / EUR).
 *
 * Only emits recognized product-data-spec attribute names under xmlns:g.
 * Shipping is limited to country/service/price; handling times are top-level.
 *
 * @see https://support.google.com/merchants/answer/14987622
 * @see https://support.google.com/merchants/answer/7052112
 * @see https://support.google.com/merchants/answer/6324484
 */
class GoogleFeedGenerator
{
    public function __construct(private readonly GoogleProductMapper $mapper) {}

    public function toXml(): string
    {
        $w = new XMLWriter;
        $w->openMemory();
        $w->setIndent(true);
        $w->startDocument('1.0', 'UTF-8');
        $w->startElement('rss');
        $w->writeAttribute('version', '2.0');
        $w->writeAttribute('xmlns:g', 'http://base.google.com/ns/1.0');
        $w->startElement('channel');
        $w->writeElement('title', (string) config('feed.title'));
        $w->writeElement('link', rtrim((string) config('app.url'), '/').'/');
        $w->writeElement('description', (string) config('feed.description'));

        Product::with(['images', 'categories'])
            ->orderBy('id')
            ->chunk(200, function ($products) use ($w) {
                foreach ($products as $product) {
                    $dto = $this->mapper->map($product);
                    if ($dto) {
                        $this->writeItem($w, $dto);
                    }
                }
            });

        $w->endElement(); // channel
        $w->endElement(); // rss
        $w->endDocument();

        return $w->outputMemory();
    }

    private function writeItem(XMLWriter $w, GoogleProductData $dto): void
    {
        $w->startElement('item');

        $this->g($w, 'id', $dto->id);
        $this->g($w, 'title', $dto->title);
        $w->startElement('g:description');
        $w->writeCdata($dto->description);
        $w->endElement();
        $this->g($w, 'link', $dto->link);
        $this->g($w, 'image_link', $dto->imageLink);
        $this->g($w, 'availability', $dto->availability);
        $this->g($w, 'condition', $dto->condition);
        $this->g($w, 'price', $dto->price);
        $this->g($w, 'brand', $dto->brand);

        if ($dto->salePrice) {
            $this->g($w, 'sale_price', $dto->salePrice);
        }

        foreach ($dto->additionalImageLinks as $url) {
            if ($url !== '') {
                $this->g($w, 'additional_image_link', $url);
            }
        }

        if ($dto->gtin) {
            $this->g($w, 'gtin', $dto->gtin);
        }
        if ($dto->mpn) {
            $this->g($w, 'mpn', $dto->mpn);
        }
        if (! $dto->identifierExists) {
            $this->g($w, 'identifier_exists', 'false');
        }

        if ($dto->itemGroupId) {
            $this->g($w, 'item_group_id', $dto->itemGroupId);
        }
        if ($dto->googleProductCategory !== '') {
            $this->g($w, 'google_product_category', $dto->googleProductCategory);
        }
        if ($dto->productType !== '') {
            $this->g($w, 'product_type', $dto->productType);
        }
        if ($dto->shippingWeight) {
            $this->g($w, 'shipping_weight', $dto->shippingWeight);
        }
        if ($dto->shippingLabel) {
            $this->g($w, 'shipping_label', $dto->shippingLabel);
        }
        if ($dto->unitPricingMeasure) {
            $this->g($w, 'unit_pricing_measure', $dto->unitPricingMeasure);
            if ($dto->unitPricingBaseMeasure) {
                $this->g($w, 'unit_pricing_base_measure', $dto->unitPricingBaseMeasure);
            }
        }

        // Top-level handling times — source of truth: merchant.shipping (fallback feed.*).
        $minHandling = (int) config('merchant.shipping.handling_min', config('feed.shipping_handling_time_min', 0));
        $maxHandling = (int) config('merchant.shipping.handling_max', config('feed.shipping_handling_time_max', 1));
        if ($maxHandling < $minHandling) {
            $maxHandling = $minHandling;
        }
        $this->g($w, 'min_handling_time', (string) $minHandling);
        $this->g($w, 'max_handling_time', (string) $maxHandling);

        // Shipping: country / service / price only. Free DE must be 0.00 EUR.
        $shipPriceAmount = (float) config(
            'merchant.shipping.price',
            config('feed.shipping_price', config('shop.shipping.price', 0))
        );
        $shipPrice = PriceFormatter::format($shipPriceAmount, $dto->currency);
        $w->startElement('g:shipping');
        $this->g($w, 'country', (string) config('merchant.shipping.country', 'DE'));
        $this->g($w, 'service', $this->shippingService());
        $this->g($w, 'price', $shipPrice);
        $w->endElement();

        $w->endElement(); // item
    }

    private function shippingService(): string
    {
        $service = trim((string) config(
            'merchant.shipping.service',
            config('feed.shipping_service', 'Standardversand')
        ));
        $service = str_replace(["\u{2013}", "\u{2014}", '–', '—'], '-', $service);
        $service = preg_replace('/\s+/u', ' ', $service) ?? $service;

        return $service !== '' ? $service : 'Standardversand';
    }

    private function g(XMLWriter $w, string $name, string $value): void
    {
        if ($value === '') {
            return;
        }

        $w->writeElement('g:'.$name, $value);
    }
}
