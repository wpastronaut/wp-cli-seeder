<?php

namespace WPastronaut\WP_CLI\Seeder;

class Helpers {
	public static function maybe_make_child() {
		// 50% chance.
		return ( wp_rand( 1, 2 ) === 1 );
	}

	public static function maybe_reset_depth() {
		// 10% chance.
		return ( wp_rand( 1, 10 ) === 1 );
	}
}
