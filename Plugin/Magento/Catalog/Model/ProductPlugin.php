<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Pricing\Plugin\Magento\Catalog\Model;

use Magento\Catalog\Model\Product;
use ECInternet\Pricing\Helper\Data;
use ECInternet\Pricing\Model\Config;
use Psr\Log\LoggerInterface;

/**
 * Plugin for Magento\Catalog\Model\Product
 */
class ProductPlugin
{
    /**
     * @var \ECInternet\Pricing\Helper\Data
     */
    private $helper;

    /**
     * @var \ECInternet\Pricing\Model\Config
     */
    private $config;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * ProductPlugin constructor.
     *
     * @param \ECInternet\Pricing\Helper\Data  $helper
     * @param \ECInternet\Pricing\Model\Config $config
     * @param \Psr\Log\LoggerInterface         $logger
     */
    public function __construct(
        Data $helper,
        Config $config,
        LoggerInterface $logger
    ) {
        $this->helper = $helper;
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * After getPrice()
     *
     * @param \Magento\Catalog\Model\Product $subject
     * @param float                          $result
     *
     * @return float
     * @noinspection PhpMissingParamTypeInspection
     */
    public function afterGetPrice(
        Product $subject,
        $result
    ) {
        if (!$this->config->isModuleEnabled()) {
            return $result;
        }

        // Cache sku
        $sku = $subject->getSku();
        if (!$sku) {
            $this->log('afterGetPrice() - Unable to determine SKU.', ['product' => $subject->getData()]);
            return $result;
        }

        // Start logging
        $this->log('afterGetPrice() - ---------------------------------------');

        $price = $this->getPrice($sku);
        $this->log('afterGetPrice()', ['sku' => $sku, 'result' => $result, 'price' => $price]);

        if ($price == null) {
            $this->log('afterGetPrice() - Unable to calculate custom price.');
            $this->log('afterGetPrice() - Returning original price:', [$result]);
            $this->log('afterGetPrice() - ---------------------------------------' . PHP_EOL);

            return $result;
        }

        $this->log('afterGetPrice() - Returning custom price:', [$price]);
        $this->log('afterGetPrice() - ---------------------------------------' . PHP_EOL);

        return $price;
    }

    /**
     * @param string $sku
     *
     * @return float|null
     */
    protected function getPrice(
        string $sku
    ) {
        if ($pricingSystem = $this->helper->getPricingSystem()) {
            return $pricingSystem->getPrice($sku);
        } else {
            $this->log('getPrice() - Unable to determine pricing system.');
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
        $this->logger->info('[ECInternet_Pricing] Plugin/Magento/Catalog/Model/ProductPlugin - ' . $message, $extra);
    }
}
