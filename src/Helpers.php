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

	public static function get_inserted_posts( $post_type, $fields = 'all' ) {
		return get_posts([
			'post_type' => $post_type,
			'post_status' => 'any',
			'posts_per_page' => '-1',
			'fields' => $fields,
			'meta_key' => '_wpa_seeder_inserted_at',
		]);
	}

	public static function get_inserted_terms( $taxonomy, $fields = 'all' ) {
		return get_terms([
			'taxonomy' => $taxonomy,
			'hide_empty' => false,
			'fields' => $fields,
			'meta_key' => '_wpa_seeder_inserted_at',
		]);
	}
}
