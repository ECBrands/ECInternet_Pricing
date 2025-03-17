<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Pricing\Plugin\Magento\Framework\View\Element;

use Magento\Framework\App\RequestInterface;
use ECInternet\Pricing\Logger\Logger;
use ECInternet\Pricing\Model\Config;

class TemplatePlugin
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * @var \ECInternet\Pricing\Logger\Logger
     */
    private $logger;

    /**
     * @var \ECInternet\Pricing\Model\Config
     */
    private $config;

    /**
     * @var string
     */
    private $moduleName;

    /**
     * @var string
     */
    private $controllerName;

    /**
     * @var string
     */
    private $actionName;

    public function __construct(
        RequestInterface $request,
        Logger $logger,
        Config $config
    ) {
        $this->request = $request;
        $this->logger  = $logger;
        $this->config  = $config;
    }

    /**
     * Modify the template of a block.
     *
     * @param \Magento\Framework\View\Element\Template $subject
     * @param string|null                              $result
     *
     * @return string|null
     */
    public function afterGetTemplate(\Magento\Framework\View\Element\Template $subject, $result)
    {
        if (!$this->config->isModuleEnabled()) {
            return $result;
        }

        // Get the controller, action, and module names
        $this->moduleName     = $this->request->getModuleName();      // e.g. 'catalogsearch'
        $this->controllerName = $this->request->getControllerName();  // e.g. 'result'
        $this->actionName     = $this->request->getActionName();      // e.g. 'index'

        $this->log('execute()', [
            'moduleName'     => $this->moduleName,
            'controllerName' => $this->controllerName,
            'actionName'     => $this->actionName,
        ]);

        $layoutHandle = $this->getLayoutHandle();
        $this->log('execute()', ['layoutHandle' => $layoutHandle]);

        // Catalog Category View / category.products.list
        if ($layoutHandle === 'catalog_category_view') {
            if ($this->config->shouldOverrideCategoryView()) {
                if ($subject->getNameInLayout() == 'category.products.list') {
                    return 'ECInternet_Pricing::catalog/product/list.phtml';
                }
            }
        }

        // Catalogsearch Result Index / search_result_list
        if ($layoutHandle === 'catalogsearch_result_index') {
            if ($this->config->shouldOverrideCatalogSearch()) {
                if ($subject->getNameInLayout() == 'search_result_list') {
                    return 'ECInternet_Pricing::catalog/product/list.phtml';
                }
            }
        }

        // Catalogsearch Advanced Result / search_result_list
        if ($layoutHandle === 'catalogsearch_advanced_result') {
            if ($this->config->shouldOverrideAdvancedSearch()) {
                if ($subject->getNameInLayout() == 'search_result_list') {
                    return 'ECInternet_Pricing::catalog/product/list.phtml';
                }
            }
        }

        // Return the original result if no changes are made
        return $result;
    }

    /**
     * @return string
     */
    private function getLayoutHandle()
    {
        return implode('_', [$this->moduleName, $this->controllerName, $this->actionName]);
    }

    private function log(string $message, array $extra = [])
    {
        $this->logger->info('TemplatePlugin - ' . $message, $extra);
    }
}
