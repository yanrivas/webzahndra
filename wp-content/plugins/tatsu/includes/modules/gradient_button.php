<?php
if (!function_exists('tatsu_gradient_button')) {
	function tatsu_gradient_button( $atts, $content ) {

		$atts = shortcode_atts( array (
			'button_text' => '',
			'url' => '',
			'new_tab'=> 'no',
			'type' => 'small',
			'alignment' => '',							 
			'bg_color' => '',
			'hover_bg_color' => '',
			'color'=> '',
			'hover_color'=> '',
			'border_width' => 0,			
			'border_color'=> '',
			'hover_border_color'=> '',
			'lightbox' => 0,	
			'image' => '',
			'video_url' => '',
			'animate' => 0,
			'animation_type' => 'fadeIn',
			'animation_delay' => 0,
			'key' => be_uniqid_base36(true),
		), $atts ) ;
		
		extract( $atts );
		$custom_style_tag = be_generate_css_from_atts( $atts, 'tatsu_gradient_button', $key );
		$custom_class_name = 'tatsu-'.$key;

		$mfp_class = '';
		$output = '';

		$alignment = ( "block" === $type ) ? 'center' : $alignment;
		if( isset( $alignment ) ){
			if( $alignment != 'none' ){
				$alignment = 'align-block block-'.$alignment;
			} else {
				$alignment = '';
			}
		}

		$new_tab = ( isset( $new_tab ) && 1 == $new_tab ) ? 'target="_blank"' : '' ;
		
		$hover_bg_class =  empty( $hover_bg_color ) ? 'transparent_hover_bg' : '';

		$animate = ( isset( $animate ) && 1 == $animate ) ? ' tatsu-animate' : ''; 

		$url = ( empty( $url ) ) ? '#' : $url ;

		$image_wrap_class = '';

		if( $lightbox && 1 == $lightbox ) {
			if( !empty( $video_url ) ) {
				$mfp_class = 'mfp-iframe';
				$url = $video_url;
			} elseif ( !empty($image) ) {
				$mfp_class = 'mfp-image';
				$url = $image;
			}
		}

		$output .= '
		<div class="tatsu-module tatsu-gradient-button tatsu-button-container '.$alignment.' '.$custom_class_name.' '.$hover_bg_class.'">
			<div class="tatsu-button-wrap">
				<a class="tatsu-button '.$animate.' '.$mfp_class.' '.$type.'btn" href="'.$url.'" '.$new_tab.' data-animation="'.$animation_type.'" data-animation-delay="'.$animation_delay.'">
					<span class="tatsu-button-text " data-text="'.$button_text.'"><span class="default">'.$button_text.'</span></span>
				</a> 
			</div>
			'.$custom_style_tag.'
		</div>';
		//$output .= $custom_style_tag;
		return $output;
	}
	add_shortcode( 'tatsu_gradient_button', 'tatsu_gradient_button' );
}
?>