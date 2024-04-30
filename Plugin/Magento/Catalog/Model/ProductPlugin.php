<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Pricing\Plugin\Magento\Catalog\Model;

use Magento\Catalog\Model\Product;
use ECInternet\Pricing\Helper\Data;
use ECInternet\Pricing\Logger\Logger;

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
     * @var \ECInternet\Pricing\Logger\Logger
     */
    private $logger;

    /**
     * ProductPlugin constructor.
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
        $this->log('afterGetPrice()', ['sku' => $subject->getSku(), 'result' => $result]);

        if ($this->helper->isModuleEnabled()) {
            $this->log('afterGetPrice() - ---------------------------------------');
            $this->log('afterGetPrice()', ['sku' => $subject->getSku(), 'price' => $result]);

            $price = $this->getPrice($subject->getSku());
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
        $this->logger->info('Plugin/Magento/Catalog/Model/ProductPlugin - ' . $message, $extra);
    }
}
