<?php

define( 'FOXY_FRAMEWORK_FILE', __FILE__ );

require_once dirname( FOXY_FRAMEWORK_FILE ) . '/class-foxy-setup.php';

Foxy_Setup::initialize();

function foxy() {
	return $GLOBALS['foxy'];
}
