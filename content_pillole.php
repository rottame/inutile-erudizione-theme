<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package ThemeGrill
 * @subpackage ColorMag
 * @since ColorMag 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
   <?php do_action( 'colormag_before_post_content' ); ?>
   <?php global $post_count ?>
    <div class="pill-number">
      <a href="<?php the_permalink(); ?>">
        <span class="pillno">
          <?php if(!empty(pill_number(the_title( '', '', false )))): ?>
            §<?php echo pill_number(the_title( '', '', false )) ?>
          <?php else: ?>
            ∞
          <?php endif; ?>
        </span>
      </a>
    </div>

   <div class="article-content clearfix">

      <?php if( get_post_format() ) { get_template_part( 'inc/post-formats' ); } ?>

      <?php colormag_entry_meta(); ?>

      <div class="entry-content clearfix">
        <?php if($post_count == 0): ?>
        <?php the_content(); ?>
        <?php else: ?>
        <?php pill_excerpt(); ?>
        <?php endif; ?>

        <div class="read-more-link">
          <div class="the-link">
            <a class="more-link" title="<?php the_title(); ?>" href="<?php the_permalink(); ?>">
              <span></span>
              <?php _e( 'Read more', 'colormag' ); ?>
            </a>
          </div>
        </div>
      </div>

   </div>

   <?php do_action( 'colormag_after_post_content' ); ?>
</article>
