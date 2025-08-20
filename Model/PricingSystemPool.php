<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Pricing\Model;

use ECInternet\Pricing\Api\PricingSystemPoolInterface;
use ECInternet\Pricing\Api\Data\PricingSystemInterface;
use Psr\Log\LoggerInterface;

class PricingSystemPool implements PricingSystemPoolInterface
{
    /**
     * @var \ECInternet\Pricing\Api\Data\PricingSystemInterface[]
     */
    private $pricingSystems;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * PricingSystemPool constructor.
     *
     * @param \Psr\Log\LoggerInterface $logger
     * @param array                    $pricingSystems
     */
    public function __construct(
        LoggerInterface $logger,
        array $pricingSystems = []
    ) {
        $this->logger = $logger;

        foreach ($pricingSystems as $pricingSystemName => $pricingSystem) {
            if (!$pricingSystem instanceof PricingSystemInterface) {
                $this->log(
                    sprintf(
                        'Pricing System %s does not implement %s',
                        $pricingSystemName,
                        PricingSystemInterface::class
                    )
                );
            }
        }

        $this->pricingSystems = $pricingSystems;
    }

    public function getPricingSystems()
    {
        return $this->pricingSystems;
    }

    public function getPricingSystem(string $pricingSystemName)
    {
        //$this->log('getPricingSystem()', ['pricingSystemName' => $pricingSystemName]);

        if (array_key_exists($pricingSystemName, $this->pricingSystems)) {
            return $this->pricingSystems[$pricingSystemName];
        }

        $this->log(sprintf('Pricing System %s does not exist', $pricingSystemName));

        return null;
    }

    private function log(string $message, array $extra = [])
    {
        $this->logger->info('[ECInternet_Pricing] Model/PricingSystemPool - ' . $message, $extra);
    }
}
