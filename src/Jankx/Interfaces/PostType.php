<?php

namespace Jankx\Core;

interface Foxy_Post_Type_Interface {
	public function get();

	public function load();

	public function validate();

	public function save();
}
