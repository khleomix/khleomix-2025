<?php
/**
 * WDS-BT Plugins
 *
 * Recommended Plugins for WDS BT theme.
 *
 * @package WDS-BT-Plugins
 *
 * @author  WebDevStudios, JC <jc@webdevstudios.com>
 */

// Include plugins notice.
require plugin_dir_path( __FILE__ ) . 'wdsbt-plugins-notice.php';

// Load the plugin list.
$plugin_data         = file_exists( plugin_dir_path( __FILE__ ) . 'wdsbt-plugins-list.php' ) ? require plugin_dir_path( __FILE__ ) . 'wdsbt-plugins-list.php' : array(
	'public'  => array(),
	'private' => array(),
	'wds'     => array(),
);
$recommended_plugins = array_merge(
	$plugin_data['public'],
	$plugin_data['private'],
	$plugin_data['wds']
);

// Sort the plugins alphabetically by name.
usort(
	$recommended_plugins,
	function ( $a, $b ) {
		return strcmp( $a['name'], $b['name'] );
	}
);

/**
 * Check if a plugin is installed.
 *
 * @param string $slug Plugin slug.
 * @return bool
 */
function wds_bt_is_plugin_installed( $slug ) {
	$plugins = get_plugins();
	foreach ( $plugins as $plugin_path => $plugin_info ) {
		if ( strpos( $plugin_path, $slug ) !== false ) {
			return true;
		}
	}
	return false;
}

/**
 * Check if a plugin is active.
 *
 * @param string $slug Plugin slug.
 * @return bool
 */
function wds_bt_is_plugin_active( $slug ) {
	$plugins = get_option( 'active_plugins' );
	foreach ( $plugins as $plugin_path ) {
		if ( strpos( $plugin_path, $slug ) !== false ) {
			return true;
		}
	}
	return false;
}

/**
 * Get the plugin source URL, replacing {version} with the actual version if specified.
 *
 * @param array $plugin Plugin data.
 * @return string
 */
function wds_bt_get_plugin_source_url( $plugin ) {
	if ( isset( $plugin['version'] ) && $plugin['version'] ) {
		return str_replace( '{version}', $plugin['version'], $plugin['source_template'] );
	}
	return $plugin['source_template'];
}

/**
 * Register a hidden page for selecting and installing recommended plugins.
 */
function wds_bt_register_plugin_install_page() {
	add_submenu_page(
		'plugins.php',
		'Install Recommended Plugins',
		'Install Recommended Plugins',
		'manage_options',
		'wds_bt_install_recommended_plugins',
		'wds_bt_install_recommended_plugins_page'
	);
}

add_action( 'admin_menu', 'wds_bt_register_plugin_install_page' );

/**
 * Page content for selecting and installing recommended plugins.
 */
function wds_bt_install_recommended_plugins_page() {
	global $recommended_plugins, $plugin_data;

	echo '<div class="wrap" style="background-color: #fff; max-width: 95%; padding: 20px; margin: 2rem 0; border: 1px solid #ccd0d4; border-radius: 10px;">';
	echo '<img src="https://webdevstudios.com/wp-content/uploads/2024/02/wds-banner.png" alt="Banner" style="max-width: 100%; margin-bottom: 20px; border-radius: 10px;">';
	echo '<h1>Install Recommended Plugins</h1>';

	// Check if form was submitted and handle the plugin installation.
	$status_message = '';
	if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] ) {
		// Verify nonce to ensure the request is valid.
		if ( ! isset( $_POST['wds_bt_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wds_bt_nonce'] ) ), 'wds_bt_install_plugins' ) ) {
			wp_die( 'Nonce verification failed. Please try again.' );
		}

		// Sanitize and process the selected plugins.
		$selected_plugins = isset( $_POST['plugins'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['plugins'] ) ) : array();

		// Handle plugin installation and updates.
		$status_message = wds_bt_install_selected_plugins( $selected_plugins );
		wds_bt_update_composer_json( $selected_plugins );
		wds_bt_run_composer_update();
	}

	// Display status message if any plugins were installed.
	if ( $status_message ) {
		echo '<div class="updated"><p>' . esc_html( $status_message ) . '</p></div>';
	}

	// Begin form output.
	echo '<form method="post" id="wds-bt-plugins-form" style="padding: 20px;">';

	// Add nonce field for security.
	wp_nonce_field( 'wds_bt_install_plugins', 'wds_bt_nonce' );

	// Display plugins by category.
	$categories = array(
		'public'  => 'Public Plugins',
		'wds'     => 'WDS Plugins',
		'private' => 'Private Plugins',
	);

	foreach ( $categories as $key => $label ) {
		echo '<div><h2 style="margin-bottom: 0;">' . esc_html( $label ) . '</h2><hr style="margin-top: 5px;">';
		foreach ( $plugin_data[ $key ] as $plugin ) {
			$is_installed = wds_bt_is_plugin_installed( $plugin['slug'] );
			$is_active    = wds_bt_is_plugin_active( $plugin['slug'] );
			$disabled     = $is_installed ? ' disabled' : '';

			echo '<label style="display: block; margin-bottom: 8px;">';
			echo '<input type="checkbox" name="plugins[]" value="' . esc_attr( $plugin['slug'] ) . '"' . esc_attr( $disabled ) . '> ' . esc_html( $plugin['name'] );
			if ( $is_installed ) {
				echo ' (Installed)';
			}
			if ( $is_active ) {
				echo ' (Active)';
			}
			echo '</label>';
		}
		echo '</div>';
	}

	echo '<br><input type="submit" value="Install Selected Plugins" class="button button-primary">';
	echo '</form>';
	echo '</div>';
}

/**
 * Install selected plugins.
 *
 * @param array $selected_plugins List of selected plugins.
 * @return string Status message.
 */
function wds_bt_install_selected_plugins( $selected_plugins ) {
	include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	include_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';
	include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

	global $recommended_plugins, $plugin_data;

	$installed_plugins = array();
	foreach ( $recommended_plugins as $plugin ) {
		if ( in_array( $plugin['slug'], $selected_plugins, true ) ) {
			$upgrader = new Plugin_Upgrader();
			$upgrader->install( wds_bt_get_plugin_source_url( $plugin ) );
			$installed_plugins[] = $plugin['name'];
		}
	}

	if ( ! empty( $installed_plugins ) ) {
		return 'Installed Plugins: ' . implode( ', ', $installed_plugins );
	}

	return '';
}

/**
 * Update composer.json with selected plugins.
 *
 * @param array $selected_plugins List of selected plugins.
 */
function wds_bt_update_composer_json( $selected_plugins ) {
	$composer_file = ABSPATH . 'wp-content/composer.json';

	// Ensure composer.json exists.
	if ( ! file_exists( $composer_file ) ) {
		wp_die( 'composer.json file not found.' );
	}

	/**
	 * Read_composer_file
	 *
	 * @param  string $composer_file Path to the composer.json file.
	 * @return string
	 */
	function wds_bt_read_composer_file( $composer_file ) {
		global $wp_filesystem;

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		WP_Filesystem();

		if ( ! $wp_filesystem->exists( $composer_file ) ) {
			wp_die( 'composer.json file not found.' );
		}

		$composer_content = $wp_filesystem->get_contents( $composer_file );

		if ( false === $composer_content ) {
			wp_die( 'Failed to read composer.json file content.' );
		}

		return $composer_content;
	}

	$composer_content = wds_bt_read_composer_file( $composer_file );
	$composer_data    = json_decode( $composer_content, true );
	if ( json_last_error() !== JSON_ERROR_NONE ) {
		wp_die( esc_html( 'Invalid JSON in composer.json: ' . json_last_error_msg() ) );
	}

	// Ensure repositories key exists.
	if ( ! isset( $composer_data['repositories'] ) ) {
		$composer_data['repositories'] = array();
	}

	// Plugin mapping.
	$plugin_map = array(
		'wp-migrate-db-pro'                => 'deliciousbrains-plugin/wp-migrate-db-pro',
		'cookie-notice'                    => 'wpackagist-plugin/cookie-notice',
		'query-monitor'                    => 'wpackagist-plugin/query-monitor',
		'resmushit-image-optimizer'        => 'wpackagist-plugin/resmushit-image-optimizer',
		'require-auth-users-rest-endpoint' => 'wpackagist-plugin/require-auth-users-rest-endpoint',
		'stream'                           => 'wpackagist-plugin/stream',
		'wordpress-seo'                    => 'wpackagist-plugin/wordpress-seo',
		'advanced-custom-fields-pro'       => 'wpengine/advanced-custom-fields-pro',
		'wds-site-documentation'           => 'webdevstudios/wds-site-documentation',
	);

	// Repositories mapping for private plugins.
	$repositories_map = array(
		'deliciousbrains-plugin/wp-migrate-db-pro' => array(
			'type'    => 'package',
			'package' => array(
				'name'    => 'deliciousbrains-plugin/wp-migrate-db-pro',
				'version' => '2.6.13',
				'dist'    => array(
					'type' => 'zip',
					'url'  => 'https://packages.wdslab.com/dist/deliciousbrains-plugin/wp-migrate-db-pro/deliciousbrains-plugin-wp-migrate-db-pro-2.6.13.zip',
				),
				'require' => array(
					'composer/installers' => '~1.0 || ~2.0',
				),
				'type'    => 'wordpress-plugin',
			),
		),
		'wpengine/advanced-custom-fields-pro'      => array(
			'type'    => 'package',
			'package' => array(
				'name'    => 'wpengine/advanced-custom-fields-pro',
				'version' => '6.3.3',
				'dist'    => array(
					'type' => 'zip',
					'url'  => 'https://packages.wdslab.com/dist/wpengine/advanced-custom-fields-pro/wpengine-advanced-custom-fields-pro-6.3.3.zip',
				),
				'require' => array(
					'composer/installers' => '~1.0 || ~2.0',
				),
				'type'    => 'wordpress-plugin',
			),
		),
	);

	// Update composer.json repositories section.
	foreach ( $selected_plugins as $plugin_slug ) {
		$package_name = $plugin_map[ $plugin_slug ] ?? '';
		if ( isset( $repositories_map[ $package_name ] ) && ! in_array( $repositories_map[ $package_name ], $composer_data['repositories'], true ) ) {
			$composer_data['repositories'][] = $repositories_map[ $package_name ];
		}
	}

	// Update composer.json require section.
	foreach ( $selected_plugins as $plugin_slug ) {
		if ( isset( $plugin_map[ $plugin_slug ] ) ) {

			global $recommended_plugins;

			$version = '';
			foreach ( $recommended_plugins as $plugin ) {
				if ( $plugin['slug'] === $plugin_slug ) {
					$version = $plugin['version'];
					break;
				}
			}
			$composer_data['require'][ $plugin_map[ $plugin_slug ] ] = $version ? $version : '*';
		}
	}

	// Write updated content back to composer.json.
	global $wp_filesystem;
	if ( ! function_exists( 'WP_Filesystem' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}
	WP_Filesystem();
	$result = $wp_filesystem->put_contents( $composer_file, wp_json_encode( $composer_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
}

/**
 * Run composer update.
 * Restricted to local environments using WP_ENVIRONMENT_TYPE.
 */
function wds_bt_run_composer_update() {
	if ( 'local' !== wp_get_environment_type() ) {
		wp_die( 'This function can only run in local environments.' );
	}

	$command = 'composer update';

	$output = shell_exec( $command ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.system_calls_shell_exec

	if ( $output ) {
		error_log( 'Composer Update Output: ' . $output ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	} else {
		error_log( 'Composer Update: No output returned or command failed.' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}
}
