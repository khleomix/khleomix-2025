<?php
/**
 * Title: Primary Category
 * Slug: khleomix/primary-category
 * Categories: posts
 * Block Types: custom/primary-category
 * Inserter: false
 *
 * @package khleomix
 */

$khleomix_category = get_the_category();

if ( $khleomix_category ) {
	// Initialize variables.
	$khleomix_category_display = '';
	$khleomix_category_link    = '';

	// Get primary category if available.
	if ( class_exists( 'WPSEO_Primary_Term' ) ) {
		$khleomix_primary_term = new WPSEO_Primary_Term( 'category', get_the_id() );
		$khleomix_primary_term = $khleomix_primary_term->get_primary_term();
		$khleomix_term         = get_term( $khleomix_primary_term );

		// Check if primary term exists.
		if ( ! is_wp_error( $khleomix_term ) ) {
			$khleomix_category_display = $khleomix_term->name;
			$khleomix_category_link    = get_category_link( $khleomix_term->term_id );
		}
	}

	// If primary term not found, use the first category.
	if ( empty( $khleomix_category_display ) && isset( $category[0] ) ) {
		$khleomix_category_display = $category[0]->name;
		$khleomix_category_link    = get_category_link( $category[0]->term_id );
	}

	// Display category if available.
	if ( ! empty( $khleomix_category_display ) ) {
		?>
		<h2 class="wp-block-heading has-large-font-size" style="padding-top: var(--wp--preset--spacing--20); padding-bottom: var(--wp--preset--spacing--20);">
			More <a href="<?php echo esc_url( $khleomix_category_link ); ?>"><?php echo esc_html( $khleomix_category_display ); ?></a>
		</h2>
		<?php
	}
}
