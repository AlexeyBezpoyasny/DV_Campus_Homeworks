define([
    'jquery',
    'jquery/ui',
    'mage/translate',
    'Alexeyb_Chat/js/buttonOpen'
], function ($) {
    'use strict';

    $.widget('alexeybChat.buttonOpenHeader', $.alexeybChat.buttonOpen, {
        options: {
            hideButton: false
        }
    });

    return $.alexeybChat.buttonOpenHeader;
});
