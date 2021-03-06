<!DOCTYPE html>
<html>
	<head>
		<title>Events | <?php show_brand_name(); ?> | <?php show_site_summary(); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
		<meta property="og:url" content="<?php echo home_url('events/'); ?>" />
		<meta property="og:title" content="Events | <?php show_brand_name(); ?> | <?php show_site_summary(); ?>" />
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
					<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
						<div class="post event event-single calendar-item">
							<div class="calendar-entry primary-color">
								<span class="day-of-week-label"><?php brand_show_event_day_of_week( $post->ID ); ?></span>
								<span class="date-label"><?php brand_show_event_day( $post->ID ); ?></span>
								<span class="month-label"><?php brand_show_event_month( $post->ID ); ?></span>
							</div>
							<?php if ( has_post_thumbnail() ): ?>
								<div class="featured-image image-wrapper wide"><?php echo the_post_thumbnail(); ?></div>
							<?php endif; ?>
							<div class="event-details">
								<span class="event-title"><a href="<?php the_permalink(); ?> "><?php the_title(); ?></a></span>
								<span class="event-location"><?php brand_show_event_location( $post->ID ); ?></span>
								<span class="event-date"><?php brand_show_event_date( $post->ID ); ?></span>
							</div>
							<a class="reveal-more" href="<?php the_permalink(); ?> ">
								<i class="fa fa-angle-right"></i>
							</a>
						</div>
					<?php endwhile; else: ?>
						<p>No events at this time.</p>
					<?php endif; ?>
				</div>
			</section>
		</div>
		<?php get_template_part( 'templates/parts/common', 'footer' ); ?>
		<?php wp_footer(); ?>
	</body>
</html>
