<?php
function colormag_middle_header_bar_display() {
  ?>
  <div class="inner-wrap">
    <div id="header-text-nav-wrap" class="clearfix">
      <div id="header-left-section">
        <?php
        if ( (get_theme_mod( 'colormag_header_logo_placement', 'header_text_only' ) == 'show_both' || get_theme_mod( 'colormag_header_logo_placement', 'header_text_only' ) == 'header_logo_only' ) ) {
          ?>
          <div id="header-logo-image">
            <?php if ( get_theme_mod( 'colormag_logo', '' ) != '' ) { ?>
              <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php echo esc_url( get_theme_mod( 'colormag_logo' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"></a>
            <?php } ?>
            <?php
            if ( function_exists( 'the_custom_logo' ) && has_custom_logo( $blog_id = 0 ) ) {
              colormag_the_custom_logo();
            }
            ?>
          </div><!-- #header-logo-image -->
          <?php
        }
        $screen_reader = '';
        if ( get_theme_mod( 'colormag_header_logo_placement', 'header_text_only' ) == 'header_logo_only' || (get_theme_mod( 'colormag_header_logo_placement', 'header_text_only' ) == 'disable' ) ) {
          $screen_reader = 'screen-reader-text';
        }
        ?>
        <div id="header-text" class="<?php echo $screen_reader; ?>">
          <?php if ( is_front_page() || is_home() ) : ?>
            <h1 id="site-title">
              <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php filter_blog_name(get_bloginfo( 'name', 'display' )); ?></a>
            </h1>
          <?php else : ?>
            <h3 id="site-title">
              <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php filter_blog_name(get_bloginfo( 'name', 'display' )); ?></a>
            </h3>
          <?php endif; ?>
          <?php
          $description = get_bloginfo( 'description', 'display' );
          if ( $description || is_customize_preview() ) :
            ?>
            <div class="hr style1"><span></span><span></span><span></span></div>
            <p id="site-description"><?php filter_description($description); ?></p>
          <?php endif; ?><!-- #site-description -->
        </div><!-- #header-text -->
      </div><!-- #header-left-section -->
      <div id="header-right-section">
        <?php
        if ( is_active_sidebar( 'colormag_header_sidebar' ) ) {
          ?>
          <div id="header-right-sidebar" class="clearfix">
            <?php
            // Calling the header sidebar if it exists.
            if ( ! dynamic_sidebar( 'colormag_header_sidebar' ) ):
            endif;
            ?>
          </div>
          <?php
        }
        ?>
      </div><!-- #header-right-section -->
    </div><!-- #header-text-nav-wrap -->
  </div><!-- .inner-wrap -->
  <?php
}
