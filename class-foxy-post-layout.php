<?php

class Foxy_Post_Layout {
	public function supported_layout() {
	}

	public static function post_layout( $args = array(), $posts ) {
		if ( ! ( $posts instanceof WP_Query ) ) {
			// Check $posts variable is instance of WP_Query.
			throw new Exception( 'Argument #1 must be instance of WP_Query' );
		}
		/**
		 * Merge post layout settings with default settings
		 */
		$args = wp_parse_args(
			$args,
			array(
				'style' => 'card',
				'row_items' => 4,
				'carousel' => false,
			)
		);

		/**
		 * Clone style setting in argument to new variable
		 */
		$style = $args['style'];

		/**
		 * Generate css class for carousel
		 */
		$has_carousel = $args['carousel'] ? 'has-carousel' : 'not-carousel';

		$class_names = array(
			'post-layout',
			$has_carousel,
			sprintf( '%s-layout', $style ),
			sprintf( 'row-item-%s', $args['row_items'] ),
		);

		Foxy::ui()->tag(array(
			'class' => implode( ' ', $class_names ),
		));
		$wrap_class = $args['carousel'] ? 'post-layout-wrap owl-carousel' : 'post-layout-wrap';
		if ( $posts->have_posts() ) {
			Foxy::ui()->tag(array(
				'name' => 'section',
				'class' => $wrap_class,
			));
			do_action( 'foxy_post_layout_before_loop' );
			do_action( "foxy_post_layout_{$style}_before_loop" );
			while ( $posts->have_posts() ) {
				$posts->the_post();
				if ( Foxy::hook_is_empty( "foxy_post_layout_{$style}_loop" ) ) {
					$current_post_type = get_post_type();
					$template = Foxy::search_template(array(
					));
					if ( ! empty( $template ) ) {
						require $template;
					} else {
						foxy_detault_loop_content( $current_post_type, $style );
					}
				} else {
					do_action( "foxy_post_layout_{$style}_loop" );
				}
			}

			do_action( "foxy_post_layout_{$style}_end_loop" );
			do_action( 'foxy_post_layout_after_loop' );
			Foxy::ui()->tag(
				array(
					'name' => 'section',
					'close' => 'true',
				)
			);
			echo '<div class="clearfix"></div>';
		} else {
			foxy_no_content();
		}
		Foxy::ui()->tag( array( 'close' => true ) );
	}

	public static function default_loop_layout( $args = array() ) {
		$args = wp_parse_args( $args, array(
			'style' => 'list',
		) );

		$style =  $args['style'];

		Foxy::ui()->tag(array(
			'class' => sprintf( 'post-layout post-layout-%1$s style-%1$s', $style ),
		));
		if ( have_posts() ) {
			Foxy::ui()->tag(array(
				'name' => 'section',
				'class' => 'post-layout-wrap'
			));
			do_action( 'foxy_post_layout_before_loop' );
			do_action( "foxy_post_layout_{$style}_before_loop" );
			while ( have_posts() ) {
				the_post();
				if ( Foxy::hook_is_empty( "foxy_post_layout_{$style}_loop" ) ) {
					$current_post_type = get_post_type();
					$template = Foxy::search_template(array(
					));
					if ( ! empty( $template ) ) {
						require $template;
					} else {
						foxy_detault_loop_content( $current_post_type, $style );
					}
				} else {
					do_action( "foxy_post_layout_{$style}_loop" );
				}
			}

			do_action( "foxy_post_layout_{$style}_end_loop" );
			do_action( 'foxy_post_layout_after_loop' );
			Foxy::ui()->tag(array(
				'name' => 'section',
				'close' => 'true'
			));
		} else {
			foxy_no_content();
		}
		Foxy::ui()->tag(array(
			'close' => true,
		));
	}
}
