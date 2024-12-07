<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Pricing\Pricing\Price;

use Magento\Framework\Pricing\Price\AbstractPrice;

class CustomPrice extends AbstractPrice
{
    const PRICE_CODE = 'ecinternet_custom_price';

    public function getValue()
    {
        return 123;
    }
}