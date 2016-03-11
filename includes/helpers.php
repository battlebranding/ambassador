<?php

function show_brand_name() {
	echo get_bloginfo();
}

function show_brand_email() {
	echo get_brand_option('email');
}

function show_brand_position() {
	echo get_brand_option('brand_position');
}

function show_site_summary() {
	echo get_brand_option('site_summary');
}

function show_site_description() {
	echo get_brand_option('site_description');
}

function get_brand_billboard_link() {
	return get_brand_option('billboard_link');
}

function get_site_icon() {
	return get_brand_option('site_icon');
}

function get_brand_option( $option_name = '' ) {

	$brand_options = get_option( 'brand' );
	$brand_option = '';

	if ( $brand_options ) {

		$brand_option = isset( $brand_options[ $option_name ] ) ? $brand_options[ $option_name ] : '';

	}

	return $brand_option;

}