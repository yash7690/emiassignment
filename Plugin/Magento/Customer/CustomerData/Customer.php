<?php

/**
 * Adobe_EmiAssignment
 *
 * @category  PHP
 * @package   Adobe_EmiAssignment
 * @author    Adobe <support@adobe.com>
 * @copyright 2022 Copyright (c) Adobe
 * @link      https://www.adobe.com/
 */

namespace Adobe\EmiAssignment\Plugin\Magento\Customer\CustomerData;

use Magento\Customer\Helper\Session\CurrentCustomer;

class Customer
{
    /**
     * @var CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @param CurrentCustomer $currentCustomer
     */
    public function __construct(
        CurrentCustomer $currentCustomer
    ) {
        $this->currentCustomer = $currentCustomer;
    }

    /**
     * @inheritdoc
     */
    public function afterGetSectionData($subject, $result)
    {
        if (is_array($result) && array_key_exists('firstname', $result)) {
            $customer = $this->currentCustomer->getCustomer();
            $result['gender'] = $customer->getGender();
        }

        return $result;
    }
}
