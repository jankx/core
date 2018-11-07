<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Cheatin huh?' );
}

class Foxy_Admin_UI_Common {
	protected static $instance;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function admin_featured_column() {
	}

	public static function choose_site_layout() {
		$supported_layouts = Foxy::get_supported_layouts();
		$selected_layout   = Foxy::get_layout();
		?>
		<select class="widefat" name="foxy_site_layout" id="site_layout">
			<option value=""><?php _e( 'Default' ); ?></option>
		<?php foreach( $supported_layouts as $supported_layout => $layout_name ): ?>
			<option value="<?php echo $supported_layout; ?>"<?php selected( $supported_layout, $selected_layout ); ?>><?php echo $layout_name; ?></option>
		<?php endforeach; ?>
		</select>
		<div class="foxy-desc"><?php esc_html_e('Lorem ipsum dolor sit, amet consectetur adipisicing elit. Debitis alias cupiditate omnis iure! Optio delectus tempore voluptas, perspiciatis blanditiis eos?', 'foxy'); ?></div>
		<?php
	}

	public function widget_post_layout( $widget, $instance ) {
		$layouts = Foxy_Post_Layout::supported_post_layouts();
		if ( ! isset( $instance['style'] ) ) {
			$instance['style'] = '';
		}
		?>
		<p>
			<label for="<?php echo $widget->get_field_id( 'style' ); ?>"><?php _e( 'Layout type', 'foxy' ); ?></label>
			<select class="widefat" name="<?php echo $widget->get_field_name( 'style' ); ?>" id="<?php echo $widget->get_field_id( 'style' ); ?>">
				<option value=""><?php esc_html_e( 'Default', 'foxy' ); ?></option>
			<?php foreach ( $layouts as $layout => $name ) : ?>
				<option value="<?php echo $layout ?>"<?php selected( $layout, $instance['style'] ) ?>><?php echo $name; ?></option>
			<?php endforeach; ?>
			</select>
		</p>
		<?php
	}

	public function widget_post_taxonomy_layout( $instance ) {
	}

	public function edit_post_choose_layout() {
	}

	public function edit_taxonomy_choose_layout() {
	}

	public function post_type_archive_choose_layout() {
	}

	public static function choose_hide_post_title( $post ) {
		$hide_title = Foxy::has_title( $post->ID );
		?>
		<div class="misc-pub-section misc-pub-hide-title">
			<label for="foxy-hide-title">
				<input type="checkbox" id="foxy-hide-title" name="foxy_hide_post_title" value="yes"<?php checked( false, $hide_title ); ?>>
				<?php esc_html_e( 'Foxy hide the title', 'foxy' ); ?>
			</label>
		</div>
		<?php
	}
}
