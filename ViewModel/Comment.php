<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_OrderComment
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\OrderComment\ViewModel;

use M2Commerce\OrderComment\Model\Config;
use M2Commerce\OrderComment\Model\Data\OrderComment;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Comment implements ArgumentInterface
{

    /**
     * @var Config
     */
    private $config;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @param Config $config
     * @param CheckoutSession $checkoutSession
     */
    public function __construct(
        Config $config,
        CheckoutSession $checkoutSession
    ) {
        $this->config = $config;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @return string|null
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getComment(): ?string
    {
        if ($this->checkoutSession->getQuoteId()) {
            return $this->checkoutSession->getQuote()->getData(OrderComment::COMMENT_FIELD_NAME);
        }
        return null;
    }

    /**
     * Get Max Length validation classes if character restriction is enabled
     *
     * @return string
     */
    public function getExtraClass(): string
    {
        $class = '';
        if ($maxLength = $this->config->getMaximumCharacterLength()) {
            $class .= 'validate-length maximum-length-' . $maxLength;
        }
        return $class;
    }
}
