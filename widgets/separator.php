<?php
class palate_separator extends WP_Widget {
	function __construct() {
		$widget_ops  = array(
      'classname' => 'separator',
      'description' => __('Display a separator.', 'palate')
    );
		$control_ops = array('width' => 200, 'height' => 250);
		parent::__construct(false, $name = __( 'SIE: Separator', 'palate'), $widget_ops);
	}

	function form( $instance ) {
    $tg_defaults[ 'style' ]    = '';
		$instance                  = wp_parse_args( ( array ) $instance, $tg_defaults );
		$style                     = esc_attr( $instance[ 'style' ] );

    ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php _e( 'Style:', 'palate' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>" type="text" value="<?php echo $style; ?>"/>
		</p>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance            = $old_instance;
    $instance[ 'style' ] = strip_tags( $new_instance[ 'style' ] );
		return $instance;
	}

	function widget( $args, $instance ) {
		extract( $args );
		extract( $instance );
		global $post;
    $style    = isset( $instance[ 'style' ] ) ? $instance[ 'style' ] : 1;
		echo $before_widget;
    ?>
    <div class="hr style<?php echo $style?>"><span></span><span></span><span></span></div>
    <?php
		echo $after_widget;
	}
}
