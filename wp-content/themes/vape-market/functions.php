<?php

define( 'VAPE_MARKET_THEME_URL', get_template_directory_uri() );

// include customizer
include('customizer.php');

// require source libraries
include('src/Vape_Market_Menu.php');
include('src/Vape_Market_Render.php');
include('src/Vape_Market_Widget.php');

new Vape_Market_Theme();
class Vape_Market_Theme {

	public function __construct() {

		add_action('init', array( $this, 'addMenus' ));
		add_action( 'widgets_init', array( $this, 'widgetsInit' ));

		remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);

	}

	public function addMenus() {

		$menu = new Vape_Market_Menu();
		$menu->register('main', 'Main');
		$menu->register('topbar', 'Top Bar');
		$menu->register('footer1', 'Footer 1');
		$menu->register('footer2', 'Footer 2');
		$menu->register('footer3', 'Footer 3');

	}

	/**
	 * Register our sidebars and widgetized areas.
	 *
	 */
	public static function widgetsInit() {

		$vmWidget = new Vape_Market_Widget();
		$vmWidget->register( 'right_sidebar', 'Right Sidebar' );

		$vmWidget->register( 'home1', 'Home 1' );
		$vmWidget->register( 'home2', 'Home 2' );
		$vmWidget->register( 'home3', 'Home 3' );

		// project sidebar
		$vmWidget->register( 'directory_sidebar', 'Directory Sidebar' );

	}

}

// remove message about how many products are being shown in catalog
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

// modify add to cart text
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text' );
function woo_custom_cart_button_text() {
	return __( 'Buy Now', 'vm-theme' );
}

add_filter( 'woocommerce_product_add_to_cart_text', 'woo_archive_custom_cart_button_text' );
function woo_archive_custom_cart_button_text() {
	return __( 'Buy Now', 'woocommerce' );
}

// remove sorting selector
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

// declare sensei support
add_action( 'after_setup_theme', 'vmDeclareSenseiSupport' );
function vmDeclareSenseiSupport() {
  add_theme_support( 'sensei' );
}

add_filter( 'mb_aio_load_free_extensions', '__return_true' );

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

// test mb settings in customizer
add_filter( 'mb_settings_pages', 'vape_market_settings_page' );
function vape_market_settings_page( $settings_pages ) {
	$settings_pages[] = array(
		'id'          => 'vape-market-settings',
		'option_name' => 'vm_settings',
		'menu_title'  => __( 'Vape Market Settings', 'vape-market' ),
		'columns'     => 1,
	);
	return $settings_pages;
}

// settings footer meta box
function vape_market_get_meta_box( $meta_boxes ) {
	$prefix = 'vm_';

	$meta_boxes[] = array(
		'id' => 'vm-footer',
		'title' => esc_html__( 'Footer Settings', 'vape-market' ),
		'settings_pages' => 'vape-market-settings',
		'context' => 'normal',
		'priority' => 'default',
		'autosave' => false,
		'fields' => array(
			array(
				'id' => $prefix . 'info_box_title',
				'type' => 'text',
				'name' => esc_html__( 'Footer Info Box Title', 'vape-market' ),
			),
			array(
				'id' => $prefix . 'info_box_content',
				'name' => esc_html__( 'WYSIWYG', 'vape-market' ),
				'type' => 'wysiwyg',
			),
			array(
				'id' => $prefix . 'copyright',
				'type' => 'text',
				'name' => esc_html__( 'Copyright Statement', 'vape-market' ),
			),
			array(
				'id' => $prefix . 'footer_image',
				'type' => 'image_advanced',
				'name' => esc_html__( 'Image Advanced', 'vape-market' ),
			),
		),
	);

	return $meta_boxes;
}
add_filter( 'rwmb_meta_boxes', 'vape_market_get_meta_box' );

// template include filter
add_filter( 'template_include', 'vm_template', 99 );

function vm_template( $template ) {

	if ( !is_front_page() && is_home() ) {
		$new_template = locate_template( array( 'page-blog.php' ) );
		if ( '' != $new_template ) {
			return $new_template;
		}
	}

	return $template;

}


/*
 * Locations Tax
 */
 function vm_register_taxonomy() {

 	$args = array (
 		'label' => esc_html__( 'Locations', 'vape-market' ),
 		'labels' => array(
 			'menu_name' => esc_html__( 'Locations', 'vape-market' ),
 			'all_items' => esc_html__( 'All Locations', 'vape-market' ),
 			'edit_item' => esc_html__( 'Edit Location', 'vape-market' ),
 			'view_item' => esc_html__( 'View Location', 'vape-market' ),
 			'update_item' => esc_html__( 'Update Location', 'vape-market' ),
 			'add_new_item' => esc_html__( 'Add new Location', 'vape-market' ),
 			'new_item_name' => esc_html__( 'New Location', 'vape-market' ),
 			'parent_item' => esc_html__( 'Parent Location', 'vape-market' ),
 			'parent_item_colon' => esc_html__( 'Parent Location:', 'vape-market' ),
 			'search_items' => esc_html__( 'Search Locations', 'vape-market' ),
 			'popular_items' => esc_html__( 'Popular Locations', 'vape-market' ),
 			'separate_items_with_commas' => esc_html__( 'Separate Locations with commas', 'vape-market' ),
 			'add_or_remove_items' => esc_html__( 'Add or remove Locations', 'vape-market' ),
 			'choose_from_most_used' => esc_html__( 'Choose most used Locations', 'vape-market' ),
 			'not_found' => esc_html__( 'No Locations found', 'vape-market' ),
 			'name' => esc_html__( 'Locations', 'vape-market' ),
 			'singular_name' => esc_html__( 'Location', 'vape-market' ),
 		),
 		'public' => true,
 		'show_ui' => true,
 		'show_in_menu' => true,
 		'show_in_nav_menus' => true,
 		'show_tagcloud' => true,
 		'show_in_quick_edit' => true,
 		'show_admin_column' => false,
 		'show_in_rest' => false,
 		'rest_base' => false,
 		'hierarchical' => true,
 		'query_var' => true,
 		'sort' => false,
 	);

 	register_taxonomy( 'location', array( 'directory-listing' ), $args );
 }
 add_action( 'init', 'vm_register_taxonomy', 0 );


/*
 * Register directory meta box
 */
 add_filter( 'rwmb_meta_boxes', 'vm_register_meta_boxes' );
 function vm_register_meta_boxes( $meta_boxes ) {
     $meta_boxes[] = array (
       'id' => 'directory-listing',
       'title' => 'Directory Listing',
       'pages' =>   array (
          'directory-listing',
       ),
       'context' => 'normal',
       'priority' => 'high',
       'autosave' => false,
       'fields' =>   array (

         array (
           'id' => 'website',
           'type' => 'url',
           'name' => 'Website',
         ),

         array (
           'id' => 'logo',
           'type' => 'image',
           'name' => 'Logo',
           'max_file_uploads' => 4,
         ),

         array (
           'id' => 'address',
           'type' => 'text',
           'name' => 'Address',
         ),
				 array (
           'id' => 'city',
           'type' => 'text',
           'name' => 'City',
         ),
				 array (
           'id' => 'province',
           'type' => 'text',
           'name' => 'Province',
         ),
				 array (
           'id' => 'postal_code',
           'type' => 'text',
           'name' => 'Postal Code',
         ),

         array (
           'id' => 'map',
           'type' => 'map',
           'name' => 'Map',
           'address_field' => array( 'address, city, province, postal_code' ),
           'region' => 'CA',
           'api_key' => 'AIzaSyA8rfFaL4YUHqREaRDkcqXMIuL5knCgNGk',
           'std' => '60,100,15',
         ),

       ),

       'geo' => '1',
     );

     return $meta_boxes;
 }


add_filter( 'gmap_api_params', function( $params ) {
	 $params['key'] = 'AIzaSyBLEBQdrM3bssCqRMT8r4XSnM3jlyYCXgU';
	 return $params;
});
