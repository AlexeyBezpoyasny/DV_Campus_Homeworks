define([
    'jquery',
    'jquery/ui',
    'mage/translate'
], function ($) {
    'use strict';

    $.widget('alexeybChat.popup', {
        options: {
            closePopup: '#alexeyb-chat-close-popup-button',
            openChatButton: '.button-open-chat'
        },

        /**
         * @private
         */
        _create: function () {
            $(document).on('alexeyb_chat_openPopup.alexeyb_chat', $.proxy(this.openPopup, this));
            $(this.options.closePopup).on('click.alexeyb_chat', $.proxy(this.closePopup, this));
            $(this.element).show();
        },

        /**
         * @private
         */
        _destroy: function () {
            $(document).off('alexeyb_chat_openPopup.alexeyb_chat');
            $(this.options.closePopup).off('click.alexeyb_chat');
        },

        openPopup: function () {
            $(this.element).addClass('active');
        },

        closePopup: function () {
            $(this.element).removeClass('active');
            $(this.options.openChatButton).trigger('alexeyb_chat_closePopup');
        }
    });

    return $.alexeybChat.popup;
});
