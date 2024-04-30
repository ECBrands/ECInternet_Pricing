<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Pricing\Api;

interface PricingSystemPoolInterface
{
    /**
     * Get all pricing systems
     *
     * @return \ECInternet\Pricing\Api\Data\PricingSystemInterface[]
     */
    public function getPricingSystems();

    /**
     * Get pricing system by name
     *
     * @param string $pricingSystemName
     *
     * @return \ECInternet\Pricing\Api\Data\PricingSystemInterface|null
     */
    public function getPricingSystem(string $pricingSystemName);
}
