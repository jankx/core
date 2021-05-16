<?php

switch ($numOfFooterWidgets) {
    case 2:
        style(".footer-widgets-area .jankx-footer-widget{
            width: 100%;
        }", "(min-width: 767px)");
        style(".footer-widgets-area .jankx-footer-widget{
            width: 50%;
        }", "(min-width: 991px)");
        break;
    case 3:
        style(".footer-widgets-area .jankx-footer-widget.widget-area-1 {
            width: 100%;
        }
        .footer-widgets-area .jankx-footer-widget.widget-area-2, .footer-widgets-area .jankx-footer-widget.widget-area-3 {
            width: 50%;
        }", "(min-width: 767px)");
        style(".footer-widgets-area .jankx-footer-widget.widget-area-1{
            width: 50%;
        }
        .footer-widgets-area .jankx-footer-widget.widget-area-2, .footer-widgets-area .jankx-footer-widget.widget-area-3 {
            width: 25%;
        }", "(min-width: 991px)");
        style(".footer-widgets-area .jankx-footer-widget.widget-area-1 {
            width: 45%;
        }
        .footer-widgets-area .jankx-footer-widget.widget-area-2, .footer-widgets-area .jankx-footer-widget.widget-area-3 {
            width: 27.5%;
        }", "(min-width: 1200px)");
        break;
    case 4:
        style(".footer-widgets-area .jankx-footer-widget {
            width: 50%;
        }", "(min-width: 767px)");
        style(".footer-widgets-area .jankx-footer-widget {
            width: 25%;
        }", "(min-width: 991px)");
        break;
    default:
        style(".footer-widgets-area .jankx-footer-widget {
            float: none;
            width: 100%;
        }", "all");
        break;
}
