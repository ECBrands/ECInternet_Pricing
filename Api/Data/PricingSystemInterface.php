<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Pricing\Api\Data;

use Magento\Quote\Model\Quote\Item as QuoteItem;

interface PricingSystemInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $sku
     * @param float  $quantity
     *
     * @return float|null
     */
    public function getPrice(string $sku, float $quantity = 1.0);

    /**
     * @param \Magento\Quote\Model\Quote\Item $quoteItem
     *
     * @return float|null
     */
    public function getPriceForQuoteItem(QuoteItem $quoteItem);
}
