<?php

// includes/gle-options-themedy

/**
 * Prevent direct access to this file.
 *
 * @since 1.7.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}


/**
 * Third meta box - optional: setting up the setting fields & labels.
 *    For supported 'Themedy Brand' child themes with CPTs.
 *
 * @since 1.3.0
 *
 * @see   DDW_GLE_Plugin_Settings::help() in /includes/gle-admin-options.php
 *
 * @uses  post_type_exists()
 * @uses  ddw_genesis_layout_extras_option()
 */
function ddw_genesis_layout_extras_box_themedy() {

	/** Description - user info: Child Theme generated special Custom Post Type sections */
	echo '<h4>' . __( 'Special Custom Post Type Sections', 'genesis-layout-extras' ) . '</h4>';

		echo '<p><span class="description">' . __( 'Here you can set up a <strong>default</strong> layout option for various extra archive pages generated by Custom Post Types which were set by child themes.', 'genesis-layout-extras' ) . ' ' . sprintf(
				/* translators: %1$s - opening HTML code tag / %2$s - closing HTML code tag / %3$s - label "Genesis layout settings" */
				__( '%1$sGenesis Default%2$s in the drop-down menus below always means the chosen default layout option in the regular %3$s.', 'genesis-layout-extras' ),
				'<code style="font-style: normal; color: #333;">',
				'</code>',
				'<a href="' . esc_url( admin_url( 'admin.php?page=genesis#genesis-theme-settings-layout' ) ) . '">' . __( 'Genesis layout settings', 'genesis-layout-extras' ) . '</a>'
			) . '</span></p>';

	/** Child Theme: Themedy - Clip Cart */
	if ( post_type_exists( 'products' ) ) {

		echo '<hr class="div" />';

		echo '<h4>' . __( 'Child Theme: Clip Cart by Themedy Themes', 'genesis-layout-extras' ) . '</h4>';

			ddw_genesis_layout_extras_option(
				__( 'Products Post Type Layout (archive)', 'genesis-layout-extras' ) . ': ',
				'ddw_genesis_layout_cpt_themedy_products'
			);

			ddw_genesis_layout_extras_option(
				__( 'Product Categories Taxonomy Layout', 'genesis-layout-extras' ) . ': ',
				'ddw_genesis_layout_cpt_themedy_product_category'
			);

			ddw_gle_save_button();

	}  // end-if clipcart products check

	/** Child Theme: Themedy - Stage */
	if ( post_type_exists( 'photo' ) ) {

		echo '<hr class="div" />';

		echo '<h4>' . __( 'Child Theme: Stage by Themedy Themes', 'genesis-layout-extras' ) . '</h4>';

			ddw_genesis_layout_extras_option(
				__( 'Photo Galleries Taxonomy Layout', 'genesis-layout-extras' ) . ': ',
				'ddw_genesis_layout_cpt_themedy_photo_gallery'
			);

			ddw_gle_save_button();

	}  // end-if stage photo check

}  // end function