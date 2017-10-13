<?php
/*
Plugin Name: WYSIWYG Foot Notes
Description: WYSIWYG foot note
Version:     1.0
Author:      Heng
*/


//Add TinyMCE button

add_action('admin_head', 'hz_fn_button');
function hz_fn_button(){
	global $typenow;
	add_filter("mce_external_plugins", "hz_add_tinymce_plugin");
	add_filter("mce_buttons","hz_register_my_tc_button");
}

function hz_add_tinymce_plugin($plugin_array){
	$plugin_array['hz_tc_button'] = plugins_url('/footnote-button.js', __FILE__);
	return $plugin_array;
}

function hz_register_my_tc_button($buttons){
	array_push($buttons, "hz_tc_button");
	return $buttons;
}



add_action('wp_enqueue_scripts', 'hz_enqueue_styles');

function hz_enqueue_styles(){
	wp_enqueue_style('hz-footnote-styles', get_bloginfo('wpurl').'/'.PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)).'/style.css');

}

add_action('wp_enqueue_scripts', 'hz_enqueue_scripts');
function hz_enqueue_scripts(){
	wp_enqueue_script('jquery');

	wp_register_script('hz-footnote-script', get_bloginfo('wpurl').'/'.PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)).'/script.js', array('jquery'), '1.34');
	wp_enqueue_script('hz-footnote-script');
}


// Converts footnote markup into actual footnotes
function footnote_convert($content) {
	$post_id = get_the_ID();

	$n = 1;
	$notes = array();
	if (preg_match_all('/\[footnote\*(.*?)\]/s', $content, $matches)) {
		foreach($matches[0] as $key => $marker) {
			$note = preg_replace('/\[footnote\*(.*?)\]/s', '['.$n.']', $marker);
			$m = $matches[1][$key];
			$notes[$n] = $m;	
			$content = str_replace($marker, "<span class='hz-footnote-block'>[$n]<span class='hz-footnote'>$m</span></span>", $content);
			$n++;
		}


		$content .= "\n\n";


		$content .= "<div class='hz-footnotes-bottom' id='footnotes-$post_id'>";
		$content .= "<div class='hz-footnotedivider'></div>";
			$content .= "<ol>";

			foreach($notes as $nt){
				$content .= "<li>$nt</li>";
			}
			$content .= "</ol>";
		$content .= "</div>";
	}

	return($content);
}

add_action('the_content', 'footnote_convert', 1);
?>
