<?php
class Foxy_Option_Framework_Redux extends Foxy_Option_Framework_Base {
	public function load_options( $id, $refresh = false ) {
		$this->loaded_options[ $id ] = $GLOBALS[ $id ];
	}

	public function get_option( $option_name, $default_value = false ) {
		if ( ! isset( $this->loaded_options[ $this->id ] ) ) {
			$this->load_options( $this->id );
		}
		return array_get( $this->loaded_options[ $this->id ], $option_name, $default_value );
	}

	public function set_args( $id, $args ) {
		Redux::setArgs( $id, $args );
	}

	public function add_sections( $sections ) {
		if ( isset( $sections[0] ) && is_array( $sections[0] ) ) {
			foreach ( $sections as $section ) {
				Redux::setSection( $this->id, $section );
			}
		} else {
			Redux::setSection( $this->id, $section );
		}
	}
}
