<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Pricing\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Layout;
use ECInternet\Pricing\Model\Config;

/**
 * Observer for 'layout_generate_blocks_after' event
 */
class LayoutGenerateBlocksAfter implements ObserverInterface
{
    /**
     * @var \ECInternet\Pricing\Model\Config
     */
    private $config;

    /**
     * LayoutGenerateBlocksAfter constructor.
     *
     * @param \ECInternet\Pricing\Model\Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * Remove existing price block if we're adding our own price block
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(Observer $observer)
    {
        if (!$this->config->isModuleEnabled()) {
            return;
        }

        // Get the layout object
        if ($layout = $observer->getData('layout')) {
            if ($this->shouldRemoveBlock($layout)) {
                $layout->unsetElement('product.price.final');
            }
        }
    }

    /**
     * @param \Magento\Framework\View\Layout $layout
     *
     * @return bool
     */
    private function shouldRemoveBlock(Layout $layout)
    {
        /** @var \ECInternet\Pricing\Block\Catalog\Product\View $customPricingBlock */
        if ($customPricingBlock = $layout->getBlock('product.price.ecinternet-pricing')) {
            return $customPricingBlock->hasCustomPrice();
        }

        return false;
    }
}
