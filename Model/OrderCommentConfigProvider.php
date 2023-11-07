<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_OrderComment
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\OrderComment\Model;

use M2Commerce\OrderComment\Model\Data\OrderComment;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session;

class OrderCommentConfigProvider implements ConfigProviderInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @param Config $scopeConfig
     * @param Session $checkoutSession
     */
    public function __construct(
        Config  $scopeConfig,
        Session $checkoutSession
    ) {
        $this->config = $scopeConfig;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getConfig()
    {
        $comment = '';
        if ($this->checkoutSession->getQuoteId()) {
            $comment = $this->checkoutSession->getQuote()->getData(OrderComment::COMMENT_FIELD_NAME) ?: '';
        }

        return [
            'show_in_checkout' => $this->config->canShowInCheckout(),
            'max_length' => (int) $this->config->getMaximumCharacterLength(),
            'comment_initial_collapse_state' => (int) $this->config->getInitialCollapseState(),
            'existing_comment' => $comment
        ];
    }
}
