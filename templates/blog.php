<?php
/**
 * Template Name: Blog Archive
 */
?>
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

				<div id="content">
					<div id="feed">
						<div class="breadcrumb"><?php do_action( 'show_ambassador_breadcrumb' ); ?></div>
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
										<a class="button read-more accent-color" href="<?php the_permalink(); ?> ">Read More</a>
									</div>
								</div>
							</article>
						<?php endwhile; endif; ?>
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