<?php
/**
 * Displays the searchform of the theme.
 *
 * @package ThemeGrill
 * @subpackage ColorMag
 * @since ColorMag 1.0
 */
?>
<form action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search-form searchform clearfix" method="get">
   <div class="search-wrap">
      <label for="#search-input"><?php esc_attr_e( 'Search', 'palate' ); ?></label>
      <input type="text" id="search-input" class="s field" name="s">
      <button class="search-icon" type="submit"></button>
   </div>
</form><!-- .searchform -->
