<?php
/**
 * Register custom block styles.
 * Learn More: https://developer.wordpress.org/reference/functions/register_block_style/
 *
 * @package khleomix
 */

namespace WebDevStudios\khleomix;

/**
 * Register block styles.
 */
function register_block_styles() {

	$block_styles = array(
		'core/button' => array(
			'minimal' => __( 'Minimal', 'khleomix' ),
			'text'    => __( 'Text Only', 'khleomix' ),
		),
		'core/quote'  => array(
			'large' => __( 'Large', 'khleomix' ),
		),
	);

	foreach ( $block_styles as $block => $styles ) {
		foreach ( $styles as $style_name => $style_label ) {
			register_block_style(
				$block,
				array(
					'name'  => $style_name,
					'label' => $style_label,
				)
			);
		}
	}
}
add_filter( 'init', __NAMESPACE__ . '\register_block_styles', 10, 1 );
