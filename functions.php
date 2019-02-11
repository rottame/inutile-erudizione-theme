<?php
define( 'COLORMAG_PARENT_DIR', get_template_directory() );
define( 'COLORMAG_INCLUDES_DIR', COLORMAG_PARENT_DIR . '/inc' );
define( 'COLORMAG_WIDGETS_DIR', COLORMAG_INCLUDES_DIR . '/widgets' );

require 'inc/header.php';
require 'inc/filters.php';
require 'widgets/widgets.php';

define('CAT_PRESENTAZIONI_ID', 198);

function add_separator() {
  echo '<div class="hr style2"><span></span><span></span><span></span></div>';
}
add_action('colormag_after_header', 'add_separator', 10, 2);


//function enqueue_parent_theme_style() {
//  wp_enqueue_style('parent-style', get_template_directory_uri().'/style.css');
//}
//add_action( 'wp_enqueue_scripts', 'enqueue_parent_theme_style' );

function palate_exclude_category($query) {
  if(is_admin()) {
    return;
  }

  if (!$query->is_main_query()) {
    return;
  }
  $excluded_categories = array(CAT_PRESENTAZIONI_ID);
  if ($query->is_singular){
    return;
  }
  if (is_category($excluded_categories)) {
    return;
  }

  $query->set( 'category__not_in', $excluded_categories );
}
add_action( 'pre_get_posts', 'palate_exclude_category' );
