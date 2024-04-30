<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Pricing\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

/**
 * Observer for 'layout_generate_blocks_after' event
 */
class LayoutGenerateBlocksAfter implements ObserverInterface
{
    /**
     * Remove existing tier pricing block if we're adding our own tier price block
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(Observer $observer)
    {
        if ($this->shouldRemoveBlock($observer)) {
            if ($layout = $observer->getData('layout')) {
                $layout->unsetElement('product.price.final');
            }
        }
    }

    /**
     * @return bool
     */
    private function shouldRemoveBlock(Observer $observer)
    {
        /** @var \Magento\Framework\View\Layout $layout */
        if ($layout = $observer->getData('layout')) {
            /** @var \ECInternet\Pricing\Block\Catalog\Product\View $customPricingBlock */
            if ($customPricingBlock = $layout->getBlock('product.price.ecinternet-pricing')) {
                return $customPricingBlock->hasCustomPrice();
            }
        }

        return false;
    }
}
