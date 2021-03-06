<?php

class Ambassador_Theme {

	protected static $single_instance = null;

	static function init() {

		if ( self::$single_instance === null ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;

	}

	public function __construct() {

		if ( ! class_exists('CMB2_Bootstrap_220') ) {
			include_once 'includes/third-party/CMB2/init.php';
		}

		include_once 'includes/helpers.php';
		include_once 'includes/post-types/sections.php';
		include_once 'includes/post-types/templates.php';
		include_once 'includes/extensions/woo-stripe/woo-stripe.php';

	}

	public function hooks() {

		add_action( 'wp_enqueue_scripts', array( $this, 'load_styles_and_scripts' ) );
		add_action( 'wp_head', array( $this, 'live_css' ) );
		add_action( 'init', array( $this, 'setup_amabassador_theme' ) );

		add_action( 'show_ambassador_breadcrumb', array( $this, 'show_breadcrumb' ) );

		add_action( 'admin_menu', array( $this, 'add_branding_menu' ) );
		add_action( 'cmb2_admin_init', array( $this, 'layout_page_settings' ) );
		add_action( 'cmb2_admin_init', array( $this, 'brand_settings' ) );
		add_action( 'cmb2_admin_init', array( $this, 'landing_page_settings' ) );

		add_filter( 'template_include', array( $this, 'show_landing_page' ), 10, 1 );

	}

	public function load_styles_and_scripts() {

		wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css' );
		wp_enqueue_style( 'google-font', 'https://fonts.googleapis.com/css?family=Archivo+Black' );
		wp_enqueue_style( 'brand-new', get_stylesheet_uri() );

	}

	public function setup_amabassador_theme() {

		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'woocommerce' );

		$this->install_pages();

		register_nav_menus( array(
			'top-main-menu' 		=> __( 'Top Main Menu' ),
			'sidebar-main-menu' 	=> __( 'Sidebar Main Menu' ),
			'sidebar-store-menu' 	=> __( 'Sidebar Store Menu' ),
		) );

		$landing_page_options = get_option( 'landing-page' );
		$show_landing_page = isset( $landing_page_options['_ambassador_show_landing_page'] ) ? $landing_page_options['_ambassador_show_landing_page'] : null;

		if ( is_null( $show_landing_page ) ) {
			$landing_page_options['_ambassador_show_landing_page'] = 1;
			update_option( 'landing-page', $landing_page_options );
		}

	}

	public function live_css() {

		$primary_color = get_brand_option('primary_color');
		$secondary_color = get_brand_option('secondary_color');
		$accent_color = get_brand_option('accent_color');
		$base_color = get_brand_option('base_color');

		$css = '<style>';
		$css .= "div.primary-color { background-color: $primary_color; }";
		$css .= "p.primary-color { background-color: $primary_color; }";
		$css .= "div.secondary-color { background-color: $secondary_color; }";
		$css .= ".button.primary-color { background-color: $primary_color; }";
		$css .= ".button.secondary-color { background-color: $secondary_color; }";
		$css .= ".button.accent-color { background-color: $accent_color; }";
		$css .= ".button.base-color { background-color: $base_color; }";
		$css .= '</style>';

		echo $css;

	}

	public function add_branding_menu() {

		add_theme_page( 'Layout', 'Layout', 'manage_options', 'layout-settings', array( $this, 'layout_settings_page' ) );
		add_theme_page( 'Branding', 'Branding', 'manage_options', 'brand', array( $this, 'brand_settings_page' ) );
		add_theme_page( 'Landing Page', 'Landing Page', 'manage_options', 'landing-page', array( $this, 'show_landing_page_settings' ) );

	}

	public function layout_page_settings() {

		$layout_page_options = new_cmb2_box( array(
			'id'      => 'layout_options_metabox',
			'title'   => __( 'Layout Options', 'ambassador' ),
			'hookup'     => false,
			'show_on'    => array(
				'key'   => 'options-page',
				'value' => array( 'layout-settings' )
			),
		) );

		$layout_page_options->add_field( array(
			'name'    => __( 'Header', 'ambassador' ),
			'id'      => 'header_title',
			'type'    => 'title',
		) );

		$layout_page_options->add_field( array(
			'name'    => __( 'Header Style', 'ambassador' ),
			'id'      => 'header_style',
			'type'    => 'select',
			'options' => array(
				'centered' => 'Centered',
				'left-sided' => 'Left Sided'
			)
		) );

		$layout_page_options->add_field( array(
			'name'    => __( 'Header Background Color', 'ambassador' ),
			'id'      => 'header_background_color',
			'type'    => 'colorpicker',
		) );

		$layout_page_options->add_field( array(
			'name'    => __( 'Home Page Sections', 'ambassador' ),
			'id'      => 'home_page_sections_title',
			'type'    => 'title',
		) );

		$sections_group_field_id = $layout_page_options->add_field( array(
			'id'          => 'sections',
			'type'        => 'group',
			'description' => __( 'Controls which sections display on the homepage', 'ambassador' ),
			'options'     => array(
				'group_title'   => __( 'Section {#}', 'amabassador' ),
				'add_button'    => __( 'Add Another Section', 'ambassador' ),
				'remove_button' => __( 'Remove Section', 'ambassador' ),
				'sortable'      => true, // beta
			),
		) );

		$layout_page_options->add_group_field( $sections_group_field_id, array(
			'name'       => __( 'Section', 'ambassador' ),
			'id'         => 'section',
			'type'       => 'section_select',
		) );

		$layout_page_options->add_field( array(
			'name'    => __( 'Footer', 'ambassador' ),
			'id'      => 'footer_title',
			'type'    => 'title',
		) );

	}

	public function brand_settings() {

		// Start with an underscore to hide fields from custom fields list
		$option_key = '_ambassador_';

		$branding_options = new_cmb2_box( array(
			'id'      => 'brand_options_metabox',
			'title'   => __( 'Theme Options Metabox', 'cmb2' ),
			'hookup'     => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( 'brand' )
			),
		) );

		$branding_options->add_field( array(
			'name' => __( 'Logo', 'ambassador' ),
			'desc' => __( 'Upload an image or enter a URL.', 'amabassador' ),
			'id'   => 'logo',
			'type' => 'file',
		) );

		$branding_options->add_field( array(
			'name' => __( 'Site Icon', 'ambassador' ),
			'id'   => 'site_icon',
			'type' => 'file',
		) );

		$branding_options->add_field( array(
			'name'    => __( 'Site Summary', 'ambassador' ),
			'id'      => 'site_summary',
			'type'    => 'textarea_small',
		) );

		$branding_options->add_field( array(
			'name'    => __( 'Site Description', 'ambassador' ),
			'id'      => 'site_description',
			'type'    => 'textarea_small',
		) );

		$branding_options->add_field( array(
			'name'    => __( 'Primary Brand Color', 'ambassador' ),
			'id'      => 'primary_color',
			'type'    => 'colorpicker',
			'default' => '#ffffff',
		) );

		$branding_options->add_field( array(
			'name'    => __( 'Secondary Brand Color', 'ambassador' ),
			'id'      => 'secondary_color',
			'type'    => 'colorpicker',
			'default' => '#ffffff',
		) );

		$branding_options->add_field( array(
			'name'    => __( 'Accent Brand Color', 'ambassador' ),
			'id'      => 'accent_color',
			'type'    => 'colorpicker',
			'default' => '#ffffff',
		) );

		$branding_options->add_field( array(
			'name'    => __( 'Base Brand Color', 'ambassador' ),
			'id'      => 'base_color',
			'type'    => 'colorpicker',
			'default' => '#ffffff',
		) );

		$branding_options->add_field( array(
			'name'    => __( 'Footer Style', 'ambassador' ),
			'id'      => 'footer_style',
			'type'    => 'title',
		) );

		$branding_options->add_field( array(
			'name'    => __( 'Contact Methods', 'ambassador' ),
			'id'      => 'contact_methods_title',
			'type'    => 'title',
		) );

		$branding_options->add_field( array(
			'name'    	=> __( 'Phone Number', 'ambassador' ),
			'id'      	=> $option_key . 'social_phone',
			'type'    	=> 'text_medium',
		) );

		$branding_options->add_field( array(
			'name'    	=> __( 'Main Email', 'ambassador' ),
			'id'      	=> 'email',
			'type'    	=> 'text_email',
		) );

		$branding_options->add_field( array(
			'name'    	=> __( 'Instagram Name', 'ambassador' ),
			'id'      	=> $option_key . 'social_instagram',
			'type'    	=> 'text_medium',
		) );

		$branding_options->add_field( array(
			'name'    	=> __( 'Twitter Name', 'ambassador' ),
			'id'      	=> $option_key . 'social_twitter',
			'type'    	=> 'text_medium',
		) );

		$branding_options->add_field( array(
			'name'    	=> __( 'Facebook Address', 'ambassador' ),
			'id'      	=> $option_key . 'social_facebook',
			'type'    	=> 'text_medium',
		) );

		$branding_options->add_field( array(
			'name'    	=> __( 'Business Address', 'ambassador' ),
			'id'      	=> $option_key . 'social_address',
			'type'    	=> 'textarea_small',
		) );



	}

	public function get_page_permalinks() {

		$pages = get_pages();
		$permalinks = array( '' => '--' );

		foreach ( $pages as $page ) {
			$permalink = get_permalink( $page->ID );
			$permalinks[ $permalink ] = $page->post_title;
		}

		return $permalinks;

	}

	public function landing_page_settings() {

		// Start with an underscore to hide fields from custom fields list
		$option_key = '_ambassador_';

		$landing_page_options = new_cmb2_box( array(
			'id'      => 'landing_page_options_metabox',
			'title'   => __( 'Landing Page Options', 'ambassador' ),
			'hookup'     => false,
			'show_on'    => array(
				'key'   => 'options-page',
				'value' => array( 'landing-page' )
			),
		) );

		$landing_page_options->add_field( array(
			'name'    	=> __( 'Show Landing Page', 'ambassador' ),
			'id'      	=> $option_key . 'show_landing_page',
			'type'    	=> 'select',
			'desc'		=> __( 'Controls whether or not you want the landing page to display. To edit, view "Landing" page', 'ambassador' ),
			'options'	=> array(
				'' 		=> '--',
				'1'	=> __( 'Yes', 'ambassador' ),
				'0'	=> __( 'No', 'ambassador' ),
			),
		) );

	}

	public function get_homepage_posts( $post_types = array() ) {

		$args = array(
			'public'	=> true,
			'_builtin'	=> false
		);

		$post_types = get_post_types( $args, 'names' );

		$post_types['post'] = 'post';

		return $post_types;

	}

	public function layout_settings_page() {
		?>
		<div class="wrap cmb2-options-page layout">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( 'layout_options_metabox', 'layout-settings' ); ?>
		</div>
		<?php
	}

	public function brand_settings_page() {
		?>
		<div class="wrap cmb2-options-page brand">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( 'brand_options_metabox', 'brand' ); ?>
		</div>
		<?php
	}

	public function show_landing_page_settings() {
		?>
		<div class="wrap cmb2-options-page landing-page">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( 'landing_page_options_metabox', 'landing-page' ); ?>
		</div>
		<?php
	}

	public function install_pages() {

		$pages = array(
			'blog' => __( 'Blog', 'ambassador' ),
		);

		foreach ( $pages as $page => $page_title ) {

			$page_exists = get_page_by_title( $page_title, OBJECT, 'page' );

			if ( $page_exists ) {
				continue;
			}

			$page_args = array(
				'post_type'		=> 'page',
				'post_title'	=> $page_title,
				'post_name'		=> $page,
				'post_status'	=> 'publish'
			);

			$page_id = wp_insert_post( $page_args );

		}

	}

	public function show_breadcrumb() {

		global $wp;

		$urls_parts = explode( '/', $wp->request );

		$breadcrumbs = array(
			home_url() => __( 'Home', 'ambassador' ),
		);

		// Output the Home link
		echo '<a class="crumb" href="' . home_url() . '">' . __( 'Home', 'ambassador' ) . '</a>';

		if ( is_singular('post') ) {
			echo '<span class="divider">/</span><a class="crumb" href="' . home_url() . '">' . __( 'Blog', 'ambassador' ) . '</a>';
		}

		if ( $urls_parts[0] ) {

			$crumbs = '';

			foreach ( $urls_parts as $part ) {

				$crumbs .= '/' . $part;

				$part = str_replace( '-', ' ', $part );
				echo '<span class="divider">/</span><a class="crumb" href="' . home_url( $crumbs ) . '">' . ucwords( $part ) . '</a>';

			}

		}

	}

	public function show_landing_page( $template ) {

		$landing_page_options = get_option( 'landing-page' );

		$show_landing_page = isset( $landing_page_options['_ambassador_show_landing_page'] ) ? $landing_page_options['_ambassador_show_landing_page'] : false;

		if ( $show_landing_page && ! is_user_logged_in() ) {
			return get_template_directory() . '/templates/landing.php';
		}

		return $template;

	}




}

add_action( 'after_setup_theme', array( Ambassador_Theme::init(), 'hooks' ) );
