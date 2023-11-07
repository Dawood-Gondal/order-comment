<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_OrderComment
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\OrderComment\Model;

use M2Commerce\OrderComment\Api\OrderCommentManagementInterface;
use M2Commerce\OrderComment\Model\Data\OrderComment;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\ValidatorException;
use Magento\Quote\Api\CartRepositoryInterface;

class OrderCommentManagement implements OrderCommentManagementInterface
{
    /**
     * @var CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param CartRepositoryInterface $quoteRepository
     * @param Config $config
     */
    public function __construct(
        CartRepositoryInterface $quoteRepository,
        Config $config
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->config = $config;
    }

    /**
     * @param $cartId
     * @param \M2Commerce\OrderComment\Api\Data\OrderCommentInterface $orderComment
     * @return string|null
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     * @throws ValidatorException
     */
    public function saveOrderComment(
        $cartId,
        \M2Commerce\OrderComment\Api\Data\OrderCommentInterface $orderComment
    ) {
        $quote = $this->quoteRepository->getActive($cartId);
        if (!$quote->getItemsCount()) {
            throw new NoSuchEntityException(__('Cart %1 doesn\'t contain products', $cartId));
        }
        $comment = $orderComment->getComment();
        $this->validateComment($comment);

        try {
            $quote->setData(OrderComment::COMMENT_FIELD_NAME, strip_tags($comment));
            $this->quoteRepository->save($quote);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('The order comment could not be saved'));
        }

        return $comment;
    }

    /**
     * @param $comment
     * @return void
     * @throws ValidatorException
     */
    protected function validateComment($comment)
    {
        $maxLength = $this->config->getMaximumCharacterLength();
        if ($maxLength && (mb_strlen($comment) > $maxLength)) {
            throw new ValidatorException(__('Comment is too long'));
        }
    }
}
