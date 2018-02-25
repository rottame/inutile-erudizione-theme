<?php
/**
 * The template for displaying Archive page.
 *
 * @package ThemeGrill
 * @subpackage ColorMag
 * @since ColorMag 1.0
 */

get_header(); ?>
	<?php do_action( 'colormag_before_body_content' ); ?>
	<div id="primary">
		<div id="content" class="clearfix">
			<?php if ( have_posts() ) : ?>
				<header class="page-header">
					<?php
						if ( is_category() ) {
					?>
					<h1 class="page-title category-title">
						<span>
							<?php
								echo filter_category(single_cat_title('', false));
							?>
						</span>
					</h1>
					<?php
						} else {
					?>
					<h1 class="page-title">
						<span>
						<?php
							if ( is_tag() ) {
								single_tag_title();
							} elseif ( is_author() ) {
								/* Queue the first post, that way we know
								 * what author we're dealing with (if that is the case).
								*/
								the_post();
								printf( __( 'Author: %s', 'colormag' ), '<span class="vcard">' . get_the_author() . '</span>' );
								/* Since we called the_post() above, we need to
								 * rewind the loop back to the beginning that way
								 * we can run the loop properly, in full.
								 */
								rewind_posts();
							} elseif ( is_day() ) {
								printf( __( 'Day: %s', 'colormag' ), '<span>' . get_the_date() . '</span>' );
							} elseif ( is_month() ) {
								printf( __( 'Month: %s', 'colormag' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );
							} elseif ( is_year() ) {
								printf( __( 'Year: %s', 'colormag' ), '<span>' . get_the_date( 'Y' ) . '</span>' );
							} elseif ( is_tax( 'post_format', 'post-format-aside' ) ) {
								_e( 'Asides', 'colormag' );
							} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
								_e( 'Images', 'colormag');
							} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
								_e( 'Videos', 'colormag' );
							} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
								_e( 'Quotes', 'colormag' );
							} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
								_e( 'Links', 'colormag' );
							} elseif ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
									woocommerce_page_title( false );
							} else {
								_e( 'Archives', 'colormag' );
							}
						}
					?>
						</span>
					</h1>
					<?php
						// Show an optional term description.
						$term_description = term_description();
						if ( ! empty( $term_description ) ) {
							printf( '<div class="taxonomy-description">%s</div>', $term_description );
						}
					?>
				</header><!-- .page-header -->

        <div class="article-container">
   				<?php
						global $post_i; $post_i = 1;
						global $post_count; if(empty($post_count)) $post_count = 0;
   					while ( have_posts() ) {
							the_post();
   						get_template_part( 'content', 'archive' );
							$post_count++;
   					}
					?>
        </div>
				<?php get_template_part( 'navigation', 'archive' ); ?>
			<?php else : ?>
				<?php get_template_part( 'no-results', 'archive' ); ?>
			<?php endif; ?>
		</div><!-- #content -->
	</div><!-- #primary -->
	<div class="hr style2"><span></span><span></span><span></span></div>
	<?php colormag_sidebar_select(); ?>
	<?php do_action( 'colormag_after_body_content' ); ?>
<?php get_footer(); ?>
