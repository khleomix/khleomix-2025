<?php
/**
 * List of recommended plugins categorized by source.
 * Includes public, WebDevStudios-specific, and private plugins.
 *
 * @package WDS-BT-Plugins
 */

return array(
	'public'  => array(
		array(
			'name'            => 'Blocks Animation',
			'slug'            => 'blocks-animation',
			'source_template' => 'https://downloads.wordpress.org/plugin/blocks-animation.latest-stable.zip',
			'version'         => null,
		),
		array(
			'name'            => 'Breadcrumb Block',
			'slug'            => 'breadcrumb-block',
			'source_template' => 'https://downloads.wordpress.org/plugin/breadcrumb-block.latest-stable.zip',
			'version'         => null,
		),
		array(
			'name'            => 'Cookie Notice',
			'slug'            => 'cookie-notice',
			'source_template' => 'https://downloads.wordpress.org/plugin/cookie-notice.latest-stable.zip',
			'version'         => null,
		),
		array(
			'name'            => 'Icon Block',
			'slug'            => 'icon-block',
			'source_template' => 'https://downloads.wordpress.org/plugin/icon-block.latest-stable.zip',
			'version'         => null,
		),
		array(
			'name'            => 'Query Monitor',
			'slug'            => 'query-monitor',
			'source_template' => 'https://downloads.wordpress.org/plugin/query-monitor.latest-stable.zip',
			'version'         => null,
		),
		array(
			'name'            => 'reSmush.it Image Optimizer',
			'slug'            => 'resmushit-image-optimizer',
			'source_template' => 'https://downloads.wordpress.org/plugin/resmushit-image-optimizer.latest-stable.zip',
			'version'         => null,
		),
		array(
			'name'            => 'Require Auth for Users REST Endpoint',
			'slug'            => 'require-auth-users-rest-endpoint',
			'source_template' => 'https://downloads.wordpress.org/plugin/require-auth-users-rest-endpoint.latest-stable.zip',
			'version'         => null,
		),
		array(
			'name'            => 'Stream',
			'slug'            => 'stream',
			'source_template' => 'https://downloads.wordpress.org/plugin/stream.latest-stable.zip',
			'version'         => null,
		),
		array(
			'name'            => 'Yoast SEO',
			'slug'            => 'wordpress-seo',
			'source_template' => 'https://downloads.wordpress.org/plugin/wordpress-seo.latest-stable.zip',
			'version'         => null,
		),
	),

	// WebDevStudios-specific plugins from GitHub.
	'wds'     => array(
		array(
			'name'            => 'WDS Site Documentation',
			'slug'            => 'wds-site-documentation',
			'source_template' => 'https://github.com/WebDevStudios/wds-site-documentation/archive/refs/heads/main.zip',
			'version'         => '^1.0.0',
		),
	),

	// Private/commercial plugins with version-specific links.
	'private' => array(
		array(
			'name'            => 'Advanced Custom Fields Pro',
			'slug'            => 'advanced-custom-fields-pro',
			'source_template' => 'https://packages.wdslab.com/dist/wpengine/advanced-custom-fields-pro/wpengine-advanced-custom-fields-pro-{version}.zip',
			'version'         => '6.3.3',
		),
		array(
			'name'            => 'Delicious Brains WP Migrate DB Pro',
			'slug'            => 'wp-migrate-db-pro',
			'source_template' => 'https://packages.wdslab.com/dist/deliciousbrains-plugin/wp-migrate-db-pro/deliciousbrains-plugin-wp-migrate-db-pro-{version}.zip',
			'version'         => '2.6.13',
		),
	),
);

/**
 * Get the source URL for a plugin, dynamically replacing {version}.
 *
 * @param array $plugin Plugin data array (name, slug, source_template, version).
 *
 * @return string Processed source URL.
 */
function get_plugin_source( $plugin ) {
	if ( ! empty( $plugin['version'] ) ) {
		return str_replace( '{version}', $plugin['version'], $plugin['source_template'] );
	}
	return $plugin['source_template'];
}
