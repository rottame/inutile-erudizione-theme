<?php
class palate_featured_posts_widget extends colormag_featured_posts_widget {
	function __construct() {
		$widget_ops  = array( 'classname' => 'widget_featured_posts widget_featured_meta', 'description' => __( 'Display latest posts or posts of specific category.', 'colormag' ) );
		$control_ops = array( 'width' => 200, 'height' => 250 );
		WP_Widget::__construct( false, $name = __( 'SIE: Featured Posts (Style 1)', 'palate' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );
		extract( $instance );

		global $post;
		$title    = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : '';
		$text     = isset( $instance[ 'text' ] ) ? $instance[ 'text' ] : '';
		$number   = empty( $instance[ 'number' ] ) ? 4 : $instance[ 'number' ];
		$type     = isset( $instance[ 'type' ] ) ? $instance[ 'type' ] : 'latest';
		$category = isset( $instance[ 'category' ] ) ? $instance[ 'category' ] : '';

		$post_status = 'publish';
		if ( get_option( 'fresh_site' ) == 1 ) {
			$post_status = array( 'auto-draft', 'publish' );
		}

		if ( $type == 'latest' ) {
			$get_featured_posts = new WP_Query( array(
				'posts_per_page'      => $number + 1,
				'post_type'           => 'post',
				'ignore_sticky_posts' => true,
				'post_status'         => $post_status,
			) );
		} else {
			$get_featured_posts = new WP_Query( array(
				'posts_per_page' => $number + 1,
				'post_type'      => 'post',
				'ignore_sticky_posts' => true,
				'category__in'   => $category,
				'category__not_in'   => CAT_PRESENTAZIONI_ID,
			) );
		}

		echo $before_widget;
		?>
		<?php
		if ( $type != 'latest' ) {
			$border_color = 'style="border-bottom-color:' . colormag_category_color( $category ) . ';"';
			$title_color  = 'style="background-color:' . colormag_category_color( $category ) . ';"';
		} else {
			$border_color = '';
			$title_color  = '';
		}
		if ( ! empty( $title ) ) {
      $cssclass = strtolower(iconv('ISO-8859-1', 'ASCII//TRANSLIT', $title));
      $cssclass = preg_replace('/[^a-z0-9]+/', '-', $cssclass);
			echo '<h3 class="widget-title ' . $cssclass . '" ' . $border_color . '><span ' . $title_color . '>' . filter_category( esc_html( $title ) ) . '</span></h3>';
		}
		if ( ! empty( $text ) ) {
			?> <p> <?php echo esc_textarea( $text ); ?> </p> <?php } ?>
		<?php
		$i = 1;

    //$get_featured_posts->the_post();

		while ( $get_featured_posts->have_posts() ):
			$get_featured_posts->the_post();
			?>
			<?php if ( $i == 1 ) {
				$featured = 'colormag-featured-post-medium';
			} else {
				$featured = 'colormag-featured-post-small';
			}
      if ( $i == 1 ) {
				echo '<div class="first-post">';
			} else if ( $i == 2 ) {
				echo '<div class="following-post">';
			}
      if ( $i > 2 ) {
        echo '<div class="hr style4"><span></span><span></span><span></span></div>';
      }
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
					<?php if ( $i == 1 ) { ?>
						<div class="entry-content">
							<?php the_excerpt(); ?>
						</div>
					<?php } ?>
				</div>

			</div>
			<?php
      if ( $i == 1 ) {
				echo '</div>';
			}

			$i ++;
		endwhile;
		if ( $i > 2 ) {
			echo '</div>';
		}
		// Reset Post Data
		wp_reset_query();
		?>
		<!-- </div> -->
		<?php
		echo $after_widget;
	}
}
