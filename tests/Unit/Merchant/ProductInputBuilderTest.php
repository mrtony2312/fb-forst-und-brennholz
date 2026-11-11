<?php

namespace Tests\Unit\Merchant;

use App\Domain\Merchant\Api\ProductInputBuilder;
use App\Domain\Merchant\Support\Availability;
use App\DTO\Merchant\GoogleProductData;
use Tests\TestCase;

class ProductInputBuilderTest extends TestCase
{
    public function test_builds_merchant_api_product_input_payload_for_de(): void
    {
        config([
            'merchant.content_language' => 'de',
            'merchant.feed_label' => 'DE',
            'merchant.currency' => 'EUR',
            'merchant.shipping.country' => 'DE',
            'merchant.shipping.service' => 'Standardversand (2–5 Werktage)',
            'merchant.shipping.price' => '0.00',
            'merchant.shipping.handling_min' => 1,
            'merchant.shipping.handling_max' => 2,
            'merchant.shipping.transit_min' => 1,
            'merchant.shipping.transit_max' => 3,
        ]);

        $dto = new GoogleProductData(
            id: '42',
            title: 'Ardenforest Holzpellets 15 kg',
            description: 'Holzpellets für den deutschen Markt.',
            link: 'https://heribrennholzgmbh.com/produkt/ardenforest',
            imageLink: 'https://heribrennholzgmbh.com/storage/products/arden.jpg',
            additionalImageLinks: [],
            availability: Availability::InStock->value,
            condition: 'new',
            price: '39.90 EUR',
            salePrice: '29.90 EUR',
            brand: 'Ardenforest',
            gtin: null,
            mpn: 'AF-42',
            identifierExists: true,
            itemGroupId: null,
            googleProductCategory: '6229',
            productType: 'Holzpellets',
            shippingWeight: '990 kg',
            contentLanguage: 'de',
            targetCountry: 'DE',
            priceAmount: 29.90,
            currency: 'EUR',
            inStock: true,
        );

        $payload = app(ProductInputBuilder::class)->build($dto);

        $this->assertSame('42', $payload['offerId']);
        $this->assertSame('de', $payload['contentLanguage']);
        $this->assertSame('DE', $payload['feedLabel']);

        $attrs = $payload['productAttributes'];
        $this->assertSame('IN_STOCK', $attrs['availability']);
        $this->assertSame('NEW', $attrs['condition']);
        $this->assertSame('Ardenforest', $attrs['brand']);
        $this->assertSame('6229', $attrs['googleProductCategory']);
        $this->assertSame('39900000', $attrs['price']['amountMicros']);
        $this->assertSame('EUR', $attrs['price']['currencyCode']);
        $this->assertSame('29900000', $attrs['salePrice']['amountMicros']);
        $this->assertSame('DE', $attrs['shipping'][0]['country']);
        $this->assertSame('0', $attrs['shipping'][0]['price']['amountMicros']);
        $this->assertSame('EUR', $attrs['shipping'][0]['price']['currencyCode']);
        $this->assertArrayNotHasKey('identifierExists', $attrs);
    }

    public function test_sets_identifier_exists_false_when_missing(): void
    {
        config([
            'merchant.content_language' => 'de',
            'merchant.feed_label' => 'DE',
            'merchant.shipping.country' => 'DE',
            'merchant.shipping.service' => 'Standard',
            'merchant.shipping.price' => 0,
        ]);

        $dto = new GoogleProductData(
            id: '99',
            title: 'Scheitholz Mix 33 cm',
            description: 'Trockenes Scheitholz.',
            link: 'https://heribrennholzgmbh.com/produkt/scheitholz',
            imageLink: 'https://heribrennholzgmbh.com/storage/products/holz.jpg',
            additionalImageLinks: [],
            availability: Availability::InStock->value,
            condition: 'new',
            price: '120.00 EUR',
            salePrice: null,
            brand: 'Heri Brennholz',
            gtin: null,
            mpn: null,
            identifierExists: false,
            itemGroupId: null,
            googleProductCategory: '6229',
            productType: 'Scheitholz',
            shippingWeight: null,
            contentLanguage: 'de',
            targetCountry: 'DE',
            priceAmount: 120.0,
            currency: 'EUR',
            inStock: true,
        );

        $payload = app(ProductInputBuilder::class)->build($dto);

        $this->assertFalse($payload['productAttributes']['identifierExists']);
    }
}
