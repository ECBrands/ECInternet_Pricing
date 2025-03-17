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
use ECInternet\Pricing\Model\Config;

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
     * @var \ECInternet\Pricing\Model\Config
     */
    private $config;

    /**
     * ProductPrice constructor.
     *
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \ECInternet\Pricing\Helper\Data                   $helper
     * @param \ECInternet\Pricing\Logger\Logger                 $logger
     * @param \ECInternet\Pricing\Model\Config                  $config
     */
    public function __construct(
        PriceCurrencyInterface $priceCurrency,
        Data $helper,
        Logger $logger,
        Config $config
    ) {
        $this->priceCurrency  = $priceCurrency;
        $this->helper         = $helper;
        $this->logger         = $logger;
        $this->config         = $config;
    }

    /**
     * Get product price
     *
     * @param \Magento\Catalog\Model\Product $product
     *
     * @return string|null
     */
    public function getPriceHtml(
        Product $product
    ) {
        if (!$this->config->isModuleEnabled()) {
            return null;
        }

        if ($pricingSystem = $this->helper->getPricingSystem()) {
            $price = $pricingSystem->getPrice($product->getSku());

            $this->log('getPriceHtml()', [
                'sku'           => $product->getSku(),
                'pricingSystem' => $pricingSystem->getName(),
                'price'         => $price
            ]);

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
        $this->logger->info('ViewModel/ProductPrice - ' . $message, $extra);
    }
}
