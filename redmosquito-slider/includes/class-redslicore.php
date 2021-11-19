<?php
/**
 * Redmosquito Slider Core
 *
 * @package widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RedSliCore' ) ) {
	/**
	 * RedSli Core class
	 *
	 * @class RedSliCore
	 */
	class RedSliCore {

		/**
		 * Plugin version
		 *
		 * @var string
		 */
		public string $version = '0.0.1';

		/**
		 * The single instance of the class
		 *
		 * @var RedSliCore|null
		 */
		protected static ?RedSliCore $_instance = null;

		/**
		 * Registered sliders.
		 *
		 * @var array
		 */
		private array $sliders = array();

		/**
		 * Redmosquito Slider Core
		 *
		 * Uses the Singleton pattern to load 1 instance of this class at maximum
		 *
		 * @static
		 * @return RedSliCore
		 */
		public static function instance(): RedSliCore {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Constructor
		 */
		private function __construct() {
			$this->define_constants();
			$this->init_hooks();
			$this->actions_and_filters();
			$this->add_shortcodes();
		}

		/**
		 * Initialise Widgets Collection
		 */
		public function init() {
			$this->initialise_localisation();
			do_action( 'redmosquito_slider_init' );
		}

		/**
		 * Initialise the localisation of the plugin.
		 */
		private function initialise_localisation() {
			load_plugin_textdomain( 'redmosquito-slider', false, plugin_basename( dirname( REDSLI_PLUGIN_FILE ) ) . '/languages/' );
		}

		/**
		 * Define constants of the plugin.
		 */
		private function define_constants() {
			$this->define( 'REDSLI_ABSPATH', dirname( REDSLI_PLUGIN_FILE ) . '/' );
			$this->define( 'REDSLI_VERSION', $this->version );
			$this->define( 'REDSLI_FULLNAME', 'redmosquito-slider' );
		}

		/**
		 * Define if not already set
		 *
		 * @param string $name name of the variable to define.
		 * @param string $value value of the variable to define.
		 */
		private static function define( string $name, string $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Initialise activation and deactivation hooks.
		 */
		private function init_hooks() {
			register_activation_hook( REDSLI_PLUGIN_FILE, array( $this, 'activation' ) );
			register_deactivation_hook( REDSLI_PLUGIN_FILE, array( $this, 'deactivation' ) );
		}

		/**
		 * Activation hook call.
		 */
		public function activation() {
		}

		/**
		 * Deactivation hook call.
		 */
		public function deactivation() {
		}

		/**
		 * Add pluggable support to functions
		 */
		public function pluggable() {
			include_once REDSLI_ABSPATH . 'includes/redsli-functions.php';
		}

		/**
		 * Add custom slider post type.
		 */
		public function add_post_type() {
			register_post_type(
				'redsli_slider',
				array(
					'label' => __( 'Sliders', 'redmosquito-slider' ),
					'labels' => array(
						'name' => __( 'Sliders', 'redmosquito-slider' ),
						'singular_name' => __( 'Slider', 'redmosquito-slider' ),
						'add_new' => __( 'Add New', 'redmosquito-slider' ),
						'add_new_item' => __( 'Add New Slider', 'redmosquito-slider' ),
						'edit_item' => __( 'Edit Slider', 'redmosquito-slider' ),
						'new_item' => __( 'New Slider', 'redmosquito-slider' ),
						'view_item' => __( 'View Slider', 'redmosquito-slider' ),
						'view_items' => __( 'View Sliders', 'redmosquito-slider' ),
						'search_items' => __( 'Search Sliders', 'redmosquito-slider' ),
						'not_found' => __( 'No Sliders found', 'redmosquito-slider' ),
						'not_found_in_trash' => __( 'No Sliders found in trash', 'redmosquito-slider' ),
						'parent_item_colon' => __( 'Parent Slider', 'redmosquito-slider' ),
						'all_items' => __( 'All Sliders', 'redmosquito-slider' ),
						'archives' => __( 'Slider Archives', 'redmosquito-slider' ),
						'attributes' => __( 'Slider Attributes', 'redmosquito-slider' ),
						'insert_into_item' => __( 'Insert into Slider', 'redmosquito-slider' ),
						'uploaded_to_this_item' => __( 'Uploaded to this Slider', 'redmosquito-slider' ),
						'featured_image' => __( 'Featured image', 'redmosquito-slider' ),
						'set_featured_image' => __( 'Set featured image', 'redmosquito-slider' ),
						'remove_featured_image' => __( 'Remove featured image', 'redmosquito-slider' ),
						'use_featured_image' => __( 'Use as featured image', 'redmosquito-slider' ),
						'menu_name' => __( 'Sliders', 'redmosquito-slider' ),
						'filter_items_list' => __( 'Filter Sliders list', 'redmosquito-slider' ),
						'filter_by_date' => __( 'Filter by date', 'redmosquito-slider' ),
						'items_list_navigation' => __( 'Sliders list navigation', 'redmosquito-slider' ),
						'items_list' => __( 'Sliders list', 'redmosquito-slider' ),
						'item_published' => __( 'Slider published', 'redmosquito-slider' ),
						'item_published_privately' => __( 'Slider published privately', 'redmosquito-slider' ),
						'item_reverted_to_draft' => __( 'Slider reverted to draft', 'redmosquito-slider' ),
						'item_scheduled' => __( 'Slider scheduled', 'redmosquito-slider' ),
						'item_updated' => __( 'Slider updated', 'redmosquito-slider' ),
					),
					'description' => __( 'Slider post type', 'redmosquito-slider' ),
					'public' => true,
					'hierarchical' => false,
					'exclude_from_search' => true,
					'publicly_queryable' => false,
					'show_ui' => true,
					'show_in_menu' => true,
					'show_in_nav_menus' => false,
					'show_in_admin_bar' => true,
					'show_in_rest' => true,
					'menu_position' => 56,
					'menu_icon' => 'dashicons-media-interactive',
					'taxonomies' => array(),
					'has_archive' => false,
					'can_export' => true,
					'delete_with_user' => false,
				)
			);
			remove_post_type_support( 'redsli_slider', 'editor' );
			add_post_type_support( 'redsli_slider', 'thumbnail' );
			register_taxonomy(
				'redsli_slider_category',
				'redsli_slider',
				array(
					'labels' => array(
						'name' => __( 'Slider Categories', 'redmosquito-slider' ),
						'singular_name' => __( 'Slider Category', 'redmosquito-slider' ),
						'search_items' => __( 'Search Slider Categories', 'redmosquito-slider' ),
						'popular_items' => __( 'Popular Slider Categories', 'redmosquito-slider' ),
						'all_items' => __( 'All Slider Categories', 'redmosquito-slider' ),
						'parent_item' => __( 'Parent Slider Category', 'redmosquito-slider' ),
						'parent_item_colon' => __( 'Parent Slider Category:', 'redmosquito-slider' ),
						'edit_item' => __( 'Edit Slider Category', 'redmosquito-slider' ),
						'view_item' => __( 'View Slider Category', 'redmosquito-slider' ),
						'update_item' => __( 'Update Slider Category', 'redmosquito-slider' ),
						'add_new_item' => __( 'Add New Slider Category', 'redmosquito-slider' ),
						'new_item_name' => __( 'New Slider Category Name', 'redmosquito-slider' ),
						'separate_items_with_commas' => __( 'Separate Slider Categories with commas', 'redmosquito-slider' ),
						'add_or_remove_items' => __( 'Add or remove Slider categories', 'redmosquito-slider' ),
						'choose_from_most_used' => __( 'Choose from the most used Slider categories', 'redmosquito-slider' ),
						'not_found' => __( 'No Slider categories found', 'redmosquito-slider' ),
						'no_terms' => __( 'No Slider categories', 'redmosquito-slider' ),
						'filter_by_item' => __( 'Filter by Slider category', 'redmosquito-slider' ),
					),
					'description' => __( 'Slider Categories', 'redmosquito-slider' ),
					'public' => false,
					'publicly_queryable' => false,
					'hierarchical' => false,
					'show_ui' => true,
					'show_in_menu' => true,
					'show_in_nav_menus' => false,
					'show_in_rest' => true,
					'show_tagcloud' => false,
					'show_in_quick_edit' => true,
					'show_admin_column' => true,
				)
			);
		}

		/**
		 * Add metabox support for custom post type.
		 */
		public function add_metabox_support() {
			include_once REDSLI_ABSPATH . '/includes/class-redslimetabox.php';
			new RedSliMetabox(
				'redsli_slider_metabox',
				array(
					array(
						'label' => __( 'Slider content', 'redmosquito-slider' ),
						'desc'  => __( 'Content of the slider', 'redmosquito-slider' ),
						'id'    => 'redsli_slider_content',
						'type'  => 'textarea',
					),
				),
				'redsli_slider',
				__( 'Slider settings', 'redmosquito-slider' )
			);
		}

		/**
		 * Add actions and filters.
		 */
		private function actions_and_filters() {
			add_action( 'after_setup_theme', array( $this, 'pluggable' ) );
			add_action( 'init', array( $this, 'init' ) );
			add_action( 'init', array( $this, 'add_post_type' ) );
			add_action( 'redmosquito_slider_init', array( $this, 'add_metabox_support' ) );
			add_action( 'wp_footer', array( $this, 'localize_slider_script' ) );
		}

		/**
		 * Add the shortcode for the slider.
		 */
		public function add_shortcodes() {
			add_shortcode( 'redsli_slider', array( $this, 'do_shortcode' ) );
		}

		/**
		 * Localize the slider script so that the sliders activate.
		 */
		public function localize_slider_script() {
			if ( count( $this->sliders ) > 0 ) {
				include_once REDSLI_ABSPATH . 'includes/class-redslislider.php';
				RedSliSlider::localize_swiper_activation( $this->sliders );
			}
		}

		/**
		 * Do the shortcode for the slider.
		 *
		 * @param $atts
		 * @return false|string
		 */
		public function do_shortcode( $atts ) {
			if ( gettype( $atts ) != 'array' ) {
				$atts = array();
			}

			include_once REDSLI_ABSPATH . 'includes/class-redslislider.php';
			$shortcode = new RedSliSlider( $atts );
			$this->sliders[] = $shortcode;
			return $shortcode->do_shortcode();
		}
	}
}
