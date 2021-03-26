<?php

namespace WPastronaut\WP_CLI\Seeder;

use WP_CLI\Utils;

class Delete_Command {
	public function all( $args, $assoc_args ) {
		\WP_CLI::confirm( "Are you sure you want to delete all seeded data from the site?", $assoc_args );

		$deletable_post_ids = get_posts([
			'post_type' => 'any',
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
}
