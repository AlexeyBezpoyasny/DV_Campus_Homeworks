define([
    'jquery',
    'jquery-ui-modules/widget',
    'mage/translate'
], function ($) {
    'use strict';

    $.widget('alexeybChat.buttonOpen', {
        options: {
            hideButton: true
        },

        /**
         * @private
         */
        _create: function () {
            $(this.element).on('click.alexeyb_chat', $.proxy(this.openPopup, this));
            $(this.element).on('alexeyb_chat_closePopup.alexeyb_chat', $.proxy(this.closePopup, this));
        },

        /**
         * @private
         */
        _destroy: function () {
            $(this.element).off('click.alexeyb_chat');
            $(this.element).off('alexeyb_chat_closePopup.alexeyb_chat');
        },

        openPopup: function () {
            $(document).trigger('alexeyb_chat_openPopup');

            if (this.options.hideButton) {
                $(this.element).removeClass('active');
            }
        },

        closePopup: function () {
            $(this.element).addClass('active');
        }
    });

    return $.alexeybChat.buttonOpen;
});
