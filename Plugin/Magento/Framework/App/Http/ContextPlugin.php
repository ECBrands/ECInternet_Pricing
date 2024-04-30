<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Pricing\Plugin\Magento\Framework\App\Http;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Http\Context;
use ECInternet\Pricing\Logger\Logger;
use Exception;

/**
 * Plugin for Magento\Framework\App\Http\Context
 */
class ContextPlugin
{
    private const CUSTOMER_ATTRIBUTE_CUSTOMER_NUMBER = 'customer_number';

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var \ECInternet\Pricing\Logger\Logger
     */
    private $logger;

    /**
     * @param \Magento\Customer\Model\Session   $customerSession
     * @param \ECInternet\Pricing\Logger\Logger $logger
     */
    public function __construct(
        CustomerSession $customerSession,
        Logger $logger
    ) {
        $this->customerSession = $customerSession;
        $this->logger          = $logger;
    }
    public function beforeGetVaryString(
        Context $subject
    ) {
        $this->log('beforeGetVaryString()');

        if ($customerNumber = $this->getCustomerNumber()) {
            $this->log('beforeGetVaryString()', ['customer_number' => $customerNumber]);

            $subject->setValue('CONTEXT_CUSTOMER_NUMBER', $customerNumber, 0);
        } else {
            $this->log('beforeGetVaryString()', ['customer_number' => 'null']);
        }
    }

    /**
     * Get 'customer_number' attribute value
     *
     * @return string|null
     */
    private function getCustomerNumber()
    {
        $this->log('getCustomerNumber()');

        if ($customerData = $this->getCustomerData()) {
            if ($customerNumberAttribute = $customerData->getCustomAttribute(self::CUSTOMER_ATTRIBUTE_CUSTOMER_NUMBER)) {
                return (string)$customerNumberAttribute->getValue();
            } else {
                $this->log('getCustomerNumber()', ['exception' => 'customer_number attribute not found']);
            }
        } else {
            $this->log('getCustomerNumber()', ['exception' => 'customerData is null']);
        }

        return null;
    }

    private function getCustomerData()
    {
        $this->log('getCustomerData()');

        try {
            return $this->customerSession->getCustomerData();
        } catch (Exception $e) {
            $this->log('getCustomerData()', ['exception' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Write to extension log
     *
     * @param string $message
     * @param array  $extra
     *
     * @return void
     */
    private function log(string $message, array $extra = [])
    {
        $this->logger->info('Plugin/Magento/Framework/App/Http/ChangeCustomerContext - ' . $message, $extra);
    }
}
