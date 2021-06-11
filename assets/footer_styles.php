<?php
switch ($numOfFooterWidgets) {
    case 2:
        ?>
        @media(min-width: 767px) {
            .footer-widgets-area .jankx-footer-widget{
                width: 100%;
            }
        }
        @media(min-width: 991px) {
            .footer-widgets-area .jankx-footer-widget{
                width: 50%;
            }
        }
        <?php
        break;
    case 3:
        ?>
        @media(min-width: 767px) {
            .footer-widgets-area .jankx-footer-widget.widget-area-1 {
                width: 100%;
            }
            .footer-widgets-area .jankx-footer-widget.widget-area-2, .footer-widgets-area .jankx-footer-widget.widget-area-3 {
                width: 50%;
            }
        }
        @media(min-width: 991px) {
            .footer-widgets-area .jankx-footer-widget.widget-area-1{
                width: 50%;
            }
            .footer-widgets-area .jankx-footer-widget.widget-area-2, .footer-widgets-area .jankx-footer-widget.widget-area-3 {
                width: 25%;
            }
        }
        @media (min-width: 1200px) {
            .footer-widgets-area .jankx-footer-widget.widget-area-1 {
                width: 45%;
            }
            .footer-widgets-area .jankx-footer-widget.widget-area-2, .footer-widgets-area .jankx-footer-widget.widget-area-3 {
                width: 27.5%;
            }
        }';
        <?php
        break;
    case 4:
        ?>
        @media(min-width: 767px) {
            .footer-widgets-area .jankx-footer-widget {
                width: 50%;
            }
        }
        @media(min-width: 991px) {
            .footer-widgets-area .jankx-footer-widget {
                width: 25%;
            }
        }';
        <?php
        break;
    default:
        ?>
        .footer-widgets-area .jankx-footer-widget {
            float: none;
            width: 100%;
        }
        <?php
        break;
}
