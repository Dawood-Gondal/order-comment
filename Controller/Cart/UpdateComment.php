<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_OrderComment
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\OrderComment\Controller\Cart;

use M2Commerce\OrderComment\Model\Data\OrderCommentFactory;
use M2Commerce\OrderComment\Model\OrderCommentManagement;
use Magento\Checkout\Controller\Cart;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class UpdateComment extends Cart implements HttpPostActionInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var OrderCommentManagement
     */
    protected $orderCommentManagement;

    /**
     * @var OrderCommentFactory
     */
    protected $orderCommentFactory;

    /**
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param CheckoutSession $checkoutSession
     * @param StoreManagerInterface $storeManager
     * @param Validator $formKeyValidator
     * @param CustomerCart $cart
     * @param LoggerInterface $logger
     * @param OrderCommentManagement $orderCommentManagement
     * @param OrderCommentFactory $orderCommentFactory
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        CheckoutSession $checkoutSession,
        StoreManagerInterface $storeManager,
        Validator $formKeyValidator,
        CustomerCart $cart,
        LoggerInterface $logger,
        OrderCommentManagement $orderCommentManagement,
        OrderCommentFactory $orderCommentFactory
    ) {
        parent::__construct(
            $context,
            $scopeConfig,
            $checkoutSession,
            $storeManager,
            $formKeyValidator,
            $cart
        );
        $this->logger = $logger;
        $this->orderCommentManagement = $orderCommentManagement;
        $this->orderCommentFactory = $orderCommentFactory;
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        try {
            $comment = trim($this->getRequest()->getParam('order_comment', ''));
            $cartQuote = $this->cart->getQuote();
            $commentObj = $this->orderCommentFactory->create();
            $commentObj->setComment($comment);
            $this->orderCommentManagement->saveOrderComment($cartQuote->getId(), $commentObj);
            $this->messageManager->addSuccessMessage(__('Your comment has been saved.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('There was an error when updating the quote.'));
            $this->logger->critical($e->getMessage(), ['exception' => $e->getTraceAsString()]);
        }

        return $this->_goBack();
    }
}
