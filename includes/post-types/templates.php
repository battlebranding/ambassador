<?php


class Ambassador_Theme_Templates {

	protected static $single_instance = null;

	static function init() {

		if ( self::$single_instance === null ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;

	}

	public function hooks() {
		add_action( 'init', array( $this, 'init_template_post_type' ) );
	}

	public function init_template_post_type() {

	    $args = array(
			'public' 	=> true,
			'label'  	=> 'Templates',
			'menu_icon'	=> 'dashicons-exerpt-view',
			'supports'	=> array( 'title' ),
			'menu_position' => 58
	    );

	    register_post_type( 'template', $args );

	}

}

add_action( 'after_setup_theme', array( Ambassador_Theme_Templates::init(), 'hooks' ) );