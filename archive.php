<!DOCTYPE html>
<html>
	<head>
		<title>Ambassador Theme</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
		<?php wp_head(); ?>
	</head>
	<body>
		<div id="wrapper">
			<div class="page">
				<div class="site-notice hide">
					<span>This is an alert. It conveys really important messages to your visitors <a href="#">Read More</a></span>
				</div>
				<header>
					<div class="branding">
						<h1><a href="<?php echo home_url(); ?>"><?php echo get_bloginfo(); ?></h1>
					</div>
					<nav class="contact-menu menu right">
						<ul>
							<li><a href="tel:13365552007">(336) 555-2007</a></li>
							<li><a href="http://instagram.com"><i class="fa fa-instagram"></i></a></li>
							<li><a href="http://facebook.com"><i class="fa fa-facebook"></i></a></li>
							<li><a href="http://twitter.com"><i class="fa fa-twitter"></i></a></li>
						</ul>
					</nav>
				</header>
				<div class="billboard">
					<div class="content">
						<p>Brand Position goes here. It’s your secret weapon.<br />Why your customers should pick you. Tell Them why.</p>
						<a class="button medium" href="#">Welcome</a>
					</div>
					<div class="overlay"></div>
				</div>
				<div id="content">
					<div id="feed">
						<div class="breadcrumb"><?php do_action( 'show_ambassador_breadcrumb' ); ?></div>
						<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
							<article class="post-wrapper">
								<div class="post">
									<?php if ( has_post_thumbnail() ): ?>
										<div class="featured-image image-wrapper wide"><?php echo the_post_thumbnail(); ?></div>
									<?php endif; ?>
									<div class="content">
										<h2><a href="<?php the_permalink(); ?> "><?php the_title(); ?></a></h2>
										<p><?php the_date(); ?></p>

										<a class="button read-more" href="<?php the_permalink(); ?> ">Read More</a>
									</div>
								</div>
								<div class="post-meta">
									<span class="right">View More <a href="<?php echo home_url('blog'); ?>">Posts</a></span>
								</div>
							</article>
						<?php endwhile; endif; ?>
					</div>
					<div id="sidebar">
						<h4 class="label">Menu</h4>
						<?php wp_nav_menu( array( 'theme_location' => 'sidebar-main-menu', 'container' => 'nav', 'container_class' => 'vertical' ) ); ?>
						<?php if ( get_theme_support( 'woocommerce' ) ): ?>
							<h4 class="label">Store Menu</h4>
							<?php wp_nav_menu( array( 'theme_location' => 'sidebar-store-menu', 'container' => 'nav', 'container_class' => 'woocommerce vertical' ) ); ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<footer>
			<section>
				<h3><?php echo get_bloginfo(); ?></h3>
			</section>
		</footer>
		<?php wp_footer(); ?>
	</body>
</html>
