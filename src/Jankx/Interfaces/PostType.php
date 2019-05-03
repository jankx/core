<?php

namespace Jankx\Core;

interface Jankx_Post_Type_Interface {
	public function get();

	public function load();

	public function validate();

	public function save();
}
