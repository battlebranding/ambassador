<?php

function show_brand_name() {
	echo get_bloginfo();
}

function show_brand_email() {
	echo get_brand_option('email');
}

function show_brand_position() {
	echo sprintf( '<p>%s</p>', get_brand_option('brand_position') );
}

function show_brand_definition() {
	echo get_brand_option('brand_definition');
}

function get_brand_billboard_link() {
	return get_brand_option('billboard_link');
}

function get_brand_option( $option_name = '' ) {

	$brand_options = get_option( 'brand' );
	$brand_option = '';

	if ( $brand_options ) {

		$brand_option = isset( $brand_options[ $option_name ] ) ? $brand_options[ $option_name ] : '';

	}

	return $brand_option;

}