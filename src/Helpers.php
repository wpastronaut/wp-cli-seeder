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

	public static function get_inserted_post_ids( $post_type ) {
		return get_posts([
			'post_type' => $post_type,
			'post_status' => 'any',
			'posts_per_page' => '-1',
			'fields' => 'ids',
			'meta_key' => '_wpa_seeder_inserted_at',
		]);
	}
}
