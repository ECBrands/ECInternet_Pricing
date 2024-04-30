<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Pricing\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use ECInternet\Pricing\Logger\Logger;

/**
 * Observer for 'catalog_product_get_final_price' event
 */
class CatalogProductGetFinalPrice implements ObserverInterface
{
    /**
     * @var \ECInternet\Pricing\Logger\Logger
     */
    private $logger;

    public function __construct(
        Logger $logger
    ) {
        $this->logger = $logger;
    }

    public function execute(
        Observer $observer
    ) {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $observer->getData('product');

        /** @var float|null $qty */
        $qty = $observer->getData('qty');

        $this->log('execute()', [
            'product' => $product->getSku(),
            'qty'     => $qty
        ]);
    }

    private function log(string $message, array $extra = [])
    {
        $this->logger->info('Observer/CatalogProductGetFinalPrice - ' . $message, $extra);
    }
}