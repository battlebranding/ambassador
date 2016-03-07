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

				<?php if ( get_brand_option('brand_position') ): ?>
				<div class="billboard">
					<div class="content">
						<?php show_brand_position(); ?>
						<a class="button medium" href="<?php echo home_url('about'); ?>">Learn More</a>
					</div>
					<div class="overlay"></div>
					<div class="background" style="background-image: url(<?php echo get_brand_option( 'brand_position_background' ); ?>);"></div>
				</div>
				<?php endif; ?>

				<?php echo do_shortcode( '[upcoming_event]' ); ?>

				<?php get_template_part( 'templates/parts/common', 'blog-grid' ); ?>

			</div>
		</div>
		<?php get_template_part( 'templates/parts/common', 'footer' ); ?>
		<?php wp_footer(); ?>
	</body>
</html>
