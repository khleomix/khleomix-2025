<?php
/**
 * Register custom block styles.
 *
 * @package khleomix
 */

namespace WebDevStudios\khleomix;

/**
 * Register block variations.
 */
function register_block_variations() {
	wp_enqueue_script(
		'khleomix-enqueue-block-variations',
		get_template_directory_uri() . '/build/js/variations.js',
		array( 'wp-blocks', 'wp-dom-ready', 'wp-element', 'wp-primitives' ),
		wp_get_theme()->get( 'Version' ),
		false
	);
}

add_filter( 'enqueue_block_assets', __NAMESPACE__ . '\register_block_variations', 10, 1 );
