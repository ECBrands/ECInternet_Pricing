<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Pricing\ViewModel;

use Magento\Catalog\Model\Product;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use ECInternet\Pricing\Helper\Data;
use ECInternet\Pricing\Logger\Logger;

class ProductPrice implements ArgumentInterface
{
    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var \ECInternet\Pricing\Helper\Data
     */
    private $helper;

    /**
     * @var \ECInternet\Pricing\Logger\Logger
     */
    private $logger;

    /**
     * ProductPrice constructor.
     *
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \ECInternet\Pricing\Helper\Data                   $helper
     * @param \ECInternet\Pricing\Logger\Logger                 $logger
     */
    public function __construct(
        PriceCurrencyInterface $priceCurrency,
        Data $helper,
        Logger $logger
    ) {
        $this->priceCurrency  = $priceCurrency;
        $this->helper         = $helper;
        $this->logger         = $logger;
    }

    /**
     * Get product price
     *
     * @return string|null
     */
    public function getPriceHtml(
        Product $product
    ) {
        if ($pricingSystem = $this->helper->getPricingSystem()) {
            $this->log('getPrice()', ['pricingSystem' => $pricingSystem->getName()]);

            $price = $pricingSystem->getPrice($product->getSku());
            $this->log('getPrice()', ['price' => $price]);

            if ($price !== null) {
                return $this->priceCurrency->convertAndFormat($price);
            }
        }

        return null;
    }

    /**
     * Write to extension log
     *
     * @param string $message
     * @param array  $extra
     */
    private function log(string $message, array $extra = [])
    {
        $this->logger->info('Pricing/ViewModel/ProductPrice - ' . $message, $extra);
    }
}
