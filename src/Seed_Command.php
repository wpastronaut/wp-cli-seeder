<?php

namespace WPastronaut\WP_CLI\Seeder;

use WP_CLI;
use WP_CLI\Utils;
use WPastronaut\WP_CLI\Seeder\Helpers;
use WP_CLI\Fetchers\User as UserFetcher;

class Seed_Command {
	/**
	 * Seed dummy posts.
	 *
	 * ## OPTIONS
	 *
	 * [--count=<number>]
	 * : How many posts do you need?
	 * ---
	 * default: 30
	 * ---
	 *
	 * [--post_type=<type>]
	 * : Post type.
	 * ---
	 * default: post
	 * ---
	 *
	 * [--post_status=<status>]
	 * : Post status.
	 * ---
	 * default: publish
	 * ---
	 *
	 * [--post_author=<login>]
	 * : Post author.
	 * ---
	 * default:
	 * ---
	 *
	 * [--lang=<lang>]
	 * : Post language.
	 * ---
	 * default:
	 * ---
	 *
	 * [--max_depth=<number>]
	 * : Max child depth for hierachial post types.
	 * ---
	 * default: 1
	 * ---
	*/
	public function posts( $args, $assoc_args ) {
		if( ! post_type_exists( $assoc_args['post_type'] ) ) {
			WP_CLI::error( sprintf( "Post type %s doesn't exist", $assoc_args['post_type'] ) );
		}

		$faker = Helpers::faker();

		$post_args = [
			'post_type' => $assoc_args['post_type'],
			'post_status' => $assoc_args['post_status'],
		];

		if ( $assoc_args['post_author'] ) {
			$user_fetcher = new UserFetcher();
			$post_args['post_author'] = $user_fetcher->get_check( $assoc_args['post_author'] )->ID;
		}

		$hierarchical = get_post_type_object( $assoc_args['post_type'] )->hierarchical;

		$progress = Utils\make_progress_bar( sprintf( 'Seeding posts for post type: %s', $post_args['post_type'] ), $assoc_args['count'] );

		$previous_post_id = 0;
		$current_depth = 1;
		$current_parent = 0;

		foreach( range( 0, $assoc_args['count'] ) as $index ) {
			if ( $hierarchical ) {
				if ( Helpers::maybe_make_child() && $current_depth < $assoc_args['max_depth'] ) {
					$current_parent = $previous_post_id;
					$current_depth++;
				} elseif ( Helpers::maybe_reset_depth() ) {
					$current_depth  = 1;
					$current_parent = 0;
				}
			}

			$post_args['post_title'] = ucfirst( $faker->words( $faker->numberBetween( 3, 7 ), true ) );
			$post_args['post_excerpt'] = $faker->optional( '0.7', '' )->sentence( $faker->numberBetween( 10, 25 ) );
			$post_args['post_content'] = $faker->paragraphs( $faker->numberBetween( 1, 10 ), true );
			$post_args['post_date'] = $faker->dateTimeBetween('-3 years')->format('Y-m-d H:i:m');
			$post_args['post_parent'] = $current_parent;

			$post_id = wp_insert_post( $post_args, true );

			if( is_wp_error( $post_id ) ) {
				WP_CLI::warning( $post_id );

				continue;
			}

			$previous_post_id = $post_id;

			if( $assoc_args['lang'] && function_exists( 'pll_set_post_language' ) ) {
				pll_set_post_language( $post_id, $assoc_args['lang'] );
			}

			update_post_meta( $post_id, '_wpa_seeder_inserted_at', time() );

			$progress->tick();
		}

		$progress->finish();
	}

	/**
	 * Seed dummy terms.
	 *
	 * ## OPTIONS
	 *
	 * [--count=<number>]
	 * : How many terms do you need?
	 * ---
	 * default: 30
	 * ---
	 *
	 * [--taxonomy=<taxonomy>]
	 * : Taxonomy.
	 * ---
	 * default: category
	 * ---
	 *
	 * [--max_depth=<number>]
	 * : Max child depth for hierachial taxonomies.
	 * ---
	 * default: 1
	 * ---
	*/
	public function terms( $args, $assoc_args ) {
		if( ! taxonomy_exists( $assoc_args['taxonomy'] ) ) {
			WP_CLI::error( sprintf( "Taxonomy %s doesn't exist", $assoc_args['taxonomy'] ) );
		}

		$faker = Helpers::faker();

		$progress = Utils\make_progress_bar( sprintf( 'Seeding terms for taxonomy: %s', $assoc_args['taxonomy'] ), $assoc_args['count'] );

		$previous_term_id = 0;
		$current_depth = 1;
		$current_parent = 0;

		foreach( range( 0, $assoc_args['count'] ) as $index ) {
			$term_name = ucfirst( $faker->words( $faker->numberBetween( 1, 3 ), true ) );

			if( term_exists( $term_name, $assoc_args['taxonomy'] ) ) {
				continue;
			}

			if ( is_taxonomy_hierarchical( $assoc_args['taxonomy'] ) ) {
				if ( Helpers::maybe_make_child() && $current_depth < $assoc_args['max_depth'] ) {
					$current_parent = $previous_term_id;
					$current_depth++;
				} elseif ( Helpers::maybe_reset_depth() ) {
					$current_depth  = 1;
					$current_parent = 0;
				}
			}

			$term_args = [
				'parent' => $current_parent,
				'description' => $faker->optional( '0.7', '' )->paragraph( $faker->numberBetween( 2, 6 ) ),
			];
			$term = wp_insert_term( $term_name, $assoc_args['taxonomy'], $term_args );

			if( is_wp_error( $term ) ) {
				WP_CLI::warning( $term );

				continue;
			}

			$previous_term_id = $term['term_id'];

			update_term_meta( $term['term_id'], '_wpa_seeder_inserted_at', time() );

			$progress->tick();
		}

		$progress->finish();
	}
}
