<?php
/**
 * Foxy template helpers
 *
 * @package Foxy/Core
 * @subpackage UI
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @license GPL
 * @link https://wpclouds.com
 */

/**
 * Foxy index content
 */
function foxy_index_content() {
	Foxy::post_layout( 'list' );
}

/**
 * Foxy error 404 content
 *
 * @return void
 */
function foxy_error_404_content() {

}

/**
 * Foxy archive content
 *
 * @return void
 */
function foxy_archive_content() {
	Foxy::post_layout( 'card' );
}

/**
 * Foxy search content
 *
 * @return void
 */
function foxy_search_content() {
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			$current_post_type = Foxy::make_slug( get_post_type() );
			$template          = Foxy::search_template(
				array(
					$current_post_type . '/loop.php',
					'loop/' . $current_post_type . '.php',
					'loop/defaut.php',
				)
			);
			if ( ! empty( $template ) ) {
				require $template;
			} else {
				foxy_detault_loop_content( $current_post_type );
			}
		}
	} else {
		foxy_no_content();
	}
}

/**
 * Foxy page content
 *
 * @return void
 */
function foxy_page_content() {
	the_post();
	$template = Foxy::search_template(
		array(
			'content/page.php',
		)
	);
	if ( ! empty( $template ) ) :
		require $template;
	else :
		foxy_default_content( 'page' );
	endif;
}

/**
 * Foxy single content
 *
 * @return void
 */
function foxy_single_content() {
	the_post();
	$post_type = get_post_type();
	$post_type_file = Foxy::make_slug( $post_type );

	$content_hook      = "foxy_single_{$post_type}_content";
	if ( ! Foxy::hook_is_empty( $content_hook ) ) {
		do_action( $content_hook );
	} else {
		$template = Foxy::search_template(
			array(
				$post_type_file . '/content.php',
				'content/' . $post_type_file . '.php',
				'content/default.php',
			)
		);
		if ( ! empty( $template ) ) :
			require $template;
		else :
			foxy_default_content( $post_type );
		endif;
	}
	do_action( 'foxy_after_single_content', $post_type );
	do_action( "foxy_after_single_{$post_type}_content" );
}


/**
 * Function get no content template
 *
 * @return void
 */
function foxy_no_content() {
	do_action( 'foxy_ui_before_no_content' );
	$template = Foxy::search_template(array(
		'no-content.php',
	));
	if ( empty( $template ) ) {
		Foxy::ui()->tag(array(
			'name' => 'h2',
			'id' => 'no-content-heading',
			'class' => 'no-content',
		));
		echo esc_html__( 'OOOP!!', 'foxy' );
		Foxy::ui()->tag(array(
			'name' => 'h2',
			'context' => 'no-content-heading',
			'close' => true,
		));
		echo '<div class="no-content-desc">';
			printf( esc_html__( 'Don\'t have anything', 'foxy' ) );
		echo '</div>';
	} else {
		require $template;
	}
	do_action( 'foxy_ui_after_no_content' );
}

/**
 * Default content for post don't have template
 *
 * @param string $post_type Post type need to render content.
 * @return void
 */
function foxy_default_content( $post_type = null ) {
	if ( is_null( $post_type ) ) {
		$post_type = get_post_type();
	}
	Foxy::ui()->tag( array(
		'name'    => 'article',
		'context' => 'article-' . $post_type,
		'class'   => implode( ' ', get_post_class( 'item item-detail' ) ),
	) );
	do_action( 'foxy_before_post_content', $post_type );
	do_action( 'foxy_post_content', $post_type );
	do_action( 'foxy_after_post_content', $post_type );
	Foxy::ui()->tag(
		array(
			'name'    => 'article',
			'context' => 'article-' . $post_type,
			'close'   => true,
		)
	);
}

/**
 * Default loop content for post don't have template
 *
 * @param string $post_type Post type need to render content.
 * @return void
 */
function foxy_detault_loop_content( $post_type = null, $style = null ) {
	if ( is_null( $post_type ) ) {
		$post_type = get_post_type();
	}
	Foxy::ui()->tag(
		array(
			'name'    => 'article',
			'context' => 'article-' . $post_type,
			'class'   => implode( ' ', get_post_class( 'item loop-item' ) ),
		)
	);
	echo '<div class="item-inner">';
	do_action( "foxy_before_post_layout_{$post_type}_loop_content", $style );

	foxy_default_loop_image( $post_type, $style );

	echo '<div class="item-info">';
		do_action( 'foxy_post_layout_content', $post_type, $style );
		do_action( "foxy_post_layout_{$post_type}_addition_info", $style );
	echo '</div>'; // Close .item-info tag.

	do_action( "foxy_after_{$post_type}_loop_content", $style );
	echo '</div>'; // Close .item-inner tag.
	Foxy::ui()->tag(
		array(
			'name'    => 'article',
			'context' => 'article-' . $post_type,
			'close'   => true,
		)
	);
}

function foxy_default_loop_image( $post_type = null, $style = null ) {
	if ( has_post_thumbnail() ) {
		Foxy::ui()->tag(
			array(
				'name'    => 'figure',
				'class'   => 'item-thumb',
				'context' => 'post-layout-figure',
			)
		);

		do_action( 'foxy_post_layout_image', $post_type, $style );
		do_action( "foxy_post_layout_{$post_type}_figure", $style );

		Foxy::ui()->tag(
			array(
				'name'    => 'figure',
				'context' => 'post-layout-figure',
				'close'   => true,
			)
		);
	} else {
		$show_no_image = apply_filters( "foxy_show_{$post_type}_no_image", true );
		if ( $show_no_image ) {
			Foxy::ui()->tag(
				array(
					'name'    => 'figure',
					'class'   => 'item-thumb no-image',
					'context' => 'post-layout-figure',
				)
			);

			do_action( 'foxy_post_layout_no_image', $post_type, $style );
			do_action( "foxy_post_layout_{$post_type}_figure", $style );

			Foxy::ui()->tag(
				array(
					'name'    => 'figure',
					'context' => 'post-layout-figure',
					'close'   => true,
				)
			);
		}
	}
}


function foxy_get_search_form( $form ) {
	return $form;
}
