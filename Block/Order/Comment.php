<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_OrderComment
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\OrderComment\Block\Order;

use M2Commerce\OrderComment\Model\Data\OrderComment;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context as TemplateContext;

/**
 * Block Class
 */
class Comment extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Registry|null
     */
    protected $coreRegistry = null;

    /**
     * @param TemplateContext $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        TemplateContext $context,
        Registry $registry,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->_template = 'order/view/comment.phtml';
        parent::__construct($context, $data);
    }

    /**
     * @return mixed|null
     */
    public function getOrder()
    {
        return $this->coreRegistry->registry('current_order');
    }

    /**
     * @return string
     */
    public function getOrderComment()
    {
        return trim((string) $this->getOrder()->getData(OrderComment::COMMENT_FIELD_NAME));
    }

    /**
     * @return bool
     */
    public function hasOrderComment()
    {
        return strlen($this->getOrderComment()) > 0;
    }

    /**
     * @return string
     */
    public function getOrderCommentHtml()
    {
        return nl2br($this->_escaper->escapeHtml($this->getOrderComment()));
    }
}
