<?php
class Foxy_Meta_Framework_WordPress extends Foxy_Meta_Framework_Base {
	protected function tab_id( $tab, $prefix = null ) {
		$id = '';
		if ( ! empty( $prefix ) ) {
			$id .= $prefix;
		}
		return $id .= $tab['id'];
	}

	protected function tab_title( $tab ) {
		$title = '';
		if ( ! empty( $tab['icon'] ) ) {
			$title .= sprintf( '<span class="foxy-tab-icon %s"></span> ', $tab['icon'] );
		} elseif( ! empty( $tab['image'] ) ) {
			$title .= sprintf(
				'<span class="foxy-image foxy-tab-image"><img src="%s" alt="%s"/></span> ',
				$tab['image'],
				$tab['title']
			);
		}
		$title .= sprintf( '<span class="foxy-tab-text">%s</span>', $tab['title'] );
		return $title;
	}

	public function tab_list( $tabs ) {
		?>
		<ul class="foxy-meta-tabs">
			<?php foreach( $tabs as $tab):
			$tab = wp_parse_args( $tab, array(
				'id' => '',
				'name' => '',
				'title' => '',
				'icon' => '',
				'image' => ''
			))
			?>
			<li class="tab-item">
				<a href="<?php echo $this->tab_id( $tab, '#foxy-tab-' ); ?>"><?php echo $this->tab_title( $tab ); ?></a>
			</li>
			<?php endforeach; ?>
			</ul>
		<?php
	}

	public function parse_class_name( $field ) {
		$classe_names = array( ' ' . $field['type'] );
		if (!empty($field['title'])) {
			$classe_names[] = 'has-title';
		}
		if (!empty($field['desc'])) {
			$classe_names[] = 'has-desc';
		}
		if (!empty($field['subtitle'])) {
			$classe_names[] = 'has-subtitle';
		}
		return implode(' ', $classe_names);
	}

	public function create_label( $field ) {
		$tag = wp_parse_args( $field['label_width'], array(
			'echo' => false,
		));
		$label = Foxy::ui()->tag($tag);
			$label .= sprintf(
				'<label for="foxy-field-%s" class="foxy-field-label">%s</label>',
				$field['id'],
				$field['title']
			);

			if ( ! empty( $field['subtitle'] ) ) {
				$label .= sprintf(
					'<div class="foxy-field-subtitle">%s</div>',
					$field['subtitle']
				);
			}
		$label .= '</div>';

		return $label;
	}

	public function create_desc( $field ) {
		if ( ! empty( $field['desc'] ) ) :
		?>
		<div class="field-desc">
			<?php echo esc_html( $field['desc'] ); ?>
		</div>
		<?php
		endif;
	}

	public function tab_content_fields( $object, $fields ) {
		foreach ( $fields as $tab => $field_elements ):
		?>
		<div id="foxy-tab-<?php echo $tab; ?>" class="foxy-fields foxy-tab-content">
			<?php foreach( $field_elements as $field_element ):
			$field_callback = apply_filters( "foxy_meta_{$field_element['type']}_callback", array( $this, $field_element['type'] ) );
			if ( ! isset( $field_element['type'] ) || ! isset( $field_element['id'] ) || ! is_callable( $field_callback ) ) {
				continue;
			}

			$field_element = wp_parse_args( $field_element, array(
				'title' => '',
				'subtitle' => '',
				'desc' => '',
				'label_width' => array(
					'mobile_columns' => 12,
					'tablet_columns' => 3,
					'desktop_columns' => 3
				),
				'default' => '',
				'value' => get_post_meta( $object->ID, $field_element['id'], true ) )
			);
			?>
			<div class="foxy-field foxy-meta-field<?php echo esc_attr( $this->parse_class_name( $field_element ) ); ?>">
				<?php echo $this->create_label( $field_element ); ?>
				<div class="field-content">
					<?php call_user_func( $field_callback, $field_element ); ?>
				</div>
				<?php echo $this->create_desc( $field_element ); ?>
			</div>
			<?php endforeach; ?>
		</div>
		<?php
		endforeach;
	}

	public function factory( $post, $args ) {
		list( $tabs, $fields ) = $this->group_all_fields( $args['args'] );
		$has_tabs = false;
		$tabs = apply_filters( "foxy_metabox_{$args['id']}_tabs", $tabs );
		do_action( 'foxy_before_meta_factory' );
		do_action( "foxy_before_meta_{$args['id']}_factory" );
		$wrap_classes = array( 'foxy-meta', 'foxy-fields-wrap', 'foxy-meta-wordpress-wrap' );
		if ( isset( $tabs['fields'] ) && count( $tabs['fields'] ) ) {
			$has_tabs = true;
			$wrap_classes[] = 'has-tab';
		}

		printf('<div class="%s">', implode(' ', $wrap_classes ) );
		if ( $has_tabs ) {
			$this->tab_list( $tabs['fields'] );
		}
		?>
		<div class="fields-wrap">
			<?php echo $this->tab_content_fields( $post, $fields ); ?>
		</div>
		</div>
		<?php
		do_action( "foxy_after_meta_{$args['id']}_factory" );
		do_action( 'foxy_after_meta_factory' );
	}


	public function text( $field ) {
		?>
		<input type="text" class="widefat" name="<?php echo $field['id']; ?>" value="<?php echo $field['value']; ?>">
		<?php
	}

	public function number( $field ) {
		?>
		<input type="number" class="widefat" name="<?php echo $field['id']; ?>" value="<?php echo $field['value']; ?>">
		<?php
	}
}
