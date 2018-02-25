<?php
require_once ABSPATH.'/wp-content/themes/colormag/inc/widgets/widgets.php';

add_action('widgets_init', 'palate_widgets_init');

function palate_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__('Front Page: News Area', 'palate'),
		'id'            => 'palate_news_area',
		'description'   => esc_html__('Show widget just below menu.', 'palate'),
		'before_widget' => '<section id="%1$s" class="widget %2$s clearfix">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );

  register_widget("palate_new_posts_widget");
  register_widget("palate_featured_posts_widget");
  register_widget("palate_highlighted_posts_widget");
  register_widget("palate_separator");
	register_widget("palate_category_link");
	register_widget("palate_category_presentation_widget");
}

require_once ABSPATH.'/wp-content/themes/palate/widgets/category_link.php';
require_once ABSPATH.'/wp-content/themes/palate/widgets/featured_posts.php';
require_once ABSPATH.'/wp-content/themes/palate/widgets/highlighted_posts.php';
require_once ABSPATH.'/wp-content/themes/palate/widgets/new_posts.php';
require_once ABSPATH.'/wp-content/themes/palate/widgets/separator.php';
require_once ABSPATH.'/wp-content/themes/palate/widgets/category_presentation.php';
