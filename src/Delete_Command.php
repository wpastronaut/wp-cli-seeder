<?php

namespace WPastronaut\WP_CLI\Seeder;

use WP_CLI\Utils;
use WPastronaut\WP_CLI\Seeder\Helpers;

class Delete_Command {
	/**
	 * Deletes all seeded content.
	*/
	public function all( $args, $assoc_args ) {
		\WP_CLI::confirm( "Are you sure you want to delete all seeded data from the site?", $assoc_args );

		$this->deletePosts( 'any' );
		$this->deleteTerms( get_taxonomies() );
	}

	/**
	 * Deletes seeded dummy posts.
	 *
	 * ## OPTIONS
	 *
	 * [--post_type=<type>]
	 * : Post type.
	 * ---
	 * default: any
	 * ---
	 *
	 * [--lang=<lang>]
	 * : Language of the posts you want to delete.
	 * ---
	 * default:
	 * ---
	*/
	public function posts( $args, $assoc_args ) {
		if( $assoc_args['post_type'] === 'any' ) {
			$confirmation_message = 'Are you sure you want to delete all seeded posts from the site?';
		} else {
			$confirmation_message = sprintf( 'Are you sure you want to delete all seeded posts in the post type "%s" from the site?', $assoc_args['post_type'] );
		}

		\WP_CLI::confirm( $confirmation_message, $assoc_args );

		$this->deletePosts( 'any', $assoc_args['lang'] ?? '' );
	}

	/**
	 * Deletes seeded dummy terms.
	 *
	 * ## OPTIONS
	 *
	 * [--taxonomy=<taxonomy>]
	 * : Taxonomy.
	 * ---
	 * default: any
	 * ---
	 *
	 * [--lang=<lang>]
	 * : Language of the terms you want to delete.
	 * ---
	 * default:
	 * ---
	*/
	public function terms( $args, $assoc_args ) {
		$lang = $assoc_args['lang'] ?? '';

		if( $assoc_args['taxonomy'] === 'any' ) {
			\WP_CLI::confirm( "Are you sure you want to delete all seeded terms from the site?", $assoc_args );

			$this->deleteTerms( get_taxonomies(), $lang );
		} else {
			\WP_CLI::confirm( sprintf( 'Are you sure you want to delete all seeded terms in the taxonomy "%s" from the site?', $assoc_args['taxonomy'] ), $assoc_args );

			$this->deleteTerms( $assoc_args['taxonomy'], $lang );
		}
	}

	/**
	 * Deletes seeded dummy images.
	 *
	 * ## OPTIONS
	 *
	 * [--lang=<lang>]
	 * : Language of the terms you want to delete.
	 * ---
	 * default:
	 * ---
	*/
	public function images( $args, $assoc_args ) {
		\WP_CLI::confirm( 'Are you sure you want to delete all seeded images from the site?', $assoc_args );

		$this->deleteMedia( 'image/jpeg', $assoc_args['lang'] ?? '' );
	}

	private function deletePosts( $post_type, $lang = '' ) {
		$post_ids = Helpers::get_inserted_posts( $post_type, 'ids', $lang );

		$progress = Utils\make_progress_bar( 'Deleting seeded posts', count( $post_ids ) );

		foreach( $post_ids as $post_id ) {
			wp_delete_post( $post_id );

			$progress->tick();
		}

		$progress->finish();
	}

	private function deleteTerms( $taxonomy, $lang = '' ) {
		$terms = Helpers::get_inserted_terms( $taxonomy, 'all', $lang );

		$progress = Utils\make_progress_bar( 'Deleting seeded terms', count( $terms ) );

		foreach( $terms as $term ) {
			$result = wp_delete_term( $term->term_id, $term->taxonomy );

			$progress->tick();
		}

		$progress->finish();
	}

	private function deleteMedia( $mime = false, $lang = '' ) {
		$post_ids = Helpers::get_inserted_media( $mime, 'ids', $lang );

		$name_map = [
			'image/jpeg' => 'images',
		];

		$name = $name_map[$mime] ?? 'media';

		$progress = Utils\make_progress_bar( sprintf( 'Deleting seeded %s', $name ), count( $post_ids ) );

		foreach( $post_ids as $post_id ) {
			wp_delete_post( $post_id );

			$progress->tick();
		}

		$progress->finish();
	}
}
