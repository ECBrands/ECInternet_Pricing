<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Pricing\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use ECInternet\Pricing\Api\PricingSystemPoolInterface;

class PricingSystem implements OptionSourceInterface
{
    /**
     * @var \ECInternet\Pricing\Api\PricingSystemPoolInterface
     */
    private $pricingSystemPool;

    /**
     * PricingSystem constructor.
     *
     * @param \ECInternet\Pricing\Api\PricingSystemPoolInterface $pricingSystemPool
     */
    public function __construct(
        PricingSystemPoolInterface $pricingSystemPool
    ) {
        $this->pricingSystemPool = $pricingSystemPool;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];

        $pricingSystems = $this->pricingSystemPool->getPricingSystems();
        foreach ($pricingSystems as $pricingSystem) {
            $options[] = [
                'value' => $pricingSystem->getName(),
                'label' => $pricingSystem->getName(),
            ];
        }

        return $options;
    }
}
