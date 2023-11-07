/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_OrderComment
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/additional-validators',
    'M2Commerce_OrderComment/js/model/checkout/order-comment-validator'
], function (Component, additionalValidators, commentValidator) {
    'use strict';

    additionalValidators.registerValidator(commentValidator);
    return Component.extend({});
});
