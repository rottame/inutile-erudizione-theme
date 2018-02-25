<?php

class palate_category_presentation_widget extends WP_Widget {
	function __construct() {
		$widget_ops  = array(
      'classname' => 'category_presentation',
      'description' => __('Display category presentation', 'palate')
    );
		$control_ops = array('width' => 200, 'height' => 250);
		parent::__construct(false, $name = __( 'SIE: Category Peesentation', 'palate'), $widget_ops);
	}

	function form( $instance ) {
    $tg_defaults[ 'title' ]    = '';
		$instance                  = wp_parse_args( ( array ) $instance, $tg_defaults );
    $title                     = esc_attr( $instance[ 'title' ] );
    ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'colormag' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>"/>
		</p>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance            = $old_instance;
    $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
		return $instance;
	}

	function widget( $args, $instance ) {
		extract( $args );
		extract( $instance );

		global $post;
    $title    = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : '';

		$post_status = 'publish';
		if ( get_option( 'fresh_site' ) == 1 ) {
			$post_status = array( 'auto-draft', 'publish' );
		}

		$category_id = null;
		if(is_category()) {
			$category_id = get_query_var('cat');
		}

		if (!empty($category_id) && $category_id != CAT_PRESENTAZIONI_ID) {
			$get_posts = new WP_Query( array(
				'post_type'           => 'post',
				'ignore_sticky_posts' => false,
				'post_status'         => $post_status,
				'cat'                 => CAT_PRESENTAZIONI_ID
			) );

			$html = null;

			while ( $get_posts->have_posts() ):
				$get_posts->the_post();
	      $cats = get_the_category($get_posts->post->ID);
	      $catids = array_map(function($c){ return $c->term_id;}, $cats);

				if(in_array($category_id, $catids)) {
					ob_start();
				?>
				  <div class="single-article clearfix">
						<div class="article-content">
							<h3 class="entry-title">
								<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
							</h3>
							<div class="entry-content">
								<?php presentation_excerpt(); ?>

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
					</div>
				<?php
					$html = ob_get_clean();
					ob_end_clean();
				}
			endwhile;
			// Reset Post Data
			wp_reset_query();

			echo $before_widget;

			if(!empty($html)) {
				if (!empty($title)) {
					echo '<h3 class="widget-title"><span>' . esc_html( $title ) . '</span></h3>';
				}
				?>
				<div class="articles">
					<?php echo $html; ?>
				</div>
				<div class="hr style3"><span></span><span></span><span></span></div>
				<?php
			}
			echo $after_widget;
		} else {
			echo $before_widget;
			echo $after_widget;
		}
	}
}
