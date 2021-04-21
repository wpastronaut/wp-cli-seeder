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

	public static function get_images( $count ) {
		$pdo = new \PDO("sqlite:".dirname(__DIR__)."/data.db");

		$query = $pdo->prepare('SELECT url, orientation, author_name, author_url FROM images ORDER BY RANDOM() LIMIT :count');

		$query->execute([
			'count' => 5,
		]);

		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}

	public static function download_file_to_media_library( $url, $desc = null ) {
		if( empty( $url ) ) {
			return new \WP_Error( 'error', 'File url is empty' );
		}

		$file_array = [];

		preg_match( '/[^\?]+\.(jpe?g|jpe)\b/i', $url, $matches );

		$file_array = [
			'name' => basename( $matches[0] ),
			'tmp_name' => download_url( $url ),
		];

		if ( is_wp_error( $file_array['tmp_name'] ) ) {
			return new \WP_Error( 'error', 'Error while trying to store file temporarily' );
		}

		$id = media_handle_sideload( $file_array, 0, $desc );

		if ( is_wp_error( $id ) ) {
			unlink( $file_array['tmp_name'] );

			return new \WP_Error( 'error', 'Couldn\'t store file permanently' );
		}

		if ( empty( $id ) ) {
			return new \WP_Error( 'error', "File upload ID is empty" );
		}

		return $id;
	}

	public static function faker( $locale = false ) {
		if( $locale ) {
			return \Faker\Factory::create( $locale );
		}

		return \Faker\Factory::create();
	}
}
