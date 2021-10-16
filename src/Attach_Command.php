<?php

namespace WPastronaut\WP_CLI\Seeder;

use WP_CLI;
use WP_CLI\Utils;

class Attach_Command {
	/**
	 * Attach seeded terms to seeded posts.
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
	 * [--lang=<lang>]
	 * : Language of the terms and posts you want to attach.
	 * ---
	 * default:
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

		$post_ids = Helpers::get_inserted_posts( $assoc_args['post_type'], 'ids', $assoc_args['lang'] ?? '' );
		$term_ids = Helpers::get_inserted_terms( $assoc_args['taxonomy'], 'ids', $assoc_args['lang'] ?? '' );

		$progress = Utils\make_progress_bar( sprintf( 'Attaching terms from the taxonomy "%s" for the post type "%s"', $assoc_args['taxonomy'], $assoc_args['post_type'] ), $assoc_args['count'] );

		foreach( $post_ids as $post_id ) {
			$number_of_terms = mt_rand( 0, 4 );
			if ( $number_of_terms > 1 ) {
				$random_terms = array_rand( $term_ids, $number_of_terms );
			} elseif ( 1 === $number_of_terms ) {
				$random_terms = array( array_rand( $term_ids, 1 ) );
			} else {
				$random_terms = array();
			}

			$random_term_ids = array_values( array_intersect_key( $term_ids, array_flip( $random_terms ) ) );
			$terms = wp_set_object_terms( $post_id, $random_term_ids, $assoc_args['taxonomy'] );

			$progress->tick();
		}

		$progress->finish();
	}

	/**
	 * Attach seeded images to seeded posts as featured images.
	 *
	 * ## OPTIONS
	 *
	 * [--post_type=<type>]
	 * : Post type.
	 * ---
	 * default: post
	 * ---
	 *
	 * [--lang=<lang>]
	 * : Language of the images and posts you want to attach.
	 * ---
	 * default:
	 * ---
	 *
	 * @alias images-to-posts
	*/
	public function images_to_posts( $args, $assoc_args ) {
		if( ! post_type_exists( $assoc_args['post_type'] ) ) {
			WP_CLI::error( sprintf( 'Post type "%s" doesn\'t exist', $assoc_args['post_type'] ) );
		}

		if( ! post_type_supports( $assoc_args['post_type'], 'thumbnail' ) ) {
			WP_CLI::error( sprintf( 'Post type "%s" doesn\'t support featured images currently', $assoc_args['post_type'] ) );
		}

		$post_ids = Helpers::get_inserted_posts( $assoc_args['post_type'], 'ids', $assoc_args['lang'] ?? '' );
		$image_ids = Helpers::get_inserted_media( 'image/jpeg', 'ids', $assoc_args['lang'] ?? '' );

		$progress = Utils\make_progress_bar( sprintf( 'Attaching images to posts in the post type "%s"', $assoc_args['post_type'] ), $assoc_args['count'] );

		foreach( $post_ids as $post_id ) {
			set_post_thumbnail( $post_id, $image_ids[array_rand( $image_ids )] );

			$progress->tick();
		}

		$progress->finish();
	}
}
