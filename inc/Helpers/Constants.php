<?php

namespace RT\Clplace\Helpers;

class Constants {

	const SERVLISTING_VERSION = '1.0.0';

	public static function get_version() {
		return WP_DEBUG ? time() : self::SERVLISTING_VERSION;
	}
}

