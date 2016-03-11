<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $post->post_title; ?> | <?php show_brand_name(); ?> | <?php show_site_summary(); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
		<meta property="og:url" content="<?php echo get_permalink( $post->ID ); ?>" />
		<meta property="og:title" content="<?php echo $post->post_title; ?> | <?php show_brand_name(); ?> | <?php show_site_summary(); ?>" />
		<meta property="og:description" content="<?php show_site_description(); ?>" />
		<meta property="og:image" content="<?php echo get_site_icon(); ?>" />
		<?php wp_head(); ?>
	</head>
	<body>
		<div id="wrapper">
			<?php do_action( 'before_show_brand_header' ); ?>
			<?php get_template_part( 'templates/parts/common', 'header' ); ?>
			<?php do_action( 'after_show_brand_header' ); ?>
			<section id="content">
				<div class="page content">
					<article class="post-wrapper">
						<div class="post">
							<div class="featured-image image-wrapper"></div>
							<div class="content">
								<h2><?php the_title(); ?></h2>
								<?php echo wpautop( $post->post_content ); ?>
							</div>
						</div>
					</article>
				</div>
			</section>
		</div>
		<?php get_template_part( 'templates/parts/common', 'footer' ); ?>
		<?php wp_footer(); ?>
	</body>
</html>