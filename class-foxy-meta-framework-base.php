<?php
abstract class Foxy_Meta_Framework_Base implements Foxy_Meta_Framework_Interface {
	public function meta_title( $args ) {
		$title = '';
		if ( ! empty( $args['icon'] ) ) {
			$title .= sprintf( '<span class="foxy-meta-icon %s"></span> ', $args['icon'] );
		} elseif ( ! empty( $args['image'] ) ) {
			$title .= sprintf(
				'<span class="foxy-image foxy-meta-image"><img src="%s" alt="%s"/></span> ',
				$args['image'],
				$args['title']
			);
		}
		$title .= sprintf( '<span class="foxy-title-text">%s</span>', $args['title'] );
		return $title;
	}

	public function group_all_fields( $original_fields ) {
		$tabs = array();
		$fields = array();
		foreach ( $original_fields as $field ) {
			if ( 'tab' === $field['type'] ) {
				$tabs = array_merge( $tabs, $field );
			} else {
				if ( ! empty( $field['tab'] ) ) {
					$fields[ $field['tab'] ][] = $field;
				} else {
					$fields['fxng'][] = $field;
				}
			}
		}
		return array( $tabs, $fields );
	}

	public function filter_post_type_metas( $post, $metas ) {
		$results = array();
		foreach ( $metas as $id => $args ) {
			if ( ! in_array( $post->post_type, (array) $args['post_type'], true ) ) {
				// Free up memory.
				unset( $metas[ $id ] );
				continue;
			}
			$results = array_merge( $results, $args['fields'] );
			// Free up memory.
			unset( $metas[ $id ] );
		}
		return $results;
	}
}
