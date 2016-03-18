<?php
	$brand_options = get_option( 'brand' );
	$logo_url = isset( $brand_options['logo'] ) ? $brand_options['logo'] : '';
	$logo = ( $logo_url ) ? '<img src="' . $logo_url . '" />' : '<h1>' . show_brand_name() . '</h1>';

	$layout_options = get_option( 'layout-settings' );
	$header_style = isset( $layout_options['header_style'] ) ? $layout_options['header_style'] : '';
	$header_background_color = isset( $layout_options['header_background_color'] ) ? $layout_options['header_background_color'] : '';

	if ( 'left-sided' == $header_style ) {
		$logo_classes = 'width-percent-25 align-text-left float-left';
		$nav_classes = 'width-percent-75 align-text-right float-right';
	} else {
		$logo_classes = '';
		$nav_classes = 'align-text-center';
	}
?>
<header>
	<section class="align-text-center">
		<div class="<?php echo $logo_classes; ?>">
			<h1 class="brand-name"><a href="<?php echo home_url(); ?>"><?php echo $logo; ?></a></h1>
		</div>
		<?php
			wp_nav_menu( array(
				'theme_location' => 'top-main-menu',
				'container' => 'nav',
				'container_class' => $nav_classes,
				'menu_class'	=> 'menu horizontal',
			) );
		?>
	</section>
	<div class="background" style="background-color: <?php echo $header_background_color; ?>"></div>
</header>