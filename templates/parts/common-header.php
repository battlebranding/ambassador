<header>
	<section>
		<div class="width-percent-50">
			<h1 class="brand-name"><a href="<?php echo home_url(); ?>"><?php show_brand_name(); ?></a></h1>
		</div>
		<?php
			wp_nav_menu( array(
				'theme_location' => 'top-main-menu',
				'container' => 'nav',
				'container_class' => 'width-percent-50 align-text-right',
				'menu_class'	=> 'menu horizontal',
			) );
		?>
	</section>
</header>