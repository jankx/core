<?php

add_action( 'jankx_post_layout_image', 'jankx_loop_post_thumbnail', 10, 2 );
function jankx_loop_post_thumbnail( $post_type = 'post', $style = 'list' ) {
	$size = apply_filters( 'jankx_loop_post_thumbnail_size', 'medium', $post_type, $style );
	Jankx::ui()->tag(
		array(
			'name'    => 'a',
			'context' => 'default-post-thumbnail',
		),
		array(
			'href'  => get_the_permalink(),
			'title' => get_the_title(),
		)
	);
	the_post_thumbnail( $size );
	echo '</a>';
}

add_action( 'jankx_post_layout_no_image', 'jankx_loop_no_image', 10, 2 );
function jankx_loop_no_image() {
	Jankx::ui()->tag(
		array(
			'name'    => 'a',
			'context' => 'no-post-thumbnail',
			'class' => 'no-image'
		),
		array(
			'href'  => get_the_permalink(),
			'title' => get_the_title(),
		)
	);
	echo '<span class="jankx-image-icon fx-picture"></span>';
	echo '</a>';
}

add_action( 'jankx_post_layout_content', 'jankx_loop_post_title', 10, 3 );
function jankx_loop_post_title( $post_type, $style, $article_tag = null ) {
	$tag = apply_filters( 'jankx_default_loop_title_tag', 'h3' );
	Jankx::ui()->tag(
		array(
			'name'  => $tag,
			'class' => 'item-title ' . $post_type . '-name',
		)
	);
	printf( '<a href="%1$s" title="%2$s">%2$s</a></%3$s>', get_the_permalink(), get_the_title(), esc_attr( $tag ) ); // WPCS: XSS ok.
}

add_action( 'jankx_post_layout_content', 'jankx_loop_post_excerpt', 10, 3 );
function jankx_loop_post_excerpt( $post_type, $style, $article_tag = null ) {
	printf( '<div class="item-desc %1$s-desc">%2$s</div>', esc_attr( $post_type ), get_the_excerpt() ); // WPCS: XSS ok.
}

add_action( 'jankx_before_post_content', 'jankx_single_post_title' );
function jankx_single_post_title( $post_type ) {
	if ( ! Jankx::has_title() ) {
		return;
	}
	Jankx::ui()->tag(
		array(
			'name'  => 'h1',
			'class' => 'item-title ' . $post_type . '-name',
		)
	);
	$show_link = apply_filters( 'jankx_show_title_with_link', false );
	if ( $show_link ) {
		printf( '<a href="%1$s" title="%2$s">%2$s</a>', get_the_permalink(), get_the_title() ); // WPCS: XSS ok.
	} else {
		the_title();
	}
	echo '</h1>';
}

add_action( 'jankx_post_layout_before_loop', 'jankx_post_layout_before_loop', 3, 2 );
function jankx_post_layout_before_loop( $args, $widget_args ) {
	if ( $args['carousel'] || ! in_array( $args['style'], Jankx_Post_Layout::column_styles(), true ) ) {
		return;
	}
	Jankx::ui()->tag( array( 'class' => 'row' ) );
}

add_action( 'jankx_post_layout_after_loop', 'jankx_post_layout_after_loop', 33, 2 );
function jankx_post_layout_after_loop( $args, $widget_args ) {
	if ( $args['carousel'] || ! in_array( $args['style'], Jankx_Post_Layout::column_styles(), true ) ) {
		return;
	}
	Jankx::ui()->tag( array( 'close' => true ) );
}

add_action( 'jankx_post_content', 'jankx_single_post_content' );
function jankx_single_post_content( $post_type ) {
	Jankx::ui()->tag(
		array(
			'class' => 'item-content single-content',
		)
	);
	the_content();
	echo '</div>';
}

/**
 * Filter post excerpt length
 */
add_filter( 'excerpt_length', 'jankx_post_excerpt_length' );
function jankx_post_excerpt_length( $length ) {
	if ( 'post' === get_post_type() ) {
		$length = 20;
	}
	return $length;
}

add_filter( 'excerpt_more', 'jankx_post_excerpt_more_text' );
function jankx_post_excerpt_more_text( $more_text ) {
	$more_text = '&hellip;';
	return $more_text;
}

add_action( 'jankx_post_layout_after_default_loop', 'jankx_loop_paginate' );
function jankx_loop_paginate() {
	bootstrap_pagination();
}


function bootstrap_pagination( $echo = true ) {
	global $wp_query;

	$big = 999999999; // need an unlikely integer

	$pages = paginate_links( array(
			'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format' => '?paged=%#%',
			'current' => max( 1, get_query_var('paged') ),
			'total' => $wp_query->max_num_pages,
			'type'  => 'array',
			'prev_next'   => true,
			'prev_text'    => __('« Prev'),
			'next_text'    => __('Next »'),
		)
	);

	if( is_array( $pages ) ) {
		$paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');

		$pagination = '<nav aria-label="Page navigation example"><ul class="pagination">';

		foreach ( $pages as $page ) {
			$page = str_replace('page-numbers', 'page-link', $page);
			$pagination .= "<li class=\"page-item\">$page</li>";
		}

		$pagination .= '</nav></ul>';

		if ( $echo ) {
			echo $pagination;
		} else {
			return $pagination;
		}
	}
}

add_action( 'jankx_before_main_content', 'jankx_alert_messages', 6 );
function jankx_alert_messages() {
	$alert_type = (bool)array_get($_GET, 'result', true) ?  'success' : 'danger';
	$messages = array_get($_GET, 'messages', array());
	if ( empty( $messages ) ) {
		return;
	}
	?>
	<div class="messages" style="margin: 20px 0;">
		<?php if(!empty($messages)): ?>
		<?php foreach($messages as $message): ?>
		<div class="alert alert-<?php echo $alert_type; ?> alert-dismissible">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $message; ?>
		</div>
		<?php endforeach; ?>
		<?php endif; ?>
	</div>
	<?php
}
