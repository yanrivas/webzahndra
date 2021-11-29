<?php

if( !function_exists( 'be_is_json' ) ) {
	function be_is_json( $string ) {
		json_decode($string);
		return ( json_last_error() == JSON_ERROR_NONE );
	}
}

if( !function_exists( 'be_solid_color' ) ) {
	function be_solid_color( $color ){
		return array($color,$color,'solid');
	}
}

if( !function_exists( 'be_gradient_color' ) ) {
	function be_gradient_color( $color_arr ){
		$color_value = ''; 
		$first_color_stop = '';
		$i = 0;
		$color_value = 'linear-gradient(';
		$color_value .= $color_arr['angle'].'deg';
		$colorPositions = $color_arr['colorPositions'];
		foreach( $colorPositions as $colorPos => $colorCode ){
			$color_value .= ', '. $colorCode .' '. $colorPos.'%';
			if( $i == 0 ){
				$first_color_stop = $colorCode;
			}
			$i++;
		}
		$color_value .= ')';
		return array( $color_value, $first_color_stop, 'gradient' );
	}
}

if( !function_exists( 'be_compute_color' ) ) {
	function be_compute_color( $color ){
		$color_value = ''; 
		$first_color_stop = '';
		$colorHubColor = '';
		$i = 0;
		if(! empty( $color)){
			if( be_is_json( $color ) ){
				$color_arr = json_decode( $color, true );
				if( array_key_exists( 'id', $color_arr ) ) {
					$id_array = explode( ':', $color_arr['id'] );
					$color_data = $color_arr['color'];
					if( $id_array[0] == 'swatch' && function_exists( 'colorhub_get_swatch' ) ){
						$swatch = colorhub_get_swatch( $id_array[1] );
						$color_data = $swatch['color'];
					}
					if( $id_array[0] == 'palette' && function_exists( 'colorhub_get_palette' ) ){
						$color_data = colorhub_get_palette( $id_array[1] );
					}
					if( is_array( $color_data ) ){
						return be_gradient_color( $color_data );
					} else{
						return be_solid_color( $color_data );
					}
				} 
				else {
					return be_gradient_color( $color_arr );
				}
			} else {
				return be_solid_color( $color );
			}
		}
		return array( $color_value, $first_color_stop, 'solid' );
	}
}

// for CSS Generation for each module 
// get atts(after combining default and atts) & module name (section or row or column)
if( !function_exists( 'be_reformat_module_options' ) ) {
	function be_reformat_module_options( $arr ){
		$new_arr = array();
		foreach($arr as $key => $value){
			$new_arr[$value['att_name']] = $value;
		}
		return $new_arr;
	}
}

if( !function_exists( 'be_should_compute_css' ) ) {
	function be_should_compute_css( $atts, $condition ){
		extract($atts);
		$check_flag = array();
		$relation = !empty($condition['relation'])? $condition['relation'] : '';
		$condition_array = !empty( $condition['when'] ) ? $condition['when'] : array() ;
		if( empty( $condition_array ) ) {
			return true;
		}
		foreach( $condition_array as $each_key => $each_value ){
			$condition_count = count( $condition_array );
			$checking = is_array($each_value) ? $each_value : $condition_array ;
			//if( is_array ( $checking ) ){
				switch( $checking[1] ){
					case 'empty':
						if( empty( ${$checking[0]} ) ){
							$check_flag[] = true;
						}else if( 'and' != $relation ){
							$check_flag[] = false;
						}else{
							return false;
						}
						break;
					case 'notempty':
						if( !empty( ${$checking[0]} ) ){
							$check_flag[] = true;
						}else if( 'and' != $relation ){
							$check_flag[] = false;
						}else{
							return false;
						}
						break;
					case '=':
						if( ${$checking[0]} == $checking[2]){
							$check_flag[] = true;
						}else if( 'and' != $relation ){
							$check_flag[] = false;
						}else{
							return false;
						}
						break;
					case '!=':
						if( ${$checking[0]} != $checking[2]){
							$check_flag[] = true;
						}else if( 'and' != $relation ){
							$check_flag[] = false;
						}else{
							return false;
						}
						break;
					default:
						$check_flag[] = null;
						if( 'and' == $relation ){
							return false;
						}
						break;
				}
			//}
		}
		if( in_array( true, $check_flag ) ){
			if( ( 'and' == $relation && !in_array( false, $check_flag ) ) || ( 'and' != $relation ) ){
				return true;
			}
		}
		return false;
	}
}

if( !function_exists( 'be_compute_css' ) ) {
	function be_compute_css( $config_att, $condition, $val, $property ){

		if( !empty( $condition['callback'] ) && function_exists( $condition['callback'] ) && empty($val)){
			$val = call_user_func($condition['callback']);
		}

		if( is_array( $property ) ){
			$css = '';
			foreach($property as $element){
				$css .= be_compute_css( $config_att, $condition, $val, $element );
			}
			return $css;
		}


		if( $config_att['type'] == 'color'){
			$value = be_compute_color($val);
			$val = $value[0];
			$output = '';
			if( ( 'border' === $property || 'border-color' === $property ) && 'gradient' === $value[2] ) {
				return 'border-image: '.$value[0].';border-image-slice: 1;';
			} else if( ( 'background' === $property || 'background-color' === $property ) && 'gradient' === $value[2] ) {
				return 'background: '.$value[0].';';
			} else if( 'color' === $property ) {
				if( 'gradient' === $value[2] ) {
					$output .= 'background: '.$value[0].'; -webkit-background-clip:text; -webkit-text-fill-color:transparent;';
					
				} else {
					$output .= 'color: '.$value[0].';';
				}
				return $output;
			} else if( 'border-color' === $property){
				return 'border-color: '.$value[0].'; ';
			} 
		}


		if( !empty( $condition['operation'] ) && is_array( $condition['operation'] ) ){
			$operation = $condition['operation'];
			//extract($operation);
			switch($operation[0]){
				case '/':
					$val /=  $operation[1];
					break;
				case '*':
					$val *=  $operation[1];
					break;
				case '+':
					$val +=  $operation[1];
					break;
				case '-':
					$val -=  $operation[1];
					break;
				default:
					$val = $val;
					break;
			}
		}


		if( $property == 'background-image' ) {
			return $property.': url('.$val.');';
		} else if( $property == 'transform' ) {
			$value = explode(' ', $val);
			return $property.': translate3d('.($value[0]).','.($value[1]).', 0);';
		} else {
			if( !empty( $condition['append'] ) || !empty( $condition['prepend'] )){
				if( !empty( $condition['append'] ) && !empty( $condition['prepend'] ) ){
					return $property.': '.$condition['prepend'].$val.$condition['append'].';';
				} elseif( !empty( $condition['prepend'] ) ){
					return $property.': '.$condition['prepend'].$val.';';
				}
				return $property.': '.$val.$condition['append'].';';
			}
			return $property.': '.$val.';';		
		}
	}
}

if( !function_exists( 'be_generate_css_from_atts' ) ) {
	function be_generate_css_from_atts( $atts, $module, $uuid ){
		$tatsu_registered_modules = Tatsu_Module_Options::getInstance()->get_modules();
		$atts = apply_filters( $module.'_before_css_generation', $atts );
		$css_props = array();
		$config = be_reformat_module_options($tatsu_registered_modules[$module]['atts']);
		foreach( $atts as $att => $value ){
			if( !empty( $config[$att]['css'] )  ){
				if( !empty( $config[$att]['responsive'] ) ) {
					$temp_value = json_decode( $value, true );
					if( $temp_value ) {
						$value = $temp_value;
					}
				}
				$selectors = $config[$att]['selectors'];
				foreach( $selectors as $selector => $condition ){
					$index = str_replace('{UUID}', $uuid, $selector);
					// if( ( 'color' === $config[$att]['type'] || 'gradient_color' === $config[$att]['type'] ) && empty( $value ) ) {
					// 	$value = 'transparent';
					// }
					if( !empty( $value ) ) {
						if( is_array( $value ) ) {	
							foreach( $value as $device => $val ) {
								be_should_compute_css( $atts, $condition ) ? $css_props[$device][$index][] = be_compute_css( $config[$att], $condition, $val, $condition['property'] ) : null ;
							}
						} else {
							be_should_compute_css( $atts, $condition ) ? $css_props['d'][$index][] = be_compute_css( $config[$att], $condition, $value, $condition['property'] ) : null ;
						}
					}
				}
			}
		}
		
		$css = array();
		foreach($css_props as $device => $selectors){
			$css[$device] = '';
			foreach ( $selectors as $selector => $css_atts ) {
				if( !empty ( $css_atts ) ){
					$css[$device] .= $selector. '{'.implode('' ,$css_atts). '}';
				}
			}
		}

		$output = '';
		$output .= '<style>';
		if( !empty( $css['d'] ) ) {
			$output .= $css['d'];
		}
		//$output .= array_key_exists('d', $css) ? $css['d'] : '' ;
		if( !empty( $css['l'] ) ) {
			$output .= '@media only screen and (max-width:1377px) {';
			$output .= $css['l'];
			$output .= '}';
		}
		if( !empty( $css['t'] ) ) {
			$output .= '@media only screen and (min-width:768px) and (max-width: 1024px) {';
			$output .= $css['t'];
			$output .= '}';
		}
		if( !empty( $css['m'] ) ) {
			$output .= '@media only screen and (max-width: 767px) {';
			$output .= $css['m'];
			$output .= '}';
		}
		$output .= '</style>';

		//return be_minify_css( $output );
		return $output;
	}
}

if( !function_exists( 'be_minify_css' ) ) {
	function be_minify_css( $css ) {

		// Normalize whitespace
		$css = preg_replace( '/\s+/', ' ', $css );

		// Remove spaces before and after comment
		$css = preg_replace( '/(\s+)(\/\*(.*?)\*\/)(\s+)/', '$2', $css );

		// Remove comment blocks, everything between /* and */, unless
		// preserved with /*! ... */ or /** ... */
		$css = preg_replace( '~/\*(?![\!|\*])(.*?)\*/~', '', $css );

		// Remove ; before }
		$css = preg_replace( '/;(?=\s*})/', '', $css );

		// Remove space after , : ; { } */ >
		$css = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css );

		// Remove space before , ; { } ) > 
		$css = preg_replace( '/ (,|;|\{|}|\)|>)/', '$1', $css );

		// Strips leading 0 on decimal values (converts 0.5px into .5px)
		$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );

		// Strips units if value is 0 (converts 0px to 0)
		$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );

		// Converts all zeros value into short-hand
		$css = preg_replace( '/0 0 0 0/', '0', $css );

		// Shortern 6-character hex color codes to 3-character where possible
		$css = preg_replace( '/#([a-f0-9])\\1([a-f0-9])\\2([a-f0-9])\\3/i', '#\1\2\3', $css );

		return trim( $css );

	}
}

?>