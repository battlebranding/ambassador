<!DOCTYPE html>
<html>
	<head>
		<title><?php show_brand_name(); ?> | <?php show_site_summary(); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
		<meta property="og:url" content="<?php echo home_url(); ?>" />
		<meta property="og:title" content="<?php show_brand_name(); ?> | <?php show_site_summary(); ?>" />
		<meta property="og:description" content="<?php show_site_description(); ?>" />
		<meta property="og:image" content="<?php echo get_site_icon(); ?>" />
		<?php wp_head(); ?>
	</head>
	<body>
		<div id="wrapper">
			<?php do_action( 'before_show_brand_header' ); ?>
			<?php get_template_part( 'templates/parts/common', 'header' ); ?>
			<?php do_action( 'show_sections_before_content' ); ?>
			<section class="page-title">
				<div class="content">
					<h2><?php echo $post->post_title; ?></h2>
				</div>
			</section>
			<section id="content">
				<div class="page content">
					<?php echo do_shortcode( wpautop( $post->post_content ) ); ?>
				</div>
			</section>
			<?php do_action( 'show_sections_after_content' ); ?>
		</div>
		<?php get_template_part( 'templates/parts/common', 'footer' ); ?>
		<?php wp_footer(); ?>
	</body>
</html>