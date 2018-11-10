<?php
class Foxy_UI_Framework_Admin extends Foxy_UI_Framework_Base {
	/**
	 * Bootsttrap UI Framework constructor
	 */
	public function __construct() {
		$this->init_class_names();
	}

	public function get_name() {
		return 'admin';
	}

	public function init_class_names() {
		$this->mobile_class_prefix = 'fxc-';
		$this->small_tablet_class_prefix = 'fxc-stab-';
		$this->tablet_class_prefix = 'fxc-tab-';
		$this->desktop_class_prefix = 'fxc-desk-';
		$this->extra_class_prefix = 'fxc-xtra-';
	}
}
