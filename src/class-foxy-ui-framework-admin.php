<?php
class Foxy_UI_Framework_Admin extends Foxy_UI_Framework_Base {
	/**
	 * Bootsttrap UI Framework constructor
	 */
	public function __construct() {
		$this->init_class_names();
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function get_name() {
		return 'admin';
	}

	public function init_class_names() {
		$this->mobile_class_prefix       = 'fxc-mob-';
		$this->small_tablet_class_prefix = 'fxc-stab-';
		$this->tablet_class_prefix       = 'fxc-tab-';
		$this->desktop_class_prefix      = 'fxc-desk-';
		$this->extra_class_prefix        = 'fxc-xtra-';
	}

	public function enqueue_scripts() {
		Foxy::asset()->script("$(document).ready(function(){
				var firstel = $('.foxy-tabs .foxy-tab:first a').addClass('active').attr('href');
				$(firstel).addClass('active');
			});
			$('.foxy-tabs .foxy-tab a').click(function(e){
				e.preventDefault();
				$('.foxy-tabs .foxy-tab .active').removeClass('active');
				$('.foxy-fields-wrap.active').removeClass('active');
			    var target = $(this).addClass('active').attr( 'href' );
			    $(target).addClass('active');
			});");
	}
}
