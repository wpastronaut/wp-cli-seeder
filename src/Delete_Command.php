<?php

namespace WPastronaut\WP_CLI\Seeder;

use WP_CLI\Utils;

class Delete_Command {
	public function all( $args, $assoc_args ) {
		\WP_CLI::confirm( "Are you sure you want to delete all seeded data from the site?", $assoc_args );

		$this->deletePosts( 'any' );
		$this->deleteTerms( get_taxonomies() );
	}

	/**
	 * Deletes seeded dummy posts.
	 *
	 * [--post_type=<type>]
	 * : Post type.
	 * ---
	 * default: any
	 * ---
	*/
	public function posts( $args, $assoc_args ) {
		if( $assoc_args['post_type'] === 'any' ) {
			$confirmation_message = 'Are you sure you want to delete all seeded posts from the site?';
		} else {
			$confirmation_message = sprintf( 'Are you sure you want to delete all seeded posts in the post type "%s" from the site?', $assoc_args['post_type'] );
		}

		\WP_CLI::confirm( $confirmation_message, $assoc_args );

		$this->deletePosts( 'any' );
	}

	private function deletePosts( $post_type ) {
		$deletable_post_ids = get_posts([
			'post_type' => $post_type,
			'post_status' => 'any',
			'posts_per_page' => '-1',
			'fields' => 'ids',
			'meta_key' => '_wpa_seeder_inserted_at',
		]);

		$progress = Utils\make_progress_bar( 'Deleting seeded posts', count( $deletable_post_ids ) );

		foreach( $deletable_post_ids as $post_id ) {
			wp_delete_post( $post_id );

			$progress->tick();
		}

		$progress->finish();
	}

	private function deleteTerms( $taxonomy ) {
		$deletable_terms = get_terms([
			'taxonomy' => $taxonomy,
			'meta_key' => '_wpa_seeder_inserted_at',
			'hide_empty' => false,
		]);

		$progress = Utils\make_progress_bar( 'Deleting seeded terms', count( $deletable_terms ) );

		foreach( $deletable_terms as $term ) {
			wp_delete_term( $term->term_id, $term->taxonomy );

			$progress->tick();
		}

		$progress->finish();
	}
}
