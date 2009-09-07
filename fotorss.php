<?php
/*
Plugin Name: fotoRss
Plugin URI: http://www.albumdefamilie.ro/api/plugins/
Description: Poti integra poze de pe Albumdefamilie.ro in blog-ul tau folosind API-ul de feed rss.
Version: 1.0
License: GPL
Author:   Dave Kellam and Stefano Verna [flickrRss], Adrian Tanasa
Author URI: http://evolutica.ro
*/

if (!class_exists('fotoRss')) {
	class fotoRss {
	
		function fotoRss() {
			$this->version = "1.0";
		}
	
		function setupActivation() {
		
			function get_and_delete_option($setting) { $v = get_option($setting); delete_option($setting); return $v; }
		
			// check for previously installed version
			if (get_option('fotoRss_fotorssid')) {
				// let's port previous settings and delete them
				$settings = $this->fixArguments(array(
					get_and_delete_option('fotoRss_display_numitems'),
					get_and_delete_option('fotoRss_display_type'),
					get_and_delete_option('fotoRss_tags'),
					get_and_delete_option('fotoRss_display_imagesize'),
					get_and_delete_option('fotoRss_before'),
					get_and_delete_option('fotoRss_after'),
					get_and_delete_option('fotoRss_fotorssid'),
					get_and_delete_option('fotoRss_set'),
					get_and_delete_option('fotoRss_use_image_cache'),
					get_and_delete_option('fotoRss_image_cache_uri'),
					get_and_delete_option('fotoRss_image_cache_dest')
				));
				update_option('fotoRss_settings', $settings);
			}
		
			// update version number
			if (get_option('fotoRss_version') != $this->version)
				update_option('fotoRss_version', $this->version);
		}
	
		function fixArguments($args) {
			$settings = array();
		
			if (isset($args[0])) $settings['num_items'] = $args[0];
		  	if (isset($args[1])) $settings['type'] = $args[1];
		  	if (isset($args[2])) $settings['tags'] = $args[2];
		  	if (isset($args[6])) $settings['id'] = $args[6];
//		  	if (isset($args[7])) $settings['set'] = $args[7];
//			if (isset($args[8])) $settings['do_cache'] = $args[8];
//			if (isset($args[9])) $settings['cache_uri'] = $args[9];
//			if (isset($args[10])) $settings['cache_path'] = $args[10];
	
			$imagesize = $args[3]?$args[3]:"square";
			$before_image = $args[4]?$args[4]:"";
			$after_image = $args[5]?$args[5]:"";

			$settings['html'] = $before_image . '<a href="%fotorss_page%" title="%title%"><img src="%image_'.$imagesize.'%" alt="%title%" /></a>' . $after_image;
		
			return $settings;
		}
	
		function getSettings() {
			
			if (!get_option('fotoRss_settings')) $this->setupActivation();
			
			$settings = array(
				/*== Content params ==*/
				// Tipul imaginilor de pe AlbumdeFamilie ce pot fi aratate. Valori posibile: 'user', 'favorite', 'public'
				'type' => 'public',
				// Optional: se poate folosi type = 'user' or 'public'
				'tags' => '',
				// Optional: in dezvoltare
				'set' => '',
				// Optional: ID user (ex: vlad). De folosit cu variabila type = 'user'
				'id' => '',
				// Optional: tagmode any|all
				'tagmode' => 'any',
			
				/*== Presentational params ==*/
				 // Numarul default de imagini
				'num_items' => 4,
				 // codul  HTML de afisat inaintea listei e imagini
				'before_list' => '',
				// codul html de printat pt fiecare imagine. Optiuni disponibile:
				// - %fotorss_page%
				// - %title%
				// - %image_square%, %image_thumbnail%, %image_medium%
				'html' => '<a href="%fotorss_page%" title="%title%"><img src="%image_square%" alt="%title%"/></a>',
				// titlul default
				'default_title' => "Poze AlbumdeFamilie", 
				// codul  HTML de afisat dupa lista de imagini
				'after_list' => ''
			);
			if (get_option('fotoRss_settings'))
				$settings = array_merge($settings, get_option('fotoRss_settings'));
			return $settings;
		}
	
		function getRSS($settings) {
			if (!function_exists('MagpieRSS')) { 
				// verifica daca alt plugin plugin foloseste RSS, e posibil sa nu mearga
				include_once (ABSPATH . WPINC . '/rss.php');
				error_reporting(E_ERROR);
			}
			// preluare feed
	
			if ($settings['type'] == "user") { $rss_url = 'http://albumdefamilie.ro/rss/api/?id=' . $settings['id'] . '&tags=' . $settings['tags']. '&tagmode=' . $settings['tagmode'] . '&format=rss_200'; }
			elseif ($settings['type'] == "public") { $rss_url = 'http://albumdefamilie.ro/rss/api/?tags=' . $settings['tags'] . '&tagmode=' . $settings['tagmode'] . '&format=rss_200'; }
			else { 
				print '<strong>Parametrul "type" nu a fost setat. Verificati pagina fotoRss Setari, si specificati acest parametru.</strong>';
				die();
			}
			# get rss file
			return @fetch_rss($rss_url);
		}
	
		function printGallery($settings = array()) {
		
			if (!is_array($settings)) {
				$settings = $this->fixArguments(func_get_args());
			}
		
			$settings = array_merge($this->getSettings(), $settings);
			if (!($rss = $this->getRSS($settings))) return;
			# specifies number of pictures
			$items = array_slice($rss->items, 0, $settings['num_items']);
			echo stripslashes($settings['before_list']);
			# builds html from array
			foreach ( $items as $item ) {
				if(!preg_match('<img src="([^"]*)" [^/]*/>', $item['description'], $imgUrlMatches)) {
					continue;
				}
				$baseurl = str_replace("_240.jpg", "", $imgUrlMatches[1]);
				$thumbnails = array(
					'square' => $baseurl . "_75.jpg",
					'thumbnail' => $baseurl . "_240.jpg",
					'medium' => $baseurl . "_500.jpg",
				);
				#verifica daca poza are titlu
				if($item['title'] !== "") 
					$title = htmlspecialchars(stripslashes($item['title']));
				else 
					$title = $settings['default_title'];
				$url = $item['link'];
				$toprint = stripslashes($settings['html']);
				$toprint = str_replace("%fotorss_page%", $url, $toprint);
				$toprint = str_replace("%title%", $title, $toprint);
			
				$cachePath = trailingslashit($settings['cache_uri']);
				$fullPath = trailingslashit($settings['cache_path']);
			
				foreach ($thumbnails as $size => $thumbnail) {
					$toprint = str_replace("%image_".$size."%", $thumbnail, $toprint);
				}
				echo $toprint;
			}
			echo stripslashes($settings['after_list']);
		}
	
		function setupWidget() {
			if (!function_exists('register_sidebar_widget')) return;
			function widget_fotoRss($args) {
				extract($args);
				$options = get_option('widget_fotoRss');
				$title = $options['title'];
				echo $before_widget . $before_title . $title . $after_title;
				get_fotoRss();
				echo $after_widget;
			}
			function widget_fotoRss_control() {
				$options = get_option('widget_fotoRss');
				if ( $_POST['fotoRss-submit'] ) {
					$options['title'] = strip_tags(stripslashes($_POST['fotoRss-title']));
					update_option('widget_fotoRss', $options);
				}
				$title = htmlspecialchars($options['title'], ENT_QUOTES);
				$settingspage = trailingslashit(get_option('siteurl')).'wp-admin/options-general.php?page='.basename(__FILE__);
				echo 
				'<p><label for="fotoRss-title">Titlu:<input class="widefat" name="fotoRss-title" type="text" value="'.$title.'" /></label></p>'.
				'<p>Pentru a edita alte setari, vizitati <a href="'.$settingspage.'">fotoRss Configurare</a>.</p>'.
				'<input type="hidden" id="fotoRss-submit" name="fotoRss-submit" value="1" />';
			}
			register_sidebar_widget('fotoRss', 'widget_fotoRss');
			register_widget_control('fotoRss', 'widget_fotoRss_control');
		}
	
		function setupSettingsPage() {
			if (function_exists('add_options_page')) {
				add_options_page('fotoRss Settings', 'fotoRss', 8, basename(__FILE__), array(&$this, 'printSettingsPage'));
			}
		}
	
		function printSettingsPage() {
			$settings = $this->getSettings();
			if (isset($_POST['save_fotoRss_settings'])) {
				foreach ($settings as $name => $value) {
					$settings[$name] = $_POST['fotoRss_'.$name];
				}
//				$settings['cache_sizes'] = array();
//				foreach (array("square", "thumbnail", "medium") as $size) {
//					if ($_POST['fotoRss_cache_'.$size]) $settings['cache_sizes'][] = $size;
//				}
				update_option('fotoRss_settings', $settings);
				echo '<div class="updated"><p>datele de configurare fotoRss au fost salvate!</p></div>';
			}
			if (isset($_POST['reset_fotoRss_settings'])) {
				delete_option('fotoRss_settings');
				echo '<div class="updated"><p>datele de configurare fotoRss au fost resetate la valorile initiale!</p></div>';
			}
			include ("fotorss-settingspage.php");
		}
	}
}
$fotoRss = new fotoRss();
add_action( 'admin_menu', array(&$fotoRss, 'setupSettingsPage') );
add_action( 'plugins_loaded', array(&$fotoRss, 'setupWidget') );
register_activation_hook( __FILE__, array( &$fotoRss, 'setupActivation' ));

function get_fotoRss($settings = array()) {
	global $fotoRss;
	if (func_num_args() > 1 ) {
		$old_array = func_get_args();
		$fotoRss->printGallery($fotoRss->fixArguments($old_array));
	}
	else $fotoRss->printGallery($settings);
}

?>