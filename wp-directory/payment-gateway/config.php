<?php
/**
 *  File Type: Payment Configuration
 *
 */
 
 $dir = wp_directory::plugin_dir().'/payment-gateway/gateways/';
 $dh = opendir($dir);
 if (is_dir($dir)) {
	if ($dh = opendir($dir)) {
		while (($file = readdir($dh)) !== false) {
			$ext = pathinfo($file, PATHINFO_EXTENSION);
			if( $ext == 'php' ) {
				include(wp_directory::plugin_dir().'/payment-gateway/gateways/'.$file);
			}
		}
		closedir($dh);
	}
 }
 
?>