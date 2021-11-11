<?php
/**
 * Default template for single livestream
 *
 * @package MU Livestream
 */

use Carbon\Carbon;

get_header();

get_template_part( 'template-parts/hero/no-hero' ); ?>

<div class="w-full xl:max-w-screen-xl px-6 lg:px-10 xl:px-0 xl:mx-auto pt-4 lg:pt-12 pb-16" id="content" tabindex="-1">
	<div>
		<?php
		while ( have_posts() ) :
			the_post();

			if ( get_field( 'mu_livestream_chat' ) ) {
				$video_width = 'w-full lg:w-3/4';
			} else {
				$video_width = 'w-full';
			}
			?>
			<header>
				<?php get_template_part( 'template-parts/page-title' ); ?>
			</header>
			<article <?php post_class( 'entry-content' ); ?> id="post-<?php the_ID(); ?>">
			<?php
			if ( get_field( 'mu_livestream_archive_event_id' ) ) {
				?>
				<div class="yt relative h-0" style="padding-bottom: 56.25%;">
					<iframe class="absolute top-0 left-0 h-full w-full" src="https://player.vimeo.com/video/<?php echo esc_attr( get_field( 'mu_livestream_archive_event_id' ) ); ?>?h=7d8cbf925c" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
				</div>
				<?php
			} else {
				?>
				<div class="flex flex-wrap">
					<div class="<?php echo esc_attr( $video_width ); ?>">
						<div class="yt relative h-0" style="padding-bottom: 56.25%;">
							<iframe class="absolute top-0 left-0 h-full w-full" src="https://vimeo.com/event/<?php echo esc_attr( get_field( 'mu_livestream_live_event_id' ) ); ?>/embed" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
						</div>
					</div>
					<?php
					if ( get_field( 'mu_livestream_chat' ) ) {
						?>
						<div class="w-full lg:w-1/4">
							<iframe src="https://vimeo.com/event/<?php echo esc_attr( get_field( 'mu_livestream_live_event_id' ) ); ?>/chat/" class="w-full min-h-96 lg:h-full" frameborder="0"></iframe>
						</div>
					<?php } ?>
				</div>
			<?php } ?>

			</article>
		<?php endwhile; ?>
	</div>
</div>
<!-- Footer -->
<?php
get_footer();
