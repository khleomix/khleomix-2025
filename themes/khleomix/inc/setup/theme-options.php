<?php
/**
 * WDS-BT Theme Options admin page.
 *
 * Adds a settings page under Tools with options for speculative loading behavior.
 *
 * @package Khleomix
 */

namespace WebDevStudios\khleomix;

/**
 * Registers the WDS-BT settings page under Tools.
 */
function register_settings_page() {
	add_submenu_page(
		'tools.php',
		__( 'Khleomix Settings', 'khleomix' ),
		__( 'Khleomix Settings', 'khleomix' ),
		'manage_options',
		'khleomix-settings',
		__NAMESPACE__ . '\render_settings_page'
	);
}
add_action( 'admin_menu', __NAMESPACE__ . '\register_settings_page' );

/**
 * Renders the settings page UI.
 */
function render_settings_page() {
	$exclude_option = 'exclude_sensitive_pages';
	$global_option  = 'maybe_disable_speculative_loading';
	$debug_option   = 'maybe_log_speculative_debug';

	$exclude_value = get_option( $exclude_option, true );
	$global_value  = get_option( $global_option, false );
	$debug_value   = get_option( $debug_option, false );

	// Save on POST.
	if ( isset( $_POST['settings_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['settings_nonce'] ) ), 'save_settings' ) ) {
		$exclude_value = isset( $_POST[ $exclude_option ] );
		$global_value  = isset( $_POST[ $global_option ] );
		$debug_value   = isset( $_POST[ $debug_option ] );

		update_option( $exclude_option, $exclude_value );
		update_option( $global_option, $global_value );
		update_option( $debug_option, $debug_value );

		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Settings saved.', 'khleomix' ) . '</p></div>';
	}

	// Determine current status summary.
	$loading_status = $global_value
		? __( 'Speculative Loading is currently <strong>disabled globally</strong>.', 'khleomix' )
		: ( $exclude_value
			? __( 'Speculative Loading is <strong>enabled</strong>, but <strong>sensitive pages are excluded</strong>.', 'khleomix' )
			: __( 'Speculative Loading is <strong>fully enabled</strong>.', 'khleomix' )
		);
	?>

	<div class="wrap">
		<h1><?php esc_html_e( 'Khleomix Settings', 'khleomix' ); ?></h1>
		<p><?php esc_html_e( 'Control optional features for this block theme.', 'khleomix' ); ?></p>

		<div class="notice notice-info" style="margin-top: 20px;">
			<p><?php echo wp_kses_post( $loading_status ); ?></p>
		</div>

		<form method="post" style="margin-top: 2em;">
			<?php wp_nonce_field( 'save_settings', 'settings_nonce' ); ?>

			<h2 class="title"><?php esc_html_e( 'Performance Options', 'khleomix' ); ?></h2>

			<hr />

			<label style="display: flex; align-items: center; gap: 12px;">
				<input type="checkbox" name="<?php echo esc_attr( $global_option ); ?>" <?php checked( $global_value ); ?> />
				<h3><?php esc_html_e( 'Disable Speculative Loading for the Entire Site', 'khleomix' ); ?></h3>
			</label>
			<p class="description">
				<?php esc_html_e( 'No pages will be prefetched or prerendered.', 'khleomix' ); ?>
			</p>

			<label style="display: flex; align-items: center; gap: 12px; margin-top: 16px;">
				<input type="checkbox" name="<?php echo esc_attr( $exclude_option ); ?>" <?php checked( $exclude_value ); ?> />
				<h3><?php esc_html_e( 'Exclude sensitive pages only (e.g., Cart, Checkout)', 'khleomix' ); ?></h3>
			</label>
			<p class="description">
				<?php esc_html_e( 'Recommended for e-commerce and membership sites.', 'khleomix' ); ?>
			</p>

			<label style="display: flex; align-items: center; gap: 12px; margin-top: 16px;">
				<input type="checkbox" name="<?php echo esc_attr( $debug_option ); ?>" <?php checked( $debug_value ); ?> />
				<h3><?php esc_html_e( 'Enable debug logging for speculative loading in console', 'khleomix' ); ?></h3>
			</label>
			<p class="description">
				<?php esc_html_e( 'Logs whether a page was prerendered, is prerendering, or was loaded normally.', 'khleomix' ); ?>
			</p>

			<?php submit_button( __( 'Save Settings', 'khleomix' ) ); ?>

		</form>
	</div>
	<?php
}
