# Magento2 Module ECInternet_Pricing
``ecinternet_pricing - 0.0.1``

- [Requirements](#requirements-header)
- [Overview](#overview-header)
- [Installation](#installation-header)
- [Configuration](#configuration-header)
- [Design Modifications](#design-modifications-header)
- [Specifications](#specifications-header)
- [Attributes](#attributes-header)
- [Notes](#notes-header)
- [Version History](#version-history-header)

## Requirements

## Overview

## Installation Instructions
- Extract the zip to your Magento 2 root directory to create the following folder structure: `app/code/ECInternet/Pricing`
- Run `php -f bin/magento module:enable ECInternet_Pricing`
- Run `php -f bin/magento setup:upgrade`
- Run `php -f bin/magento setup:di:compile`
- Flush the Magento cache
- Done

## Configuration
- GENERAL
  - Enable Module
  - Enable Debug Logging
- SYSTEMS
  - Pricing System Maintenance

## Design Modifications
### Frontend
- Layout `catalog_category_view`
    - Block `category.products.list`
        - Update template to `ECInternet_Pricing::catalog/product/list.phtml`
        - Add `ProductPrice` view model to retrieve custom price


- Layout `catalog_product_view`
    - Block `product.info.main`
        - Add new block for tier price display
    - Block `product.price.final`
        - Remove existing block which shows possibly cached price
    - Block `product.info.price`
        - Add new block for product price display


- Layout `catalogsearch_advanced_result`
    - Block `search_result_list`
        - Update template to `ECInternet_Pricing::catalog/product/list.phtml`
        - Add `ProductPrice` view model to retrieve custom price


- Layout `catalogsearch_result_index`
    - Block `search_result_list`
        - Update template to `ECInternet_Pricing::catalog/product/list.phtml`
        - Add `ProductPrice` view model to retrieve custom price


- Observer `LayoutGenerateBlocksAfter` on `layout_generate_blocks_after`
  - Conditionally remove `product.price.final` element

## Specifications

## Attributes

## Features

## Notes

## Known Issues

## Version History
