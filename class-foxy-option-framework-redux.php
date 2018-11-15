<?php
class Foxy_Option_Framework_Redux extends Foxy_Option_Framework_Base {
	public function load_options( $id, $refresh = flase ) {
		$this->loaded_options[ $id ] = $GLOBALS[ $this->id() ];
	}

	public function get_option( $option_name, $default_value = false ) {
		return $default_value;
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
