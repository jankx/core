<?php
abstract class Jankx_Meta_Framework_Base implements Jankx_Meta_Framework_Interface {
	public function meta_title( $args ) {
		$title = '';
		if ( ! empty( $args['icon'] ) ) {
			$title .= sprintf( '<span class="jankx-meta-icon %s"></span> ', $args['icon'] );
		} elseif ( ! empty( $args['image'] ) ) {
			$title .= sprintf(
				'<span class="jankx-image jankx-meta-image"><img src="%s" alt="%s"/></span> ',
				$args['image'],
				$args['title']
			);
		}
		$title .= sprintf( '<span class="jankx-title-text">%s</span>', $args['title'] );
		return $title;
	}
}
