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

/**
 * Helper
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Data extends AbstractHelper
{
    const CONFIG_PATH_ENABLED        = 'pricing/general/enable';

    const CONFIG_PATH_PRICING_SYSTEM = 'pricing/system_maintenance/pricing_system';

    /**
     * @var \ECInternet\Pricing\Api\PricingSystemPoolInterface
     */
    private $pricingSystemPool;

    /**
     * Data constructor.
     *
     * @param \Magento\Framework\App\Helper\Context              $context
     * @param \ECInternet\Pricing\Api\PricingSystemPoolInterface $pricingSystemPool
     */
    public function __construct(
        Context $context,
        PricingSystemPoolInterface $pricingSystemPool
    ) {
        parent::__construct($context);

        $this->pricingSystemPool = $pricingSystemPool;
    }

    /**
     * Is module enabled?
     *
     * @return bool
     */
    public function isModuleEnabled()
    {
        return $this->scopeConfig->isSetFlag(self::CONFIG_PATH_ENABLED);
    }

    /**
     * Get selected pricing system
     *
     * @return mixed
     */
    public function getPricingSystemSetting()
    {
        return $this->scopeConfig->getValue(self::CONFIG_PATH_PRICING_SYSTEM);
    }

    /**
     * @return \ECInternet\Pricing\Api\Data\PricingSystemInterface|null
     */
    public function getPricingSystem()
    {
        if ($pricingSystemSetting = $this->getPricingSystemSetting()) {
            return $this->pricingSystemPool->getPricingSystem($pricingSystemSetting);
        }

        return null;
    }
}
