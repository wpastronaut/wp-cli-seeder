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
	 * Delete seeded terms.
	 *
	 * ## OPTIONS
	 *
	 * [--taxonomy=<taxonomy>]
	 * : Taxonomy.
	 * ---
	 * default: any
	 * ---
	*/
	public function terms( $args, $assoc_args ) {
		if( $assoc_args['taxonomy'] === 'any' ) {
			\WP_CLI::confirm( "Are you sure you want to delete all seeded terms from the site?", $assoc_args );

			$this->deletePosts( get_taxonomies() );
		} else {
			\WP_CLI::confirm( sprintf( 'Are you sure you want to delete all seeded terms in the taxonomy "%s" from the site?', $assoc_args['taxonomy'] ), $assoc_args );

			$this->deletePosts( $assoc_args['taxonomy'] );
		}
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
