<?php
if (!function_exists('tatsu_icon')) {
	function tatsu_icon( $atts, $content ) {
		$atts = shortcode_atts(array(
			'name' => '',
			'size'=> 'medium',
			'style'=> 'circle',
			'bg_color'=> '',
			'hover_bg_color'=> '',
			'color'=> '',
			'hover_color'=> '',
			'border_width' => 1,
			'border_color'=> '#323232',
			'hover_border_color'=> '#323232',
			'href'=> '#',
			'alignment' => 'none',
			'lightbox' => 0,
			'image' => '',
			'video_url' => '',
			'new_tab' => 0,
			'animate' => 0,
			'animation_type'=>'fadeIn',
			'animation_delay' => 0,
			'key' => be_uniqid_base36(true),
		),$atts );

		extract( $atts );
		$custom_style_tag = be_generate_css_from_atts( $atts, 'tatsu_icon', $key );
		$custom_class_name = 'tatsu-'.$key;
				
		$mfp_class = '';
		$output = '';
		$animate = ( isset( $animate ) && 1 == $animate ) ? ' be-animate' : '' ;
		$new_tab = ( isset( $new_tab ) && 1 == $new_tab ) ? 'target="_blank"' : '' ;
		$href = ( empty( $href ) ) ? '#' : $href ;

		if( isset( $lightbox ) && 1 == $lightbox ) {
			if( !empty( $video_url ) ) {
				$mfp_class = 'mfp-iframe';
				$href = $video_url;
			} elseif ( !empty($image) ) {
				$mfp_class = 'mfp-image';
				$href = $image;
			}
		}

		
		$output .= '<div class="tatsu-module tatsu-normal-icon tatsu-icon-shortcode align-'.$alignment.' '.$custom_class_name.'">';
		$output .= $custom_style_tag; 
		$output .= '<a href="'.$href.'" class="tatsu-icon-wrap '.$style.' '.$animate.' '.$mfp_class.'" data-animation="'.$animation_type.'" data-animation-delay="'.$animation_delay.'" '.$new_tab.'>';
		$output .= ( $style == 'plain' ) ? '<i class="tatsu-icon '.$name.' '.$size.' '.$style.'"></i></a>' : '<i class="tatsu-icon '.$name.' '.$size.' '.$style.'"  data-animation="'.$animation_type.'" data-animation-delay="'.$animation_delay.'"></i></a>' ;
		$output .= '</div>';
		
		return $output;
	}
	add_shortcode( 'tatsu_icon', 'tatsu_icon' );
	add_shortcode( 'icon', 'tatsu_icon' );
}
?>