<?php
/**
 * Template Name: Blog Archive
 */
?>
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
			<?php do_action( 'after_show_brand_header' ); ?>
			<section id="content">
				<div class="page content">
					<?php $query = new WP_Query( array( 'post_type' => 'post' ) ); ?>
					<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
						<article class="post-wrapper">
							<div class="post">
								<?php if ( has_post_thumbnail() ): ?>
									<div class="featured-image image-wrapper wide"><?php echo the_post_thumbnail(); ?></div>
								<?php endif; ?>
								<div class="content">
									<p class="post-published">Posted <span class="post-date"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?></span> at <span class="post-time"><?php the_time('gA'); ?></span></p>
									<h2><?php the_title(); ?></h2>
									<?php the_excerpt(); ?>
									<a class="button read-more accent-color small" href="<?php the_permalink(); ?> ">Read More</a>
								</div>
							</div>
						</article>
					<?php endwhile; endif; ?>
				</div>
			</section>
		</div>
		<?php get_template_part( 'templates/parts/common', 'footer' ); ?>
		<?php wp_footer(); ?>
	</body>
</html>