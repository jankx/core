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
			$current_post_type = foxy_make_slug( get_post_type() );
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
	$post_type_file = foxy_make_slug( $post_type );

	$content_hook      = "foxy_single_{$post_type}_content";
	if ( ! foxy_check_empty_hook( $content_hook ) ) {
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
function foxy_detault_loop_content( $post_type = null, $article_tag = array() ) {
	if ( is_null( $post_type ) ) {
		$post_type = get_post_type();
	}
	$article_tag = wp_parse_args(
		$article_tag,
		array(
			'name'    => 'article',
			'context' => 'article-' . $post_type,
			'class'   => implode( ' ', get_post_class( 'item loop-item' ) ),
		)
	);

	Foxy::ui()->tag( $article_tag );
	echo '<div class="item-inner">';
	do_action( "foxy_before_post_layout_{$post_type}_loop_content", $article_tag );

	foxy_default_loop_image( $post_type, $article_tag );

	echo '<div class="item-info">';

	if ( foxy_check_empty_hook( "foxy_{$post_type}_layout_content" ) ) {
		do_action( 'foxy_post_layout_content', $post_type, $article_tag );
	} else {
		do_action( "foxy_{$post_type}_layout_content", $post_type, $article_tag );
	}
	do_action( "foxy_post_layout_{$post_type}_addition_info", $article_tag );

	echo '</div>'; // Close .item-info tag.

	do_action( "foxy_after_{$post_type}_loop_content", $article_tag );
	echo '</div>'; // Close .item-inner tag.
	Foxy::ui()->tag(
		array(
			'name'    => 'article',
			'context' => 'article-' . $post_type,
			'close'   => true,
		)
	);
}

function foxy_default_loop_image( $post_type = null, $article_tag = null ) {
	$no_image = '_no';
	if ( has_post_thumbnail() ) {
		$no_image = '';
	}
	if ( empty( $no_image ) || apply_filters( "foxy_show_{$post_type}_no_image", true ) ) {
		do_action( 'foxy_post_layout_before_image', $post_type, $article_tag );
		Foxy::ui()->tag(
			array(
				'name'    => 'figure',
				'class'   => 'item-thumb',
				'context' => 'post-layout-figure',
			)
		);
		echo '<div class="item-thumb-inner">';

		do_action( "foxy_post_layout{$no_image}_image", $post_type, $article_tag );
		do_action( "foxy_post_layout_{$post_type}_figure", $article_tag );

		echo '</div><!-- End .item-thumb-inner -->';

		Foxy::ui()->tag(
			array(
				'name'    => 'figure',
				'context' => 'post-layout-figure',
				'close'   => true,
			)
		);
		do_action( 'foxy_post_layout_after_image', $post_type, $article_tag );
	}
}


function foxy_get_search_form( $form ) {
	return $form;
}
