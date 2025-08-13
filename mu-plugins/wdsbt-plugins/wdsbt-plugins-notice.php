<?php
/**
 * Display admin notice suggesting recommended plugins on Plugins and Themes pages.
 */
function wds_bt_suggest_plugins_notice() {
	global $recommended_plugins;

	$current_screen = get_current_screen();

	if ( ! in_array( $current_screen->id, array( 'plugins' ), true ) ) {
		return;
	}

	// Get current user's email.
	$current_user = wp_get_current_user();
	$user_email   = $current_user->user_email;

	// Show notice only if the user has a @webdevstudios.com email.
	if ( strpos( $user_email, '@webdevstudios.com' ) === false ) {
		return;
	}

	// Only show the notice once.
	if ( false === get_option( 'wds_bt_suggest_plugins_dismissed', false ) ) {
		add_option( 'wds_bt_suggest_plugins_dismissed', '0', '', 'yes' );
	}
	$dismissed = get_option( 'wds_bt_suggest_plugins_dismissed', false );
	if ( '1' === $dismissed || 1 === $dismissed ) {
		return;
	}
	update_option( 'wds_bt_suggest_plugins_dismissed', '1' );

	$message = '
	<div class="notice notice-info is-dismissible wds-bt-suggest-plugins-notice">
		<h2>Recommended Plugins</h2>
		<p>We recommend these plugins to optimize and extend the functionality of your theme:</p>
		<ul style="columns: 2; -webkit-columns: 2; -moz-columns: 2;">';

	foreach ( $recommended_plugins as $plugin ) {
		$status = '';
		if ( wds_bt_is_plugin_active( $plugin['slug'] ) ) {
			$status = ' (Active)';
		} elseif ( wds_bt_is_plugin_installed( $plugin['slug'] ) ) {
			$status = ' (Installed but not active)';
		} else {
			$status = ' (Not installed)';
		}

		$message .= '<li><a href="' . esc_url( wds_bt_get_plugin_source_url( $plugin ) ) . '" target="_blank">' . esc_html( $plugin['name'] ) . '</a>' . $status . '</li>';
	}

	$message .= '
		</ul>
		<p><a href="' . esc_url( admin_url( 'plugins.php?page=wds_bt_install_recommended_plugins' ) ) . '" class="button-primary">Install Recommended Plugins</a></p>
	</div>';

	echo wp_kses_post( $message );
}

add_action( 'admin_notices', 'wds_bt_suggest_plugins_notice' );
