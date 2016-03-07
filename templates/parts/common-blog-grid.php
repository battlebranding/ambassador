<?php if ( have_posts() ): ?>
<div class="blog-grid">
	<?php while ( have_posts() ) : the_post(); ?>
		<div class="post-wrapper width-percent-25">
			<div class="post">
				<div class="content">
					<span class="post-title"><a href="<?php the_permalink(); ?> "><?php the_title(); ?></a></span>
					<?php the_excerpt(); ?>
					<a class="button read-more" href="<?php the_permalink(); ?> ">Read More</a>
					<p class="post-published">Posted <span class="post-date"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?></span> at <span class="post-time"><?php the_time('gA'); ?></span></p>
				</div>
			</div>
		</div>
	<?php endwhile; ?>
</div>
<?php endif; ?>