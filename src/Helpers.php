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

	public static function get_inserted_posts( $post_type, $fields = 'all', $lang = '' ) {
		$args = [
			'post_type' => $post_type,
			'post_status' => 'any',
			'posts_per_page' => '-1',
			'fields' => $fields,
			'meta_key' => '_wpa_seeder_inserted_at',
		];

		if( $lang && defined( 'POLYLANG_VERSION' ) ) {
			$args['lang'] = $lang;
		}

		return get_posts( $args );
	}

	public static function get_inserted_terms( $taxonomy, $fields = 'all', $lang = '' ) {
		$args = [
			'taxonomy' => $taxonomy,
			'hide_empty' => false,
			'fields' => $fields,
			'meta_key' => '_wpa_seeder_inserted_at',
		];

		if( $lang && defined( 'POLYLANG_VERSION' ) ) {
			$args['lang'] = $lang;
		}

		return get_terms( $args );
	}

	public static function faker( $locale = false ) {
		if( $locale ) {
			return \Faker\Factory::create( $locale );
		}

		return \Faker\Factory::create();
	}
}
