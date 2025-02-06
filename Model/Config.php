<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Pricing\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    private const CONFIG_PATH_ENABLED                           = 'pricing/general/enable';

    private const CONFIG_PATH_PRICING_SYSTEM                    = 'pricing/system_maintenance/pricing_system';

    private const CONFIG_PATH_TEMPLATE_OVERRIDE_CATEGORY_VIEW   = 'pricing/template_overrides/catalog_category_view';

    private const CONFIG_PATH_TEMPLATE_OVERRIDE_PRODUCT_VIEW    = 'pricing/template_overrides/catalog_product_view';

    private const CONFIG_PATH_TEMPLATE_OVERRIDE_ADVANCED_SEARCH = 'pricing/template_overrides/catalogsearch_advanced_result';

    private const CONFIG_PATH_TEMPLATE_OVERRIDE_CATALOG_SEARCH  = 'pricing/template_overrides/catalogsearch_result_index';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Config constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
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

    public function shouldOverrideCategoryView()
    {
        return $this->scopeConfig->isSetFlag(self::CONFIG_PATH_TEMPLATE_OVERRIDE_CATEGORY_VIEW);
    }

    public function shouldOverrideProductView()
    {
        return $this->scopeConfig->isSetFlag(self::CONFIG_PATH_TEMPLATE_OVERRIDE_PRODUCT_VIEW);
    }

    public function shouldOverrideAdvancedSearch()
    {
        return $this->scopeConfig->isSetFlag(self::CONFIG_PATH_TEMPLATE_OVERRIDE_ADVANCED_SEARCH);
    }

    public function shouldOverrideCatalogSearch()
    {
        return $this->scopeConfig->isSetFlag(self::CONFIG_PATH_TEMPLATE_OVERRIDE_CATALOG_SEARCH);
    }
}
