<?php

class Ambassador_Theme_Sections {

	protected static $single_instance = null;

	static function init() {

		if ( self::$single_instance === null ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;

	}

	public function hooks() {

		add_action( 'init', array( $this, 'init_section_post_type' ) );
		add_action( 'cmb2_admin_init', array( $this, 'init_section_metabox' ) );
		add_action( 'cmb2_render_section_select', array( $this, 'cmb2_render_section_select' ), 10, 5 );
		add_action( 'show_sections', array( $this, 'show_sections' ) );

		add_action( 'show_hero_section', array( $this, 'show_hero_section' ) );
		add_action( 'show_blog_grid_section', array( $this, 'show_blog_grid_section' ) );

	}

	public function init_section_post_type() {

	    $args = array(
			'public' 	=> true,
			'label'  	=> 'Sections',
			'menu_icon'	=> 'dashicons-exerpt-view',
			'supports'	=> array( 'title' )
	    );

	    register_post_type( 'section', $args );

	}

	public function init_section_metabox() {

		$prefix = '_ambassador_section_';

		$section_metabox = new_cmb2_box( array(
			'id'            => 'section_metabox',
			'title'         => __( 'Section Settings', 'ambassador' ),
			'object_types'  => array( 'section' ),
			'show_names' => true,
		) );

		$section_metabox->add_field( array(
			'name'             => __( 'Type', 'ambassador' ),
			'desc'             => __( 'Select the type of section you want to create', 'ambassador' ),
			'id'               => $prefix . 'type',
			'type'             => 'select',
			'show_option_none' => true,
			'options'          => apply_filters( 'ambassador_section_types', array(
				'hero' 		=> __( 'Hero', 'ambassador' ),
				'blog_grid' => __( 'Blog Grid', 'ambassador' ),
			) ),
		) );

		$section_metabox->add_field( array(
			'name' => __( 'Display Title', 'ambassador' ),
			'id'   => $prefix . 'title',
			'type' => 'text',
		) );

		$section_metabox->add_field( array(
			'name'			=> __( 'Content', 'ambassador' ),
			'desc' 			=> __( 'Adds a message to the section if supported (optional)', 'ambassador' ),
			'id'   			=> $prefix . 'content',
			'type' 			=> 'wysiwyg',
			'sanitization_cb' => false,
			'options' 		=> array( 'textarea_rows' => 5, ),
			'show_on_cb'	=> array( $this, 'is_hero_section' ),
		) );

		$section_metabox->add_field( array(
			'name' => __( 'Content Color', 'ambassador' ),
			'id'   => $prefix . 'content_color',
			'type' => 'colorpicker',
		) );

		$section_metabox->add_field( array(
			'name' => __( 'Content Position', 'ambassador' ),
			'desc' => __( 'Changes the position of the message', 'ambassador' ),
			'id'   => $prefix . 'content_position',
			'type' => 'select',
			'options' => array(
				'top-left' => 'Top Left',
				'top-center' => 'Top Center',
				'top-right' => 'Top Right',
				'middle-left' => 'Middle Left',
				'middle-center' => 'Middle Center',
				'middle-right' => 'Middle Right',
				'bottom-left' => 'Bottom Left',
				'bottom-center' => 'Bottom Center',
				'bottom-right' => 'Bottom Right',
			),
			'show_on_cb'	=> array( $this, 'is_hero_section' ),
		) );

		$section_metabox->add_field( array(
			'name' => __( 'Number of Posts', 'ambassador' ),
			'id'   => $prefix . 'post_count',
			'type' => 'text_small',
			'show_on_cb'	=> array( $this, 'is_blog_grid_section' ),
		) );

		do_action( 'ambassador_section_fields', $section_metabox, $prefix );

		$section_metabox->add_field( array(
			'name' => __( 'Background', 'ambassador' ),
			'desc' => __( 'Upload an image, video or enter a URL.', 'ambassador' ),
			'id'   => $prefix . 'background',
			'type' => 'file',
		) );

		$section_metabox->add_field( array(
			'name' => __( 'Background Color', 'ambassador' ),
			'id'   => $prefix . 'background_color',
			'type' => 'colorpicker',
		) );

		$section_metabox->add_field( array(
			'name' => __( 'Show Background Overlay', 'ambassador' ),
			'id'   => $prefix . 'show_overlay',
			'type' => 'checkbox',
		) );

		$section_metabox->add_field( array(
			'name' => __( 'Show Link', 'ambassador' ),
			'desc' => __( 'Toggle to show a link button or not', 'ambassador' ),
			'id'   => $prefix . 'show_link',
			'type' => 'checkbox',
			'show_on_cb'	=> array( $this, 'is_hero_section' ),
		) );

		$section_metabox->add_field( array(
			'name' => __( 'Button Style', 'ambassador' ),
			'id'   => $prefix . 'button_style',
			'type' => 'select',
			'show_option_none' => true,
			'options' => array(
				'link' 		=> 'Link',
				'flat' 		=> 'Flat',
				'outlined'	=> 'Outlined'
			),
		) );

		$section_metabox->add_field( array(
			'name' => __( 'Link Label', 'ambassador' ),
			'desc' => __( 'Text on the hero button', 'ambassador' ),
			'id'   => $prefix . 'link_label',
			'type' => 'text_medium',
			'show_on_cb'	=> array( $this, 'is_hero_section' ),
		) );

		$section_metabox->add_field( array(
			'name' => __( 'Links To', 'ambassador' ),
			'desc' => __( 'Enter a link. Can be relative or absolute URL', 'ambassador' ),
			'id'   => $prefix . 'links_to',
			'type' => 'text',
			'show_on_cb'	=> array( $this, 'is_hero_section' ),
		) );

		$section_metabox->add_field( array(
			'name' => __( 'Section Height', 'ambassador' ),
			'desc' => __( 'Enter a number represented in pixels', 'ambassador' ),
			'id'   => $prefix . 'height',
			'type' => 'text_small',
		) );

	}

	public function cmb2_render_section_select(  $field, $escaped_value, $object_id, $object_type, $field_type_object ) {

		$section_list = $this->get_sections_for_select();
		$section_options = '<option value="">None</option>';

	    foreach ( $section_list as $section_id => $section_title ) {
	        $section_options .= '<option value="'. $section_id .'" '. selected( $escaped_value, $section_id, false ) .'>'. $section_title .'</option>';
	    }

		?>
		<div class="alignleft">
	        <?php echo $field_type_object->select( array(
	            'options' => $section_options,
	        ) ); ?>
	    </div>
		<?php
	}

	public function get_sections_for_select() {

		$args = array(
			'post_type'   => 'section',
			'posts_per_page'	=> -1,
		);

		$sections = get_posts( $args );

		$section_options = array();

	    if ( $sections ) {

	        foreach ( $sections as $section ) {
	        	$section_options[ $section->ID ] = $section->post_title;
	        }

	    }

		return $section_options;

	}

	public function show_sections() {

		if ( is_home() ) {

			$home_settings = get_option( 'home-settings' );
			$home_sections = isset( $home_settings['sections'] ) ? $home_settings['sections'] : array();

			foreach ( $home_sections as $section ) {

				$section_id = $section['section'];
				$section_type = get_post_meta( $section_id, '_ambassador_section_type', true );

				do_action( 'show_' . $section_type . '_section', $section_id );

			}

		}

	}

	public function show_hero_section( $section_id ) {

		$height = get_post_meta( $section_id, '_ambassador_section_height', true );
		$content = get_post_meta( $section_id, '_ambassador_section_content', true );
		$content_color = get_post_meta( $section_id, '_ambassador_section_content_color', true );

		$background_url = get_post_meta( $section_id, '_ambassador_section_background', true );
		$background_color = get_post_meta( $section_id, '_ambassador_section_background_color', true );

		$show_link = get_post_meta( $section_id, '_ambassador_section_show_link', true );
		$show_link = ( 'on' == $show_link ) ? true : false;
		$link_label = get_post_meta( $section_id, '_ambassador_section_link_label', true );
		$section_link = get_post_meta( $section_id, '_ambassador_section_links_to', true );

		$button_html = sprintf( '<a class="button primary-color" href="%s">%s</a>', $section_link, $link_label );

		$html = '<section class="hero align-text-center" style="height: ' . $height . 'px;">';

		$html .= '<div class="content" style="color: ' . $content_color . ';">';
		$html .= wpautop( $content );
		$html .= ( $show_link ) ? $button_html : '';
		$html .= '</div>';

		$html .= '<div class="overlay"></div>';
		$html .= '<div class="background" style="background-image: url(' . $background_url . '); background-color: ' . $background_color . ';"></div>';
		$html .= "</section>";

		echo $html;

	}

	public function show_blog_grid_section( $section_id ) {

		$height = get_post_meta( $section_id, '_ambassador_section_height', true );
		$background_url = get_post_meta( $section_id, '_ambassador_section_background', true );
		$background_color = get_post_meta( $section_id, '_ambassador_section_background_color', true );
		$post_count = get_post_meta( $section_id, '_ambassador_section_post_count', true );
		$button_style = get_post_meta( $section_id, '_ambassador_section_button_style', true );

		$post_args = array(
			'post_type' => 'post',
			'posts_per_page' => ( $post_count ) ? $post_count : 3,
		);

		$query = new WP_Query( $post_args );
		$posts = get_posts( $post_args );

		$html = '<section class="blog-grid" style="min-height: ' . $height . 'px;">';
		$html .= '<div class="content">';

		if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();

			// foreach ( $posts as $post ) {

				$html .= '<div class="post width-percent-25">
					<h4>Posted <span class="post-date">' . human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago</span> at <span class="post-time">' . get_the_time('gA') . '</span></h4>
					<span class="post-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></span>
					<p>' . wp_trim_words( get_the_content(), 20 ) . '</p>
					<a class="button read-more accent-color small ' . $button_style . '" href="' . get_permalink() . '">Read More</a>
					<p class="post-published"></p>
				</div>';

			// }

		endwhile; endif;

		$html .= '</div>';
		$html .= '<div class="background" style="background-image: url(' . $background_url . '); background-color: ' . $background_color . ';"></div>';
		$html .= '</section>';

		echo $html;

	}

	public function is_hero_section( $cmb ) {

		$section_type = get_post_meta( $cmb->object_id, '_ambassador_section_type', true );

		if ( 'hero' == $section_type ) {
			return true;
		}

		return false;

	}

	public function is_blog_grid_section( $cmb ) {

		$section_type = get_post_meta( $cmb->object_id, '_ambassador_section_type', true );

		if ( 'blog_grid' == $section_type ) {
			return true;
		}

		return false;

	}
}

add_action( 'after_setup_theme', array( Ambassador_Theme_Sections::init(), 'hooks' ) );