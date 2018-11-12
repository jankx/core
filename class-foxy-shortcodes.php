<?php
class Foxy_Shortcodes {
	public static function shortcodes() {
		add_shortcode( 'toc', array( __CLASS__, 'shortcode_table_of_content' ) );
	}

	public static function shortcode_table_of_content( $content, $attr ) {
	}
}
