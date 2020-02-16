define([
    'jquery',
    'jquery/ui',
    'Alexeyb_Chat/js/popup'
], function ($) {
    'use strict';

    $.widget('alexeybChat.buttonDestroyWidget', {
        /**
         * @private
         */
        _create: function () {
            $(this.element).on('click.alexeyb_chat_x', function () {
                if ($.alexeybChat.popup) {
                    $($('#alexeyb-chat-popup').get(0)).data('alexeybChatPopup').destroy();
                } else {
                    $($('#alexeyb-chat-popup').get(0)).popup();
                }
            });
        },

        /**
         * @private
         */
        _destroy: function () {
            $(this.element).off('click.alexeyb_chat_x');
        }
    });

    return $.alexeybChat.buttonDestroyWidget;
});
