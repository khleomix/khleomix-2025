<?php
/**
 * Registers custom block pattern categories for the Khleomix theme.
 *
 * @package khleomix
 */

namespace WebDevStudios\khleomix;

/**
 * Registers custom block pattern categories for the Khleomix theme.
 */
function register_custom_block_pattern_categories() {

	register_block_pattern_category(
		'content',
		array(
			'label'       => __( 'Content', 'khleomix' ),
			'description' => __( 'A collection of content patterns designed for Khleomix.', 'khleomix' ),
		)
	);
	register_block_pattern_category(
		'hero',
		array(
			'label'       => __( 'Hero', 'khleomix' ),
			'description' => __( 'A collection of hero patterns designed for Khleomix.', 'khleomix' ),
		)
	);
	register_block_pattern_category(
		'page',
		array(
			'label'       => __( 'Pages', 'khleomix' ),
			'description' => __( 'A collection of page patterns designed for Khleomix.', 'khleomix' ),
		)
	);
	register_block_pattern_category(
		'template',
		array(
			'label'       => __( 'Templates', 'khleomix' ),
			'description' => __( 'A collection of template patterns designed for Khleomix.', 'khleomix' ),
		)
	);

	// Remove default patterns.
	remove_theme_support( 'core-block-patterns' );
}
add_action( 'init', __NAMESPACE__ . '\register_custom_block_pattern_categories', 9 );
