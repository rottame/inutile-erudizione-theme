<?php

class palate_category_link extends WP_Widget {
	function __construct() {
		$widget_ops  = array(
      'classname' => 'category-link',
      'description' => __('Display a link to a category.', 'palate')
    );
		$control_ops = array('width' => 200, 'height' => 250);
		parent::__construct(false, $name = __( 'SIE: Category link', 'palate'), $widget_ops);
	}

	function form( $instance ) {
		$tg_defaults[ 'category' ] = '';
		$instance                  = wp_parse_args( ( array ) $instance, $tg_defaults );
		$category                  = $instance[ 'category' ];
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Select category', 'colormag' ); ?>
				:</label>
			<?php wp_dropdown_categories( array( 'show_option_none' => ' ', 'name' => $this->get_field_name( 'category' ), 'selected' => $category ) ); ?>
		</p>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance            = $old_instance;
    $instance[ 'category' ] = strip_tags( $new_instance[ 'category' ] );
		return $instance;
	}

	function widget( $args, $instance ) {
		extract( $args );
		extract( $instance );
		global $post;
    $category    = isset( $instance[ 'category' ] ) ? $instance[ 'category' ] : 1;
		echo $before_widget;
    ?>
    <div class="the-link">
      <a href="<?php echo get_category_link( $category ) ?>">
        <span></span>
        Erudito Archivio
      </a>
    </div>
    <?php
		echo $after_widget;
	}
}
