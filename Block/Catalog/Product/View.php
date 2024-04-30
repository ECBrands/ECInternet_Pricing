<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Pricing\Block\Catalog\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Helper\Product as ProductHelper;
use Magento\Catalog\Model\ProductTypes\ConfigInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Json\EncoderInterface as JsonEncoderInterface;
use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\Url\EncoderInterface as UrlEncoderInterface;
use ECInternet\Pricing\Helper\Data;
use ECInternet\Pricing\Logger\Logger;

/**
 * Catalog Product View Block
 */
class View extends \Magento\Catalog\Block\Product\View
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
     * View constructor.
     *
     * @param \Magento\Catalog\Block\Product\Context                   $context
     * @param \Magento\Catalog\Api\ProductRepositoryInterface          $productRepository
     * @param \Magento\Catalog\Helper\Product                          $productHelper
     * @param \Magento\Catalog\Model\ProductTypes\ConfigInterface      $productTypeConfig
     * @param \Magento\Customer\Model\Session                          $customerSession
     * @param \Magento\Framework\Json\EncoderInterface                 $jsonEncoder
     * @param \Magento\Framework\Locale\FormatInterface                $localeFormat
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface        $priceCurrency
     * @param \Magento\Framework\Stdlib\StringUtils                    $string
     * @param \Magento\Framework\Url\EncoderInterface                  $urlEncoder
     * @param \ECInternet\Pricing\Helper\Data                          $helper
     * @param \ECInternet\Pricing\Logger\Logger                        $logger
     * @param array                                                    $data
     */
    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepository,
        ProductHelper $productHelper,
        ConfigInterface $productTypeConfig,
        CustomerSession $customerSession,
        JsonEncoderInterface $jsonEncoder,
        FormatInterface $localeFormat,
        PriceCurrencyInterface $priceCurrency,
        StringUtils $string,
        UrlEncoderInterface $urlEncoder,
        Data $helper,
        Logger $logger,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data
        );

        // Don't use per Magento - https://developer.adobe.com/commerce/php/development/cache/page/private-content/
        //$this->_isScopePrivate = true;

        $this->helper = $helper;
        $this->logger = $logger;
    }

    /**
     * @return string
     */
    public function getCustomPriceHtml()
    {
        if ($customPrice = $this->getCustomPrice()) {
            $this->log('getCustomPriceHtml()', ['customPrice' => $customPrice]);

            return $this->priceCurrency->convertAndFormat($customPrice);
        }

        return '';
    }

    /**
     * Can the pricing system derive a price?
     *
     * @return bool
     */
    public function hasCustomPrice()
    {
        return $this->getCustomPrice() !== null;
    }

    /**
     * Retrieve price using pricing system
     *
     * @return float|null
     */
    public function getCustomPrice()
    {
        $this->log('getCustomPrice()');

        if ($pricingSystem = $this->helper->getPricingSystem()) {
            if ($product = $this->getProduct()) {
                if ($sku = $product->getSku()) {
                    return $pricingSystem->getPrice($sku);
                }
            }
        }

        return null;
    }

    public function log(string $message, array $extra = [])
    {
        $this->logger->info('Block/Catalog/Product/View - ' . $message, $extra);
    }
}
