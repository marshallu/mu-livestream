<?php
/**
 * Default template for single livestream
 *
 * @package MU Livestream
 */

get_header();

get_template_part( 'template-parts/hero/no-hero' ); ?>

<div class="w-full xl:max-w-screen-xl px-6 lg:px-10 xl:px-0 xl:mx-auto pt-4 lg:pt-12 pb-16" id="content" tabindex="-1">
	<div>
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<header>
				<?php get_template_part( 'template-parts/page-title' ); ?>
			</header>
			<article <?php post_class( 'entry-content' ); ?> id="post-<?php the_ID(); ?>">
				<div class="yt relative h-0" style="padding-bottom: 56.25%;">
					<iframe class="absolute top-0 left-0 h-full w-full" src="https://vimeo.com/event/<?php echo esc_attr( get_field( 'mu_livestream_live_event_id' ) ); ?>/embed" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
</div>
<!-- Footer -->
<?php
get_footer();
