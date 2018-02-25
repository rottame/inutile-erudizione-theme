<?php
class palate_highlighted_posts_widget extends WP_Widget {

	function __construct() {
		$widget_ops  = array( 'classname' => 'widget_highlighted_posts widget_featured_meta widget_pillole_di_erudizione', 'description' => __( 'Display latest posts or posts of specific category. Suitable for the Area Beside Slider Sidebar.', 'palate' ) );
		$control_ops = array( 'width' => 200, 'height' => 250 );
		parent::__construct( false, $name = __( 'SIE: Highlighted Posts', 'palate' ), $widget_ops );
	}

	function form( $instance ) {
		$tg_defaults[ 'number' ]   = 3;
		$tg_defaults[ 'type' ]     = 'latest';
    $tg_defaults[ 'category' ] = '';
		$tg_defaults[ 'title' ] = '';
		$instance                  = wp_parse_args( ( array ) $instance, $tg_defaults );
		$number                    = $instance[ 'number' ];
		$type                      = $instance[ 'type' ];
    $category                  = $instance[ 'category' ];
		$title                     = $instance[ 'title' ];
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'colormag' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>"/>
		</p>
    <?php /*
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to display:', 'colormag' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3"/>
		</p>

    */ ?>

		<p>
			<input type="radio" <?php checked( $type, 'latest' ) ?> id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>" value="latest"/><?php _e( 'Show latest Posts', 'colormag' ); ?>
			<br/>
			<input type="radio" <?php checked( $type, 'category' ) ?> id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>" value="category"/><?php _e( 'Show posts from a category', 'colormag' ); ?>
			<br/></p>

		<p>
			<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Select category', 'colormag' ); ?>
				:</label>
			<?php wp_dropdown_categories( array( 'show_option_none' => ' ', 'name' => $this->get_field_name( 'category' ), 'selected' => $category ) ); ?>
		</p>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance               = $old_instance;
		$instance[ 'number' ]   = absint( $new_instance[ 'number' ] );
    if ( empty( $instance[ 'number' ] ) ) {
      $instance[ 'number' ] = 3;
    }
		$instance[ 'type' ]     = $new_instance[ 'type' ];
    $instance[ 'category' ] = $new_instance[ 'category' ];
		$instance[ 'title' ]    = $new_instance[ 'title' ];

		return $instance;
	}

	function widget( $args, $instance ) {
		extract( $args );
		extract( $instance );

		global $post;
		$number   = empty( $instance[ 'number' ] ) ? 3 : $instance[ 'number' ];
    $number = 3;
		$type     = isset( $instance[ 'type' ] ) ? $instance[ 'type' ] : 'latest';
		$category = isset( $instance[ 'category' ] ) ? $instance[ 'category' ] : '';

		$post_status = 'publish';
		if ( get_option( 'fresh_site' ) == 1 ) {
			$post_status = array( 'auto-draft', 'publish' );
		}

		if ( $type == 'latest' ) {
			$get_featured_posts = new WP_Query( array(
				'posts_per_page'      => $number,
				'post_type'           => 'post',
				'ignore_sticky_posts' => true,
				'post_status'         => $post_status,
				'category__not__in'   => CAT_PRESENTAZIONI_ID
			) );
		} else {
			$get_featured_posts = new WP_Query( array(
				'posts_per_page' => $number,
				'post_type'      => 'post',
				'ignore_sticky_posts' => true,
				'category__in'   => $category,
				'category__not_in'   => CAT_PRESENTAZIONI_ID
			) );
		}

		echo $before_widget;
		if ( ! empty( $title ) ) {
      $cssclass = strtolower(iconv('ISO-8859-1', 'ASCII//TRANSLIT', $title));
      $cssclass = preg_replace('/[^a-z0-9]+/', '-', $cssclass);
			echo '<h3 class="widget-title ' . $cssclass . '" ' . $border_color . '><span ' . $title_color . '>' . filter_category( esc_html( $title ) ) . '</span></h3>';
		}
		?>
		<div class="widget_highlighted_post_area">
			<?php $featured = 'colormag-highlighted-post'; ?>
			<?php
			$i = 1;
			while ( $get_featured_posts->have_posts() ):$get_featured_posts->the_post();
				?>
				<div class="single-article single-pill">
          <div class="pill-number">
            <a href="<?php echo get_permalink() ?>" title="<?php echo the_title( '', '', false ) ?>">
              <span class="pillno">§<?php echo pill_number(the_title( '', '', false )) ?></span>
            </a>
          </div>
          <div class="hr style4"><span></span><span></span><span></span></div>
          <?php
					?>
					<div class="article-content">
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
				$i ++;
			endwhile;
			// Reset Post Data
			wp_reset_query();
			?>
		</div>
		<?php
		echo $after_widget;
	}

}
