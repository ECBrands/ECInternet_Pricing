<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Pricing\Plugin\Magento\Quote\Model\Quote;

use Magento\Quote\Model\Quote\Item as QuoteItem;
use ECInternet\Pricing\Helper\Data;
use ECInternet\Pricing\Logger\Logger;

/**
 * Plugin for Magento\Quote\Model\Quote\Item
 */
class ItemPlugin
{
    /**
     * @var \ECInternet\Pricing\Helper\Data
     */
    private $helper;

    /**
     * @var \ECInternet\Pricing\Logger\Logger
     */
    private $logger;

    /**
     * ItemPlugin constructor.
     *
     * @param \ECInternet\Pricing\Helper\Data   $helper
     * @param \ECInternet\Pricing\Logger\Logger $logger
     */
    public function __construct(
        Data $helper,
        Logger $logger
    ) {
        $this->helper = $helper;
        $this->logger = $logger;
    }

    /**
     * Set price from Pricing module
     *
     * @param \Magento\Quote\Model\Quote\Item $subject
     * @param float                           $result
     *
     * @return float
     * @noinspection PhpMissingParamTypeInspection
     */
    public function afterGetPrice(
        QuoteItem $subject,
        $result
    ) {
        $this->log('afterGetPrice()', ['sku' => $subject->getSku(), 'result' => $result]);

        if ($this->helper->isModuleEnabled()) {
            $this->log('afterGetPrice() - ---------------------------------------');
            $this->log('afterGetPrice()', ['sku' => $subject->getSku(), 'price' => $result]);

            $price = $this->getPrice($subject);
            $this->log('afterGetPrice()', ['price' => $price]);

            if ($price !== null) {
                $this->log('afterGetPrice() - Returning custom price:', [$price]);
                $this->log('afterGetPrice() - ---------------------------------------' . PHP_EOL);

                return $price;
            }

            $this->log('afterGetPrice() - Returning original price:', [$result]);
            $this->log('afterGetPrice() - ---------------------------------------' . PHP_EOL);
        }

        return $result;
    }

    /**
     * Set price from Sage
     *
     * @param \Magento\Quote\Model\Quote\Item $subject
     * @param float                           $result
     *
     * @return float
     * @noinspection PhpMissingParamTypeInspection
     */
    public function afterGetRowTotal(
        QuoteItem $subject,
        $result
    ) {
        $this->log('afterGetRowTotal()', ['sku' => $subject->getSku(), 'result' => $result]);

        if ($this->helper->isModuleEnabled()) {
            $this->log('afterGetRowTotal() - ---------------------------------------');
            $this->log('afterGetRowTotal()', ['sku' => $subject->getSku(), 'rowTotal' => $result]);

            $price = $this->getPrice($subject);
            if ($price !== null) {
                $qty = $subject->getQty();
                $this->log('afterGetRowTotal()', ['qty' => $qty]);

                // Multiply our price by qty to get the extended price
                $extendedPrice = $price * $qty;
                $this->log('afterGetRowTotal()', ['extendedPrice' => $extendedPrice]);

                $this->log('afterGetRowTotal() - Returning custom price:', [$extendedPrice]);
                $this->log('afterGetRowTotal() - ---------------------------------------' . PHP_EOL);

                return $extendedPrice;
            }

            $this->log('afterGetRowTotal() - Returning original price:', [$result]);
            $this->log('afterGetRowTotal() - ---------------------------------------' . PHP_EOL);
        }

        return $result;
    }

    /**
     * @param \Magento\Quote\Model\Quote\Item $quoteItem
     *
     * @return float|null
     */
    protected function getPrice(
        QuoteItem $quoteItem
    ) {
        $this->log('getPrice()', ['sku' => $quoteItem->getSku()]);

        if ($pricingSystem = $this->helper->getPricingSystem()) {
            $this->log('getPrice()', ['pricingSystem' => $pricingSystem->getName()]);

            $price = $pricingSystem->getPriceForQuoteItem($quoteItem);
            $this->log('getPrice()', ['price' => $price]);

            return $price;
        }

        return null;
    }

    /**
     * Write to extension log
     *
     * @param string $message
     * @param array  $extra
     *
     * @return void
     */
    private function log(string $message, array $extra = [])
    {
        $this->logger->info('Plugin/Magento/Quote/Model/Quote/ItemPlugin - ' . $message, $extra);
    }
}
