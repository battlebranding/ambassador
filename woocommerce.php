<!DOCTYPE html>
<html>
	<head>
		<title><?php show_brand_name(); ?> | <?php show_brand_definition(); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
		<?php wp_head(); ?>
	</head>
	<body>
		<div id="wrapper">
			<div class="page">
				<?php do_action( 'before_show_brand_header' ); ?>
				<?php get_template_part( 'templates/parts/common', 'header' ); ?>
				<?php do_action( 'after_show_brand_header' ); ?>

				<div id="content" class="woocommerce">
					<div id="feed">
						<div class="breadcrumb"><?php do_action( 'show_ambassador_breadcrumb' ); ?></div>
						<?php woocommerce_content(); ?>
					</div>
					<div id="sidebar">
						<?php if ( get_theme_support( 'woocommerce' ) ): ?>
							<h4 class="label">Store Menu</h4>
							<?php wp_nav_menu( array(
								'theme_location' => 'sidebar-store-menu',
								'container' => 'nav',
								'container_class' => 'woocommerce vertical woo-menu',
								'menu_class' => 'menu vertical'
								) );
								?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<?php get_template_part( 'templates/parts/common', 'footer' ); ?>
		<?php wp_footer(); ?>
	</body>
</html>