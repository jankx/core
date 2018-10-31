<?php

add_action( 'foxy_post_layout_image', 'foxy_loop_post_thumbnail', 10, 2 );
function foxy_loop_post_thumbnail($post_type, $style) {
	$size = apply_filters( 'foxy_loop_post_thumbnail_size', 'medium', $post_type, $style );
	the_post_thumbnail( $size );
}

add_action( 'foxy_post_layout_content', 'foxy_loop_post_title', 10, 2 );
function foxy_loop_post_title( $post_type, $style ) {
	$tag = apply_filters( 'foxy_default_loop_title_tag', 'h3' );
	Foxy::ui()->tag(array(
		'name' => $tag,
		'class' => 'item-title ' . $post_type . '-name',
	));
	printf( '<a href="%1$s" title="%2$s">%2$s</a></%3$s>', get_the_permalink(), get_the_title(), $tag);
}

add_action( 'foxy_post_layout_content', 'foxy_loop_post_excerpt', 10, 2 );
function foxy_loop_post_excerpt( $post_type, $style ) {
	printf( '<div class="item-desc %1$s-desc">%2$s</div>', $post_type, get_the_excerpt() );
}

add_action( 'foxy_before_post_content', 'foxy_single_post_title' );
function foxy_single_post_title( $post_type ) {
	Foxy::ui()->tag(array(
		'name' => 'h1',
		'class' => 'item-title ' . $post_type . '-name',
	));
	$show_link = apply_filters( 'foxy_show_title_with_link', false );
	if ($show_link) {
		printf( '<a href="%1$s" title="%2$s">%2$s</a></h1>', get_the_permalink(), get_the_title());
	} else {
		the_title();
		echo '</h1>';
	}
}

add_action( 'foxy_post_content', 'foxy_single_post_content' );
function foxy_single_post_content( $post_type ) {
	Foxy::ui()->tag(array(
		'class' => 'item-content single-content',
	));
	the_content();
	Foxy::ui()->tag(array(
		'close' => true,
	));
}
