<?php /*

Plugin Name: Network Site Names
Plugin URI: https://qosmicro.com/plugins/network-site-names
Description: Network Site Names lets you add custom site names to be shown in the admin bar menu.
Author: Jose Manuel Sanchez
Author URI: https://qosmicro.com/

Text Domain: network-site-names
Domain Path: /languages

Version: 1.0.0

License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl.html

 _____     _____ _____ _             
|     |___|   __|     |_|___ ___ ___ 
|  |  | . |__   | | | | |  _|  _| . |
|__  _|___|_____|_|_|_|_|___|_| |___|
   |__|                              

================================================================== */


// Blocking direct access
defined( 'ABSPATH' ) or die( 'Direct access not allowed!' );


//* Plugin Class
class Network_Site_Names_Class {


	// Construct.
	public function __construct() {
		
		if( is_multisite() ) {
			
			//* Add Network Site Name Field
			add_filter( 'admin_init', array( $this, 'add_network_site_name_field' ), 0 );

			//* Use Network Name in List
			add_action( 'admin_bar_menu', array( $this, 'add_network_site_name_list' ), 15 );

			//* Display Network Name of Current Blog
			add_action( 'admin_bar_menu', array( $this, 'add_network_site_name_bar' ), 900 );

		}
		
	}
	
	
	//* Add Network Site Name Field
	function add_network_site_name_field() {

		//* Make sure all Sites Have the Site Name Field
		if( function_exists( 'get_sites' ) && class_exists( 'WP_Site_Query' ) ) {
			$original_site = get_current_blog_id();
			$sites = get_sites();
			foreach ( $sites as $site ) {
				switch_to_blog( $site->blog_id );
				$has_site_name = get_blog_option( $site->blog_id, 'network_site_name' );
				if( empty( $has_site_name ) ) update_blog_option( $site->blog_id, 'network_site_name', '' );
			}
			switch_to_blog( $original_site );
		}

		# Register Setting
		register_setting( 'general', 'network_site_name', 'esc_attr' );
		
		# Add Setting Field
		add_settings_field( 'network_site_name_id', '<label for="network_site_name_id">'.__('Network Site Name', 'network-site-names' ).'</label>', function() {
			$value = get_option( 'network_site_name', '' );
			echo '<input type="text" id="network_site_name_id" name="network_site_name" value="' . esc_attr( $value ) . '" class="regular-text" />';
		}, 'general' );
		
	}
	
	
	//* Add Network Site Name Field
	function add_network_site_name_list( $wp_admin_bar ) {
			
		foreach( $wp_admin_bar->user->blogs as $blog ) {
			$network_name = get_blog_option( $blog->userblog_id, 'network_site_name' );
			if( $network_name ) $wp_admin_bar->user->blogs[ $blog->userblog_id ]->blogname = $network_name;
		}
		return $wp_admin_bar;
		
	}
	
	
	//* Add Network Site Name Field
	function add_network_site_name_bar( $wp_admin_bar ) {
		
		$network_name = get_blog_option( get_current_blog_id(), 'network_site_name' );
		if( $network_name ) 
			$wp_admin_bar->add_node( array( 'id' => 'site-name', 'title' => $network_name  ));
		return $wp_admin_bar;
		
	}

	
}
// Instantiate the class
$_add_network_site_name = new Network_Site_Names_Class();


























/* --- end */