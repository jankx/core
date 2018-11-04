<?php
abstract class Foxy_Meta_Framework_Base implements Foxy_Meta_Framework_Interface {
	public function __consntruct() {
	}

	public function get( $field_name ) {

	}

	public function meta_title( $args ) {
		$title = '';
		if ( ! empty( $args['icon'] ) ) {
			$title .= sprintf( '<span class="foxy-meta-icon %s"></span> ', $args['icon'] );
		} elseif( ! empty( $args['image'] ) ) {
			$title .= sprintf(
				'<span class="foxy-image foxy-meta-image"><img src="%s" alt="%s"/></span> ',
				$args['image'],
				$args['title']
			);
		}
		$title .= sprintf( '<span class="foxy-title-text">%s<span>', $args['title'] );
		return $title;
	}

	public function group_all_fields( $fields ) {

	}
}
