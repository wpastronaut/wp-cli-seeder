<?php

namespace WPastronaut\WP_CLI\Seeder;

use Faker;
use WP_CLI;
use WP_CLI\Utils;
use WPastronaut\WP_CLI\Seeder\Helpers;
use WP_CLI\Fetchers\User as UserFetcher;

class Seed_Command {
	private $faker;

	public function __construct() {
		$this->faker = Faker\Factory::create();
	}

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
	 * [--max_depth=<number>]
	 * : Max child depth for hierachial post types.
	 * ---
	 * default: 1
	 * ---
	*/
	public function posts( $args, $assoc_args ) {
		$post_args = [
			'post_type' => $assoc_args['post_type'],
			'post_status' => $assoc_args['post_status'],
		];

		if( ! post_type_exists( $post_args['post_type'] ) ) {
			WP_CLI::error( sprintf( "Post type %s doesn't exist", $post_args['post_type'] ) );
		}

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

			$post_args['post_title'] = $this->faker->sentence( $this->faker->numberBetween( 3, 7 ) );
			$post_args['post_excerpt'] = $this->faker->optional( '0.7', '' )->sentence( $this->faker->numberBetween( 10, 25 ) );
			$post_args['post_content'] = $this->faker->paragraphs( $this->faker->numberBetween( 1, 10 ), true );
			$post_args['post_date'] = $this->faker->dateTimeBetween('-3 years')->format('Y-m-d H:i:m');
			$post_args['post_parent'] = $current_parent;

			$post_id = wp_insert_post( $post_args, true );

			if( is_wp_error( $post_id ) ) {
				WP_CLI::warning( $post_id );

				continue;
			}

			$previous_post_id = $post_id;

			update_post_meta( $post_id, '_wpa_seeder_inserted_at', time() );

			$progress->tick();
		}

		$progress->finish();
	}
}
