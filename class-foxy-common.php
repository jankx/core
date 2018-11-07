<?php
/**
 * Foxy Common Services
 *
 * @package Foxy/Core
 * @subpackage Initilize
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @link https://wpclouds.com
 */

/**
 * Foxy_Common class
 */
class Foxy_Common {
	/**
	 * Supported site layouts
	 */
	const LAYOUT_FULL_WIDTH              = 0;
	const LAYOUT_CONTENT_SIDEBAR         = 1;
	const LAYOUT_SIDEBAR_CONTENT         = 4;
	const LAYOUT_CONTENT_SIDEBAR_SIDEBAR = 2;
	const LAYOUT_SIDEBAR_CONTENT_SIDEBAR = 5;
	const LAYOUT_SIDEBAR_SIDEBAR_CONTENT = 8;

	/**
	 * Post layouts
	 */
	const POST_LAYOUT_CARD_STYLE       = 'card';
	const POST_LAYOUT_LIST_STYLE       = 'list';
	const POST_LAYOUT_TIMELINE_STYLE   = 'timeline';
	const POST_LAYOUT_SLIDE_STYLE      = 'slide';
	const POST_LAYOUT_MANSORY_STYLE    = 'mansory';
	const POST_LAYOUT_LARGE_TOP_STYLE  = 'large_top';
	const POST_LAYOUT_LARGE_LEFT_STYLE = 'large_left';

	/**
	 * Taxonomy layouts
	 */
	const POST_CATEGORY_LAYOUT_CARD_STYLE = 'card';
	const POST_CATEGORY_LAYOUT_TAB_STYLE  = 'tab';
}
