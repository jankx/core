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
	echo 'index';
}

/**
 * Foxy error 404 content
 *
 * @return void
 */
function foxy_error_404_content() {
	echo '404';
}

/**
 * Foxy archive content
 *
 * @return void
 */
function foxy_archive_content() {
	echo 'archive';
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
	$current_post_type = Foxy::make_slug( get_post_type() );
	$content_hook      = "foxy_single_{$current_post_type}_content";
	if ( ! Foxy::hook_is_empty( $content_hook ) ) {
		do_action( $content_hook );
	} else {
		$template = Foxy::search_template(
			array(
				$current_post_type . '/content.php',
				'content/' . $current_post_type . '.php',
				'content/default.php',
			)
		);
		if ( ! empty( $template ) ) :
			require $template;
		else :
			foxy_default_content( $current_post_type );
		endif;
	}
}


/**
 * Function get no content template
 *
 * @return void
 */
function foxy_no_content() {
}

/**
 * Default content for post don't have template
 *
 * @param string $post_type Post type need to render content.
 * @return void
 */
function foxy_default_content( $post_type = null ) {
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<h1 class="post-title"><?php the_title(); ?></h1>
		<div class="content"><?php the_content(); ?></div>
		<?php
			Foxy::post_meta( $post_type );
		?>
	</article>
	<?php
}

/**
 * Default loop content for post don't have template
 *
 * @param string $post_type Post type need to render content.
 * @return void
 */
function foxy_detault_loop_content( $post_type = null ) {
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<h3 class="post-title">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
		</h3>
		<div class="content"><?php the_excerpt(); ?></div>
		<?php
			Foxy::post_meta( $post_type );
		?>
	</article>
	<?php
}
