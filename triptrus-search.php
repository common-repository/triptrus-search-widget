<?php 
	/*
	Plugin Name: TripTrus Search Widget
	Plugin URI: http://www.triptrus.com
	Description: Plugin for displaying TripTrus Search Widget
	Author: TripTrus
	Version: 1.1
	Author URI: http://www.triptrus.com
	License: GPLv2 or later
	*/
	class TripTrus_Search extends WP_Widget{
		public function __construct(){
			$widget_ops = array('description' => __('TripTrus Search Widget', 'TripTrus_Search'));
			parent::__construct(false, __('TripTrus Search', 'TripTrus_Search'), $widget_ops, null);
		}
		public function form($instance){
			$width = '100';
			$unit = '%';
			$skin = 'default';
			$operator_id = '';
			$show_in_new_window = '0';
			$css = plugins_url( 'custom.css', __FILE__ );

			if($instance){
				$skin = esc_attr($instance['skin']);
				$width = intval($instance['width']);
				$unit = esc_attr($instance['unit']);
				$operator_id = $instance['operator_id'];
				$show_in_new_window = esc_attr($instance['show_in_new_window']);
				$css = $instance['css'];
			}
			$unit_options = array('%', 'px');
			$html_option = '';
			foreach($unit_options as $option){
				$html_option .= '<option value="'.$option.'" '.(($option==$unit)?'selected':'').'>'.$option.'</option>';
			}
			if(empty($show_in_new_window))
				$show_in_new_window = '0';
			if($skin == 'custom' && empty($css))
				$css = plugins_url( 'custom.css', __FILE__ );

			$form = '<p>
<label for="'.$this->get_field_id('width').'">'.__('Width :', 'TripTrus_Search').'</label><br />
<input id="'.$this->get_field_id('width').'" name="'.$this->get_field_name('width').'" value="'.$width.'" size="5" style="text-align:right" />
<select id="'.$this->get_field_id('unit').'" name="'.$this->get_field_name('unit').'">'.$html_option.'</select>
</p><p>
<label for="'.$this->get_field_id('skin').'">'.__('Skin:', 'TripTrus_Search').'</label><br />';
		$options = array('default'=>'Default', 'dark'=>'Dark', 'light'=>'Light', 'custom'=>'Custom');
		foreach($options as $key=>$value){
			$form .= '<input type="radio" id="'.$this->get_field_id('skin').'" name="'.$this->get_field_name('skin').'" value="'.$key.'"';
			if($key == $skin)
				$form .= ' checked ';
			if($key == 'custom')
				$form .= 'onclick="this.parentNode.nextSibling.removeAttribute(\'style\');this.parentNode.nextSibling.style.display=\'block !important\';"';
			else
				$form .= 'onclick="this.parentNode.nextSibling.removeAttribute(\'style\');this.parentNode.nextSibling.style.display=\'none\';"';
			$form .= ' />&nbsp;'.$value.'<br />';
		}
		$style = '';
		if($skin != 'custom')
			$style = 'style="display:none"';
		$form .='</p><p '.$style.'><label for="'.$this->get_field_id('css').'">'.__('CSS URL:', 'TripTrus_Search').'</label><br />
<input class="widefat" id="'.$this->get_field_id('css').'" name="'.$this->get_field_name('css').'" value="'.$css.'" />
</p><p><label for="'.$this->get_field_id('show_in_new_window').'">'.__('Show result in new window:', 'TripTrus_Search').'</label><br />';
$window_options = array('No', 'Yes');
foreach($window_options as $key=>$value){
	$form .= '<input type="radio" id="'.$this->get_field_id('show_in_new_window').'" name="'.$this->get_field_name('show_in_new_window').'" value="'.$key.'"';
	if((string)$key === $show_in_new_window)
			$form .= ' checked ';
	$form .= ' />&nbsp;'.$value.'<br />';
}
		$form .='</p><p><label for="'.$this->get_field_id('operator_id').'">'.__('Operator ID:', 'TripTrus_Search').'&nbsp;<a href="http://www.triptrus.com/your-profile-id" target="_blank">find here</a></label>
<input class="widefat" id="'.$this->get_field_id('operator_id').'" name="'.$this->get_field_name('operator_id').'" value="'.$operator_id.'" />
</p>';
			echo $form;
		}
		public function update($new_instance, $old_instance){
			$instance = $old_instance;
			$instance['width'] = intval($new_instance['width']);
			$instance['skin'] = strip_tags($new_instance['skin']);
			$instance['unit'] = strip_tags($new_instance['unit']);
			$instance['css'] = strip_tags($new_instance['css']);
			$instance['operator_id'] = strip_tags($new_instance['operator_id']);
			$instance['show_in_new_window'] = strip_tags($new_instance['show_in_new_window']);
			return $instance;
		}
		public function widget($args, $instance){
			extract($args);
			$width = $instance['width'];
			$unit = $instance['unit'];
			$skin = $instance['skin'];
			$css = $instance['css'];
			$show_in_new_window = $instance['show_in_new_window'];
			$attribute = 'skin:'.$skin;
			if($skin == 'custom'){
				if(empty($css))
					$css = plugins_url( 'custom.css', __FILE__ );
				$attribute .=';css:'.$css;
			}
			$operator_id = $instance['operator_id'];
			$attribute .= ';operator-id:'.$operator_id;

			if($show_in_new_window == '1')
				$attribute .= ';target:_blank';
			echo $before_widget;
			echo '<div id="triptrus-search" style="width:'.$width.$unit.'" data-triptrus="'.$attribute.'"></div>';
			echo '<script type="text/javascript" src="//widget.triptrus.com/js/triptrus_search.min.js"></script>';
			echo $after_widget;
		}
	}
	add_action('widgets_init', create_function('', 'return register_widget("TripTrus_Search");'));
?>
