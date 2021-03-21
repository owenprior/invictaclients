<?php
/**
 * Workstation Pro.
 *
 * This file adds the functions to the Workstation Pro Theme.
 *
 * @package Workstation
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    http://my.studiopress.com/themes/workstation/
 */

// Start the engine.
include_once( get_template_directory() . '/lib/init.php' );

// Setup Theme.
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

// Set Localization (do not remove).
add_action( 'after_setup_theme', 'workstation_localization_setup' );
function workstation_localization_setup(){
	load_child_theme_textdomain( 'workstation-pro', get_stylesheet_directory() . '/languages' );
}

// Add the theme helper functions.
include_once( get_stylesheet_directory() . '/lib/helper-functions.php' );

// Add Image upload and Color select to WordPress Theme Customizer.
require_once( get_stylesheet_directory() . '/lib/customize.php' );

// Include Customizer CSS.
include_once( get_stylesheet_directory() . '/lib/output.php' );

// Include WooCommerce support.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php' );

// Include the WooCommerce styles and the Customizer CSS.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php' );

// Include notice to install Genesis Connect for WooCommerce.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php' );

// Child theme (do not remove).
define( 'CHILD_THEME_NAME', __( 'Workstation Pro', 'workstation-pro' ) );
define( 'CHILD_THEME_URL', 'http://my.studiopress.com/themes/workstation/' );
define( 'CHILD_THEME_VERSION', '1.1.5' );

// Enqueue scripts and styles.
add_action( 'wp_enqueue_scripts', 'workstation_enqueue_scripts_styles' );
function workstation_enqueue_scripts_styles() {

	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300italic,700italic,700,300', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'dashicons' );

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_script( 'workstation-responsive-menu', get_stylesheet_directory_uri() . '/js/responsive-menus' . $suffix . '.js', array( 'jquery' ), CHILD_THEME_VERSION, true );
	wp_localize_script(
		'workstation-responsive-menu',
		'genesis_responsive_menu',
		workstation_responsive_menu_settings()
	);

}

// Setup our responsive menu settings.
function workstation_responsive_menu_settings() {

	$settings = array(
		'mainMenu'    => __( 'Menu', 'workstation-pro' ),
		'subMenu'     => __( 'Submenu', 'workstation-pro' ),
		'menuClasses' => array(
			'combine' => array(
				'.nav-secondary',
				'.nav-primary',
			),
		),
	);

	return $settings;

}

// Add HTML5 markup structure.
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

// Add accessibility support.
add_theme_support( 'genesis-accessibility', array( '404-page', 'drop-down-menu', 'headings', 'rems', 'search-form', 'skip-links' ) );

// Add viewport meta tag for mobile browsers.
add_theme_support( 'genesis-responsive-viewport' );

// Add support for custom header.
add_theme_support( 'custom-header', array(
	'flex-height'     => true,
	'width'           => 0,
	'height'          => 0,
	'header-selector' => '.site-title a',
	'header-text'     => false,
) );

function genesischild_swap_header( $title, $inside, $wrap ) {
	// Set what goes inside the wrapping tags.
	if ( get_header_image() ) :
		$logo = '<img  src="' . get_header_image() . '" width="' . esc_attr( get_custom_header()->width ) . '" height="' . esc_attr( get_custom_header()->height ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '">';
	else :
		$logo = get_bloginfo( 'name' );
	endif;
		 $inside = sprintf( '<a href="%s" title="%s">%s</a>', trailingslashit( home_url() ), esc_attr( get_bloginfo( 'name' ) ), $logo );
		 // Determine which wrapping tags to use - changed is_home to is_front_page to fix Genesis bug.
		 $wrap = is_front_page() && 'title' === genesis_get_seo_option( 'home_h1_on' ) ? 'h1' : 'p';
		 // A little fallback, in case an SEO plugin is active - changed is_home to is_front_page to fix Genesis bug.
		 $wrap = is_front_page() && ! genesis_get_seo_option( 'home_h1_on' ) ? 'h1' : $wrap;
		 // And finally, $wrap in h1 if HTML5 & semantic headings enabled.
		 $wrap = genesis_html5() && genesis_get_seo_option( 'semantic_headings' ) ? 'h1' : $wrap;
		 $title = sprintf( '<%1$s %2$s>%3$s</%1$s>', $wrap, genesis_attr( 'site-title' ), $inside );
		 return $title;
}
add_filter( 'genesis_seo_title','genesischild_swap_header', 10, 3 );


// Add support for structural wraps.
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'footer',
) );

// Add support for after entry widget.
add_theme_support( 'genesis-after-entry-widget-area' );

// Add image sizes.
add_image_size( 'featured-content-lg', 1200, 600, TRUE );
add_image_size( 'featured-content-sm', 600, 400, TRUE );

// Unregister layout settings.
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Unregister secondary sidebar.
unregister_sidebar( 'sidebar-alt' );

// Unregister the header right widget area.
unregister_sidebar( 'header-right' );

// Rename Primary Menu.
add_theme_support ( 'genesis-menus', array ( 'secondary' => __( 'Before Header Menu', 'workstation-pro' ), 'primary' => __( 'Header Menu', 'workstation-pro' ) ) );

// Remove output of primary navigation right extras.
remove_filter( 'genesis_nav_items', 'genesis_nav_right', 10, 2 );
remove_filter( 'wp_nav_menu_items', 'genesis_nav_right', 10, 2 );

// Remove navigation meta box.
add_action( 'genesis_theme_settings_metaboxes', 'workstation_remove_genesis_metaboxes' );
function workstation_remove_genesis_metaboxes( $_genesis_theme_settings_pagehook ) {
    remove_meta_box( 'genesis-theme-settings-nav', $_genesis_theme_settings_pagehook, 'main' );
}

// Reposition the navigation.
remove_action( 'genesis_after_header', 'genesis_do_nav' );
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_before_header', 'genesis_do_subnav' );
add_action( 'genesis_header', 'genesis_do_nav', 5 );

// Remove skip link for primary navigation and add skip link for footer widgets.
add_filter( 'genesis_skip_links_output', 'workstation_skip_links_output' );
function workstation_skip_links_output( $links ){

	if( isset( $links['genesis-nav-primary'] ) ){
		unset( $links['genesis-nav-primary'] );
	}

	$new_links = $links;
	array_splice( $new_links, 3 );

	if ( is_active_sidebar( 'flex-footer' ) ) {
		$new_links['footer'] = __( 'Skip to footer', 'workstation-pro' );
	}

	return array_merge( $new_links, $links );

}

// Reposition the entry meta in the entry header.
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
add_action( 'genesis_entry_header', 'genesis_post_info', 8 );

// Reposition the entry image.
remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
add_action( 'genesis_entry_header', 'genesis_do_post_image', 5 );

// Add featured image above the entry content.
add_action( 'genesis_entry_header', 'workstation_featured_photo', 5 );
function workstation_featured_photo() {

	if ( is_attachment() || ! genesis_get_option( 'content_archive_thumbnail' ) ) {
		return;
	}

	if ( is_singular() && is_single() && $image = genesis_get_image( array( 'format' => 'url', 'size' => genesis_get_option( 'image_size' ) ) ) ) {
		//printf( '<div class="featured-image"><img src="%s" alt="%s" class="entry-image"/></div>', $image, the_title_attribute( 'echo=0' ) );
	}

}

// Add Excerpt support to Pages.
add_post_type_support( 'page', 'excerpt' );

// Output Excerpt on Pages.
add_action( 'genesis_meta', 'workstation_page_description_meta' );
function workstation_page_description_meta() {

	if ( is_front_page() ) {
		remove_action( 'genesis_site_description', 'genesis_seo_site_description' );
		add_action( 'genesis_after_header', 'workstation_open_after_header', 5 );
		add_action( 'genesis_after_header', 'genesis_seo_site_description', 10 );
		add_action( 'genesis_after_header', 'workstation_close_after_header', 15 );
	}

	if ( is_archive() && ! is_post_type_archive() ) {
		remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );
		add_action( 'genesis_after_header', 'workstation_open_after_header', 5 );
		add_action( 'genesis_after_header', 'genesis_do_taxonomy_title_description', 10 );
		add_action( 'genesis_after_header', 'workstation_close_after_header', 15 );
	}

	if ( is_post_type_archive() && genesis_has_post_type_archive_support() ) {
		remove_action( 'genesis_before_loop', 'genesis_do_cpt_archive_title_description' );
		add_action( 'genesis_after_header', 'workstation_open_after_header', 5 );
		add_action( 'genesis_after_header', 'genesis_do_cpt_archive_title_description', 10 );
		add_action( 'genesis_after_header', 'workstation_close_after_header', 15 );
	}

	if( is_author() ) {
		remove_action( 'genesis_before_loop', 'genesis_do_author_title_description', 15 );
		add_action( 'genesis_after_header', 'workstation_open_after_header', 5 );
		add_action( 'genesis_after_header', 'genesis_do_author_title_description', 10 );
		add_action( 'genesis_after_header', 'workstation_close_after_header', 15 );
	}

	if ( is_page_template( 'page_blog.php' ) && has_excerpt() ) {
		remove_action( 'genesis_before_loop', 'genesis_do_blog_template_heading' );
		add_action( 'genesis_after_header', 'workstation_open_after_header', 5 );
		add_action( 'genesis_after_header', 'workstation_add_page_description', 10 );
		add_action( 'genesis_after_header', 'workstation_close_after_header', 15 );
	}

/*	elseif ( is_singular() && is_page() && has_excerpt() ) {
		remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
		add_action( 'genesis_after_header', 'workstation_open_after_header', 5 );
		add_action( 'genesis_after_header', 'workstation_add_page_description', 10 );
		add_action( 'genesis_after_header', 'workstation_close_after_header', 15 );
	}
*/
}

// Output the page title and description.
function workstation_add_page_description() {

	echo '<div class="page-description">';
	echo '<h1 itemprop="headline" class="page-title">' . get_the_title() . '</h1>';
	echo '<p>' . get_the_excerpt() . '</p></div>';

}

// Output the after header wrap div.
function workstation_open_after_header() {
	echo '<div class="after-header"><div class="wrap">';
}

// Output the after header wrap div closing tags.
function workstation_close_after_header() {
	echo '</div></div>';
}

// Setup widget counts.
function workstation_count_widgets( $id ) {

	$sidebars_widgets = wp_get_sidebars_widgets();

	if ( isset( $sidebars_widgets[ $id ] ) ) {
		return count( $sidebars_widgets[ $id ] );
	}

}

// Get the class string for a flexible widget.
function workstation_widget_area_class( $id ) {

	$count = workstation_count_widgets( $id );

	$class = '';

	if( $count == 1 ) {
		$class .= ' widget-full';
	} elseif( $count % 3 == 1 ) {
		$class .= ' widget-thirds';
	} elseif( $count % 4 == 1 ) {
		$class .= ' widget-fourths';
	} elseif( $count % 6 == 0 ) {
		$class .= ' widget-uneven';
	} elseif( $count % 2 == 0 ) {
		$class .= ' widget-halves uneven';
	} else {
		$class .= ' widget-halves';
	}

	return $class;

}

// Add the flexible footer widget area.
add_action( 'genesis_before_footer', 'workstation_footer_widgets' );
function workstation_footer_widgets() {

	genesis_widget_area( 'flex-footer', array(
		'before' => '<div id="footer" class="flex-footer footer-widgets"><h2 class="genesis-sidebar-title screen-reader-text">' . __( 'Footer', 'workstation-pro' ) . '</h2><div class="flexible-widgets widget-area wrap' . workstation_widget_area_class( 'flex-footer' ) . '">',
		'after'  => '</div></div>',
	) );

}

// Register widget areas.
genesis_register_sidebar( array(
	'id'          => 'front-page-1',
	'name'        => __( 'Front Page 1', 'workstation-pro' ),
	'description' => __( 'This is the front page 1 section.', 'workstation-pro' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-2',
	'name'        => __( 'Front Page 2', 'workstation-pro' ),
	'description' => __( 'This is the front page 2 section.', 'workstation-pro' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-3',
	'name'        => __( 'Front Page 3', 'workstation-pro' ),
	'description' => __( 'This is the front page 3 section.', 'workstation-pro' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-4',
	'name'        => __( 'Front Page 4', 'workstation-pro' ),
	'description' => __( 'This is the front page 4 section.', 'workstation-pro' ),
) );
genesis_register_sidebar( array(
	'id'          => 'flex-footer',
	'name'        => __( 'Flexible Footer', 'workstation-pro' ),
	'description' => __( 'This is the footer section.', 'workstation-pro' ),
) );

add_filter('woocommerce_add_to_cart_redirect', 'themeprefix_add_to_cart_redirect');
function themeprefix_add_to_cart_redirect() {
 global $woocommerce;
 $checkout_url = wc_get_checkout_url();
 return $checkout_url;
}
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
add_filter( 'wc_add_to_cart_message_html', '__return_false' );
add_filter( 'woocommerce_order_button_text', 'woo_custom_order_button_text' ); 

function woo_custom_order_button_text() {
    return __( 'Process Payment', 'woocommerce' ); 
}

/**
 * @snippet       WooCommerce Only one product in cart
 * @how-to        Watch tutorial @ https://businessbloomer.com/?p=19055
 * @sourcecode    https://businessbloomer.com/?p=560
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 3.4.3
 */
 
add_filter( 'woocommerce_add_to_cart_validation', 'bbloomer_only_one_in_cart', 99, 2 );
  
function bbloomer_only_one_in_cart( $passed, $added_product_id ) {
 
// empty cart first: new item will replace previous
wc_empty_cart();
 
// display a message if you like
//wc_add_notice( 'New product added to cart!', 'notice' );
 
return $passed;
}


/*to let is upload svgs */

add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {

global $wp_version; if( $wp_version == '4.7' || ( (float) $wp_version < 4.7 ) ) { return $data; }

$filetype = wp_check_filetype( $filename, $mimes );

return [ 'ext' => $filetype['ext'], 'type' => $filetype['type'], 'proper_filename' => $data['proper_filename'] ];

}, 10, 4 );

function cc_mime_types( $mimes ){ $mimes['svg'] = 'image/svg+xml'; return $mimes; } add_filter( 'upload_mimes', 'cc_mime_types' );

/*function fix_svg() { echo ' .attachment-266x266, .thumbnail img { width: 100% !important; height: auto !important; } '; } add_action( 'admin_head', 'fix_svg' );*/

//* Change the footer text
add_filter('genesis_footer_creds_text', 'sp_footer_creds_filter');
function sp_footer_creds_filter( $creds ) {
	$creds = '[footer_copyright] &middot; INVICTA LAW CORPORATION';
	return $creds;
}

//* Remove the site description
add_filter( 'genesis_attr_site-description', 'genesis_attributes_screen_reader_class' );


//* Add extra CSS classes to animate the site-container
add_filter('genesis_attr_site-container', 'ig_animate_site_container');
function ig_animate_site_container($attributes) {

    $attributes['class'] .= ' animate-site-container fadeIn';
    return $attributes;

}

/* allow shortcodes in widgets */
add_filter( 'widget_text', 'do_shortcode' );

add_action( 'wp_enqueue_scripts', 'event_tracking' );
function event_tracking() {
    wp_enqueue_script( 'follow', get_stylesheet_directory_uri() . '/js/eventtracking.js', array( 'jquery' ), '', true );
}

// Add Read More Link to Excerpts

add_filter('excerpt_more', 'get_read_more_link');

add_filter( 'the_content_more_link', 'get_read_more_link' );

function get_read_more_link() {

   return '<a href="' . get_permalink() . '">[Read More]</a>';

}

//* Modify breadcrumb arguments.
add_filter( 'genesis_breadcrumb_args', 'sp_breadcrumb_args' );
function sp_breadcrumb_args( $args ) {
	$args['home'] = 'Home';
	$args['sep'] = ' / ';
	$args['list_sep'] = ', '; // Genesis 1.5 and later
	$args['prefix'] = '<div class="breadcrumb">';
	$args['suffix'] = '</div>';
	$args['heirarchial_attachments'] = true; // Genesis 1.5 and later
	$args['heirarchial_categories'] = true; // Genesis 1.5 and later
	$args['display'] = true;
	$args['labels']['prefix'] = '';
	$args['labels']['author'] = 'Archives for ';
	$args['labels']['category'] = 'Archives for '; // Genesis 1.6 and later
	$args['labels']['tag'] = 'Archives for ';
	$args['labels']['date'] = 'Archives for ';
	$args['labels']['search'] = 'Search for ';
	$args['labels']['tax'] = 'Archives for ';
	$args['labels']['post_type'] = 'Archives for ';
	$args['labels']['404'] = 'Not found: '; // Genesis 1.5 and later
return $args;
}

//* Remove breadcrumb from a single page
//* https://codex.wordpress.org/Function_Reference/is_page
function b3m_remove_genesis_breadcrumb() {
  if ( is_page( array('about','immigration-law','family-law','corporate-law','notary-public-vancouver', 'contact') ) ) {
    remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
  }
}
add_action( 'genesis_before', 'b3m_remove_genesis_breadcrumb' );

function jetpackme_no_related_posts( $options ) {
    if (
        is_single(
            array( 6552, 1 )
        )
    ) {
        $options['enabled'] = false;
    }
 
    return $options;
}
add_filter( 'jetpack_relatedposts_filter_options', 'jetpackme_no_related_posts' );