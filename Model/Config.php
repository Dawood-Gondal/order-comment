<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_OrderComment
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\OrderComment\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    const XML_PATH_CONFIG_MAX_LENGTH = 'order_comments/general/max_length';
    const XML_PATH_CONFIG_FIELD_COLLAPSE_STATE = 'order_comments/general/collapse_state';
    const XML_PATH_CONFIG_SHOW_IN_CHECKOUT = 'order_comments/general/show_in_checkout';
    const XML_PATH_CONFIG_SHOW_IN_ACCOUNT = 'order_comments/general/show_in_account';
    const XML_PATH_CONFIG_SHOW_IN_CART = 'order_comments/general/show_in_cart';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param $website
     * @return bool
     */
    public function canShowInCheckout($website = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_CONFIG_SHOW_IN_CHECKOUT,
            ScopeInterface::SCOPE_WEBSITE,
            $website
        );
    }

    /**
     * @param $website
     * @return bool
     */
    public function canShowInAccount($website = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_CONFIG_SHOW_IN_ACCOUNT,
            ScopeInterface::SCOPE_WEBSITE,
            $website
        );
    }

    /**
     * @param $website
     * @return bool
     */
    public function canShowInCart($website = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_CONFIG_SHOW_IN_CART,
            ScopeInterface::SCOPE_WEBSITE,
            $website
        );
    }

    /**
     * @param mixed $website
     * @return mixed
     */
    public function getMaximumCharacterLength($website = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CONFIG_MAX_LENGTH,
            ScopeInterface::SCOPE_WEBSITE,
            $website
        );
    }

    /**
     * @param mixed $website
     * @return mixed
     */
    public function getInitialCollapseState($website = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CONFIG_FIELD_COLLAPSE_STATE,
            ScopeInterface::SCOPE_WEBSITE,
            $website
        );
    }
}
