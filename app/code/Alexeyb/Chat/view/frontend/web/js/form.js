define([
    'jquery',
    'jquery-ui-modules/widget',
    'mage/translate'
], function ($) {
    'use strict';

    $.widget('alexeybChat.form', {
        options: {
            action: '',
            submitMessage: '#alexeyb-chat-submit-message-button',
            textarea: '#alexeyb-chat-enter-message',
            dateMessage: '.alexeyb-chat-message-date',
            sentMessage: '.alexeyb-chat-messages'
        },

        /**
         * @private
         */
        _create: function () {
            $(this.element).on('submit.alexeyb_chat', $.proxy(this.saveMessage, this));
        },

        /**
         * @private
         */
        _destroy: function () {
            $(this.element).on('submit.alexeyb_chat');
        },

        /** validate and sent message */
        saveMessage: function () {
            if (!this.validateForm()) {
                return;
            }
            this.ajaxSubmit();
        },

        /** validate form */
        validateForm: function () {
            return $(this.element).validation().valid();
        },

        /** get messages from textarea and date */
        sentMessage: function () {
            var val = $(this.options.textarea).val();
            var date = new Date().toLocaleString();// jscs:ignore requireMultipleVarDecl

            $(this.options.dateMessage).html(date);
            $(this.options.sentMessage).html(val).addClass('message-success success message');
        },

        /** submit message to backend */
        ajaxSubmit: function () {
            var formData = new FormData($(this.element).get(0));

            formData.append('form_key', $.mage.cookies.get('form_key'));
            formData.append('isAjax', 1);

            $.ajax({
                url: this.options.action,
                data: formData,
                processData: false,
                contentType: false,
                type: 'post',
                dataType: 'json',
                context: this,

                /** @inheritdoc */
                beforeSend: function () {
                    $('body').trigger('processStart');
                },

                /** @inheritdoc */
                success: function (resultJson) {
                    $('body').trigger('processStop');
                    $('.alexeyb-chat-popup-message').text(resultJson.message);
                    $('#alexeyb-chat-enter-message').text($.proxy(this.sentMessage, this)).val('');
                },

                /** @inheritdoc */
                error: function () {
                    $('body').trigger('processStop');
                    $('.alexeyb-chat-popup-message').text('Your message dont be send');
                }
            });
        }
    });

    return $.alexeybChat.form;
});
