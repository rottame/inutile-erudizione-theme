<?php

class palate_new_posts_widget extends WP_Widget {
	function __construct() {
		$widget_ops  = array(
      'classname' => 'new_posts',
      'description' => __('Display latest posts or posts of specific category, which will be used as the slider.', 'palate')
    );
		$control_ops = array('width' => 200, 'height' => 250);
		parent::__construct(false, $name = __( 'SIE: New Posts', 'palate'), $widget_ops);
	}

	function form( $instance ) {
    $tg_defaults[ 'title' ]    = '';
		$tg_defaults[ 'cats' ]    = '';
		$instance                  = wp_parse_args( ( array ) $instance, $tg_defaults );
    $title                     = esc_attr( $instance[ 'title' ] );
		$cats                      = esc_attr( $instance[ 'cats' ] );

    ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'cats' ); ?>"><?php _e( 'Categories:', 'colormag' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'cats' ); ?>" name="<?php echo $this->get_field_name( 'cats' ); ?>" type="text" value="<?php echo $cats; ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'colormag' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>"/>
		</p>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance            = $old_instance;
    $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
		$instance[ 'cats' ] = strip_tags( $new_instance[ 'cats' ] );
		return $instance;
	}

	function widget( $args, $instance ) {
		extract( $args );
		extract( $instance );

		global $post;
    $title    = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : '';
		$cats     = isset( $instance[ 'cats' ] )  ? $instance[ 'cats' ] : '';
    $cats = explode(',', $cats);
    $cats = array_map(function($s){return trim($s);}, $cats);

		$post_status = 'publish';
		if ( get_option( 'fresh_site' ) == 1 ) {
			$post_status = array( 'auto-draft', 'publish' );
		}

		if ( empty($cats) ) {
			$get_featured_posts = new WP_Query( array(
				'post_type'           => 'post',
				'ignore_sticky_posts' => false,
				'post_status'         => $post_status,
			) );
		} else {
			$get_featured_posts = new WP_Query( array(
				'post_type'      => 'post',
				'ignore_sticky_posts' => false,
				'category__in'   => $cats,
			));
		}

		$articles = [];
		$sticky = [];
		$i = 1;

		while ( $get_featured_posts->have_posts() ):
			$get_featured_posts->the_post();
      $cat = get_the_category($get_featured_posts->post->ID);
      $catid = @$cat[0]->term_id;

			$is_sticky = is_sticky($get_featured_posts->post->ID);
			if((!$is_sticky && empty($articles[$catid])) || ($is_sticky && empty($sticky[$catid]))) {
				ob_start();
			?>
			  <div class="single-article clearfix">
				<?php
				if ( has_post_thumbnail() ) {
					$image           = '';
					$thumbnail_id    = get_post_thumbnail_id( $post->ID );
					$image_alt_text  = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );
					$title_attribute = get_the_title( $post->ID );
					if ( empty( $image_alt_text ) ) {
						$image_alt_text = $title_attribute;
					}
					$image .= '<figure>';
					$image .= '<a href="' . get_permalink() . '" title="' . the_title( '', '', false ) . '">';
					$image .= get_the_post_thumbnail( $post->ID, $featured, array( 'title' => esc_attr( $title_attribute ), 'alt' => esc_attr( $image_alt_text ) ) ) . '</a>';
					$image .= '</figure>';
					echo $image;
				}
				?>
				<div class="article-content">
					<h3 class="entry-title">
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
					</h3>
          <?php colormag_colored_category(); ?>
					<div class="below-entry-meta">
						<?php
						$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
						$time_string = sprintf( $time_string, esc_attr( get_the_date( 'c' ) ), esc_html( get_the_date() )
						);
						printf( __( '<span class="posted-on"><a href="%1$s" title="%2$s" rel="bookmark"><i class="fa fa-calendar-o"></i> %3$s</a></span>', 'colormag' ), esc_url( get_permalink() ), esc_attr( get_the_time() ), $time_string
						);
						?>
						<span class="byline"><span class="author vcard"><i class="fa fa-user"></i><a class="url fn n" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php echo get_the_author(); ?>"><?php echo esc_html( get_the_author() ); ?></a></span></span>
						<span class="comments"><i class="fa fa-comment"></i><?php comments_popup_link( '0', '1', '%' ); ?></span>
					</div>
					<div class="entry-content">
						<?php the_excerpt(); ?>
					</div>
				</div>

			</div>
			<?php
				$html = ob_get_clean();
				if($is_sticky) {
					$sticky[$catid] = $html;
				} else {
					$articles[$catid] = $html;
				}
				ob_end_clean();
      }
		endwhile;
		// Reset Post Data
		wp_reset_query();

		echo $before_widget;
		if ( ! empty( $title ) ) {
			echo '<h3 class="widget-title"><span>' . esc_html( $title ) . '</span></h3>';
		}
    ?>
    <div class="articles">
		<?php
		foreach ($articles as $catid => $article) {
			if(empty($sticky[$catid])) {
				echo $article;
			} else {
				echo $sticky[$catid];
			}
		}
		?>
		</div>
		<?php
		echo $after_widget;
	}
}
