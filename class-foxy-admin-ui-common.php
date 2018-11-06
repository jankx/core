<?php

class Foxy_Admin_UI_Common {
	protected static $instance;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}



	public function admin_featured_column() {
	}

	public function choose_site_layout() {
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
}
