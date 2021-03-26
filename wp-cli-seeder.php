<?php

namespace WPastronaut\WP_CLI\Seeder;

if ( defined( 'WP_CLI' ) ) {
	\WP_CLI::add_command( 'seeder seed', __NAMESPACE__ . '\\Seed_Command' );
	\WP_CLI::add_command( 'seeder delete', __NAMESPACE__ . '\\Delete_Command' );
}
