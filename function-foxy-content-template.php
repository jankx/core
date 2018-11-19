<?php

add_action( 'foxy_post_layout_image', 'foxy_loop_post_thumbnail', 10, 2 );
function foxy_loop_post_thumbnail( $post_type, $style ) {
	$size = apply_filters( 'foxy_loop_post_thumbnail_size', 'medium', $post_type, $style );
	Foxy::ui()->tag(
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

add_action( 'foxy_post_layout_no_image', 'foxy_loop_no_image', 10, 2 );
function foxy_loop_no_image() {
	Foxy::ui()->tag(
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
	echo '<span class="foxy-image-icon fx-picture"></span>';
	echo '</a>';
}

add_action( 'foxy_post_layout_content', 'foxy_loop_post_title', 10, 2 );
function foxy_loop_post_title( $post_type, $style ) {
	$tag = apply_filters( 'foxy_default_loop_title_tag', 'h3' );
	Foxy::ui()->tag(
		array(
			'name'  => $tag,
			'class' => 'item-title ' . $post_type . '-name',
		)
	);
	printf( '<a href="%1$s" title="%2$s">%2$s</a></%3$s>', get_the_permalink(), get_the_title(), esc_attr( $tag ) ); // WPCS: XSS ok.
}

add_action( 'foxy_post_layout_content', 'foxy_loop_post_excerpt', 10, 2 );
function foxy_loop_post_excerpt( $post_type, $style ) {
	printf( '<div class="item-desc %1$s-desc">%2$s</div>', esc_attr( $post_type ), get_the_excerpt() ); // WPCS: XSS ok.
}

add_action( 'foxy_before_post_content', 'foxy_single_post_title' );
function foxy_single_post_title( $post_type ) {
	if ( ! Foxy::has_title() ) {
		return;
	}
	Foxy::ui()->tag(
		array(
			'name'  => 'h1',
			'class' => 'item-title ' . $post_type . '-name',
		)
	);
	$show_link = apply_filters( 'foxy_show_title_with_link', false );
	if ( $show_link ) {
		printf( '<a href="%1$s" title="%2$s">%2$s</a>', get_the_permalink(), get_the_title() ); // WPCS: XSS ok.
	} else {
		the_title();
	}
	echo '</h1>';
}

add_action( 'foxy_post_layout_before_loop', 'foxy_post_layout_before_loop', 3, 2 );
function foxy_post_layout_before_loop( $args, $widget_args ) {
	if ( $args['carousel'] || ! in_array( $args['style'], Foxy_Post_Layout::column_styles(), true ) ) {
		return;
	}
	Foxy::ui()->tag( array( 'class' => 'row' ) );
}

add_action( 'foxy_post_layout_after_loop', 'foxy_post_layout_after_loop', 33, 2 );
function foxy_post_layout_after_loop( $args, $widget_args ) {
	if ( $args['carousel'] || ! in_array( $args['style'], Foxy_Post_Layout::column_styles(), true ) ) {
		return;
	}
	Foxy::ui()->tag( array( 'close' => true ) );
}

add_action( 'foxy_post_content', 'foxy_single_post_content' );
function foxy_single_post_content( $post_type ) {
	Foxy::ui()->tag(
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
add_filter( 'excerpt_length', 'foxy_post_excerpt_length' );
function foxy_post_excerpt_length( $length ) {
	if ( 'post' === get_post_type() ) {
		$length = 20;
	}
	return $length;
}

add_filter( 'excerpt_more', 'foxy_post_excerpt_more_text' );
function foxy_post_excerpt_more_text( $more_text ) {
	$more_text = '&hellip;';
	return $more_text;
}

add_action( 'foxy_post_layout_after_default_loop', 'foxy_loop_paginate' );
function foxy_loop_paginate() {
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

add_action( 'foxy_before_main_content', 'foxy_alert_messages', 6 );
function foxy_alert_messages() {
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
