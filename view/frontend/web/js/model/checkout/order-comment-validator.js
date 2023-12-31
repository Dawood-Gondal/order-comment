/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_OrderComment
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

define([
    'jquery',
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/url-builder',
    'mage/url',
    'Magento_Checkout/js/model/error-processor',
    'Magento_Ui/js/model/messageList',
    'mage/translate'
], function ($, customer, quote, urlBuilder, urlFormatter, errorProcessor, messageContainer, __) {
    'use strict';

    return {

        /**
         * Make an ajax PUT request to store the order comment in the quote.
         *
         * @returns {Boolean}
         */
        validate: function () {
            var isCustomer = customer.isLoggedIn(),
                form = this.getForm(),
                quoteId = quote.getQuoteId(),
                url,
                comment = form.find('.input-text.order-comment').val();

            if (this.hasMaxLength() && comment.length > this.getMaxLength()) {
                messageContainer.addErrorMessage({ message: __("Comment is too long") });
                return false;
            }

            if (isCustomer) {
                url = urlBuilder.createUrl('/carts/mine/set-order-comment', {})
            } else {
                url = urlBuilder.createUrl('/guest-carts/:cartId/set-order-comment', {cartId: quoteId});
            }

            var payload = {
                cartId: quoteId,
                orderComment: {
                    comment: comment
                }
            };

            if (!payload.orderComment.comment) {
                return true;
            }

            var result = true;

            $.ajax({
                url: urlFormatter.build(url),
                data: JSON.stringify(payload),
                global: false,
                contentType: 'application/json',
                type: 'PUT',
                async: false,
                loader: true
            }).done(
                function (response) {
                    result = true;
                }
            ).fail(
                function (response) {
                    result = false;
                    errorProcessor.process(response);
                }
            );

            return result;
        },
        getForm: function () {
            var form =  $('.payment-method input[name="payment[method]"]:checked')
                .parents('.payment-method')
                .find('form.order-comment-form');

            // Compatibility for Rubic_CleanCheckout
            if (!form.length) {
                form = $('form.order-comment-form');
            }

            return form;
        },
        hasMaxLength: function () {
            return window.checkoutConfig.max_length > 0;
        },
        getMaxLength: function () {
            return window.checkoutConfig.max_length;
        }
    };
});
