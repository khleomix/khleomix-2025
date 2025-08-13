<?php
/**
 * Registers custom block pattern categories for the Khleomix theme.
 *
 * @package khleomix
 */

namespace WebDevStudios\khleomix;

/**
 * Registers custom block pattern for the Khleomix theme.
 */
function register_custom_block_pattern() {

		register_block_pattern(
			'khleomix/pattern-name',
			array(
				'title'         => __( 'Pattern Title', 'khleomix' ),
				'blockTypes'    => array( 'core/query' ),
				'templateTypes' => array( 'single-post' ),
				'postTypes'     => array( '' ),
				'description'   => _x( 'Block Pattern Name', 'Block pattern description', 'khleomix' ),
				'content'       => '',
			)
		);
}
add_action( 'init', __NAMESPACE__ . '\register_custom_block_pattern', 9 );
