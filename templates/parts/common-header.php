<?php
	$brand_options = get_option( 'brand' );
	$logo_url = isset( $brand_options['logo'] ) ? $brand_options['logo'] : '';
	$logo = ( $logo_url ) ? '<img src="' . $logo_url . '" />' : show_brand_name();
?>
<header>
	<section class="align-text-center">
		<div>
			<h1 class="brand-name"><a href="<?php echo home_url(); ?>"><?php echo $logo; ?></a></h1>
		</div>
		<?php
			wp_nav_menu( array(
				'theme_location' => 'top-main-menu',
				'container' => 'nav',
				'container_class' => 'align-text-center',
				'menu_class'	=> 'menu horizontal',
			) );
		?>
	</section>
</header>