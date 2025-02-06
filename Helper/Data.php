<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Pricing\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use ECInternet\Pricing\Api\PricingSystemPoolInterface;
use ECInternet\Pricing\Model\Config;

/**
 * Helper
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Data extends AbstractHelper
{
    /**
     * @var \ECInternet\Pricing\Api\PricingSystemPoolInterface
     */
    private $pricingSystemPool;

    /**
     * @var \ECInternet\Pricing\Model\Config
     */
    private $config;

    /**
     * Data constructor.
     *
     * @param \Magento\Framework\App\Helper\Context              $context
     * @param \ECInternet\Pricing\Api\PricingSystemPoolInterface $pricingSystemPool
     * @param \ECInternet\Pricing\Model\Config                   $config
     */
    public function __construct(
        Context $context,
        PricingSystemPoolInterface $pricingSystemPool,
        Config $config
    ) {
        parent::__construct($context);

        $this->pricingSystemPool = $pricingSystemPool;
        $this->config            = $config;
    }

    /**
     * @return \ECInternet\Pricing\Api\Data\PricingSystemInterface|null
     */
    public function getPricingSystem()
    {
        if ($pricingSystemSetting = $this->config->getPricingSystemSetting()) {
            return $this->pricingSystemPool->getPricingSystem($pricingSystemSetting);
        }

        return null;
    }
}
