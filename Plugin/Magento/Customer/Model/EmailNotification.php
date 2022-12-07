<?php
/**
 * No Welcome Email
 * Copyright (C) 2020 Prodovo
 *
 * This file included in Prodovo/NoWelcomeEmail is licensed under OSL 3.0
 *
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Prodovo\NoWelcomeEmail\Plugin\Magento\Customer\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\EmailNotification as SubjectEmailNotification;
use Magento\Store\Model\ScopeInterface;

/**
 * Class EmailNotification
 * @package Prodovo\NoWelcomeEmail\Plugin\Magento\Customer\Model
 */
class EmailNotification
{
    /** @var ScopeConfigInterface */
    protected $_scopeConfig;

    /**
     * Construct
     *
     * EmailNotification constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * Intercept Login Email Sending
     *
     * @param \Magento\Customer\Model\EmailNotification $subject
     * @param \Closure $proceed
     * @param CustomerInterface $customer
     * @param string $type
     * @param string $backUrl
     * @param int|null $storeId
     * @param null $sendemailStoreId
     * @return mixed
     */
    public function aroundNewAccount(
        SubjectEmailNotification $subject,
        \Closure $proceed,
        CustomerInterface $customer,
        $type = SubjectEmailNotification::NEW_ACCOUNT_EMAIL_REGISTERED,
        $backUrl = '',
        $storeId = null,
        $sendemailStoreId = null
    ) {
        if ($type === null) {
            $type = $subject::NEW_ACCOUNT_EMAIL_REGISTERED;
        }

        $isEnabled = $this->_scopeConfig->getValue('customer/create_account/disable_welcome_email', ScopeInterface::SCOPE_STORE);

        if (!$isEnabled) {
            $result = $proceed($customer, $type, $backUrl, $storeId, $sendemailStoreId);
            return $result;
        }
    }
}
