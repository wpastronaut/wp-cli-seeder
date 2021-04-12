<?php

namespace WPastronaut\WP_CLI\Seeder;

use WP_CLI;
use WP_CLI\Utils;

class Attach_Command {
	/**
	 * Attach terms to posts.
	 *
	 * ## OPTIONS
	 *
	 * [--post_type=<type>]
	 * : Post type.
	 * ---
	 * default: post
	 * ---
	 *
	 * [--taxonomy=<taxonomy>]
	 * : Taxonomy.
	 * ---
	 * default: category
	 * ---
	 *
	 * @alias terms-to-posts
	*/
	public function terms_to_posts( $args, $assoc_args ) {
		if( ! post_type_exists( $assoc_args['post_type'] ) ) {
			WP_CLI::error( sprintf( 'Post type "%s" doesn\'t exist', $assoc_args['post_type'] ) );
		}

		if( ! taxonomy_exists( $assoc_args['taxonomy'] ) ) {
			WP_CLI::error( sprintf( 'Taxonomy "%s" doesn\'t exist', $assoc_args['taxonomy'] ) );
		}

		$post_ids = get_posts([
			'post_type' => $assoc_args['post_type'],
			'post_status' => 'any',
			'posts_per_page' => '-1',
			'fields' => 'ids',
			'meta_key' => '_wpa_seeder_inserted_at',
		]);

		$term_ids = get_terms([
			'taxonomy' => $assoc_args['taxonomy'],
			'hide_empty' => false,
			'fields' => 'ids',
			'meta_key' => '_wpa_seeder_inserted_at',
		]);

		$progress = Utils\make_progress_bar( sprintf( 'Attaching terms from the taxonomy "%s" for the post type "%s"', $assoc_args['taxonomy'], $assoc_args['post_type'] ), $assoc_args['count'] );

		foreach( $post_ids as $post_id ) {
			$random_term_ids = array_values( array_intersect_key( $term_ids, array_flip( array_rand( $term_ids, mt_rand( 0, 4 ) ) ) ) );
			$terms = wp_set_object_terms( $post_id, $random_term_ids, $assoc_args['taxonomy'] );

			$progress->tick();
		}

		$progress->finish();
	}
}
