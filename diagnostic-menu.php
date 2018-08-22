<?php
/**
 * Plugin Name: A Diagnostic Menu
 * Description: A beautiful dashboard menu that transforms function names to create Admin screen names. Use the `diagnostic_dashboard_page` as a canvas for diagnosing issues.
 * Author: pbrocks
 * Version: 1.0.1
 * Text-Domain: diagnostic-dashboard-menu
 */
/**
 * Add a page to the dashboard menu.
 *
 * @since 1.0.0
 *
 * @return array
 */
add_action( 'admin_menu', 'diagnostic_dashboard' );
function diagnostic_dashboard() {
	global $this_menu_slug;
	$this_menu_slug = preg_replace( '/_+/', '-', __FUNCTION__ );
	$label = ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) );
	add_dashboard_page( __( $label, 'diagnostic-dashboard-menu' ), __( $label, 'diagnostic-dashboard-menu' ), 'manage_options', $this_menu_slug . '.php', 'diagnostic_dashboard_page' );
}


/**
 * Debug Information
 *
 * @since 1.0.0
 *
 * @param bool $html Optional. Return as HTML or not
 *
 * @return string
 */
function diagnostic_dashboard_page() {
	echo '<div class="wrap">';
	echo '<h2>' . ucwords( preg_replace( '/_+/', ' ', __FUNCTION__ ) ) . '</h2>';
	$screen = get_current_screen();
	echo '<h4 style="color:rgba(250,128,114,.7);">Current Screen is <span style="color:rgba(250,128,114,1);">' . $screen->id . '</span></h4>';
	$this_plugin = get_plugin_data( __FILE__ );
	echo '<h4>This plugin is ' . sprintf(
		__( '%1$s, version %2$s, is %3$s.', 'diagnostic-dashboard-menu' ),
		$this_plugin['Name'],
		$this_plugin['Version'],
		$this_plugin['Description']
	) . '</h4>';
	echo 'Plugin info <pre>';
	print_r( $this_plugin );
	echo '</pre>';

	$this_theme = wp_get_theme();
	echo '<h4>Theme is ' . sprintf(
		__( '%1$s and is version %2$s', 'diagnostic-dashboard-menu' ),
		$this_theme->get( 'Name' ),
		$this_theme->get( 'Version' )
	) . '</h4>';
	echo '<h4>Templates found in ' . get_template_directory() . '</h4>';
	echo '<h4>Stylesheet found in ' . get_stylesheet_directory() . '</h4>';
	echo '</div>';
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'pmpro_diagnostic_dashboard_action_links' );
/**
 * Function to add links to the plugin action links
 *
 * @param array $links Array of links to be shown in plugin action links.
 */
function pmpro_diagnostic_dashboard_action_links( $links ) {
	global $this_menu_slug;
	if ( current_user_can( 'manage_options' ) ) {
		$new_links = array(
			'<a href="' . get_admin_url( null, 'index.php?page=' . $this_menu_slug ) . '">' . __( 'View Dashboard', 'diagnostic-dashboard-menu' ) . '</a>',
		);
	}
	return array_merge( $new_links, $links );
}
