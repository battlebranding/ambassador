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
						<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
							<article class="post-wrapper">
								<div class="post event event-single calendar-item">
									<div class="calendar-entry">
										<span class="day-of-week-label"><?php brand_show_event_day_of_week( $post->ID ); ?></span>
										<span class="date-label"><?php echo date('d'); ?></span>
										<span class="month-label"><?php echo date('M'); ?></span>
									</div>
									<?php if ( has_post_thumbnail() ): ?>
										<div class="featured-image image-wrapper wide"><?php echo the_post_thumbnail(); ?></div>
									<?php endif; ?>
									<div class="content">
										<span class="event-title"><a href="<?php the_permalink(); ?> "><?php the_title(); ?></a></span>
										<span class="event-location"><?php brand_show_event_location( $post->ID ); ?></span>
										<span class="event-date"><?php brand_show_event_date( $post->ID ); ?></span>
									</div>
									<a class="reveal-more" href="<?php the_permalink(); ?> ">
										<i class="fa fa-angle-right"></i>
									</a>
								</div>
							</article>

						<?php endwhile; else: ?>
							<p>No events at this time.</p>
						<?php endif; ?>
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
