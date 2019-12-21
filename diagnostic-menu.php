<?php
/**
 * Plugin Name: Diagnostic Menu
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

	$args = array(
		'post_type' => 'gist',
		'posts_per_page' => -1,
	);
	$gists = get_posts( $args );
	foreach ( $gists as $key => $value ) {
		$custom[] = get_post_custom( $value->ID );
	}
	foreach ( $custom as $key => $value ) {
		$gist_list[] = $value['gist_id'][0];
	}
	echo '<pre>';
	print_r( $gist_list );
	echo '</pre>';

	set_transient( 'gist_list', $gist_list, 12 * HOUR_IN_SECONDS );
	echo '<h3>CV Frontend Post Loaded</h3>';
	echo '<h5>http://carlofontanos.com/loading-wp_editor-via-ajax/d</h5>';
	$this_plugin = get_plugin_data( __FILE__ );
	echo '<h4>This plugin is ' . sprintf(
		__( '%1$s, version %2$s, is %3$s.', 'diagnostic-dashboard-menu' ),
		$this_plugin['Name'],
		$this_plugin['Version'],
		$this_plugin['Description']
	) . '</h4>';
	$id = 93915;
	$postmeta = ( get_post_meta( $id, 'suggest_show_on_cats', 1 ) ?: 'nuthin' );
	?>
	<input type="text" placeholder="Start typing a category name" class="widefat" name="suggest_show_on_cats" id="suggest_show_on_cats" value="<?php echo $postmeta; ?>" size="20" />
	<?php
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

// add_action( 'plugins_loaded', 'testing_print_declared_classes' );
function testing_print_declared_classes() {
	add_action( 'admin_footer', 'print_declared_classes' );
}

function print_declared_classes() {
	echo '<pre style="text-align:center;">';
	print_r( get_declared_classes() );
	echo '</pre>';

}

add_action(
	'admin_enqueue_scripts', function() {
		wp_enqueue_script( 'local-suggest', plugins_url( 'js/local-suggest.js', __FILE__ ), array( 'suggest' ), '1.3', true );
	}
);
add_action(
	'wp_ajax_suggest_ajax_page_search', function() {

			$search = wp_unslash( $_GET['q'] );

			$comma = _x( ',', 'page delimiter' );
		if ( ',' !== $comma ) {
			$search = str_replace( $comma, ',', $search );
		}
		if ( false !== strpos( $search, ',' ) ) {
			$search = explode( ',', $search );
			$search = $search[ count( $search ) - 1 ];
		}
			$search = trim( $search );

			$term_search_min_chars = 2;

			$the_query = new WP_Query(
				array(
					's' => $search,
					'posts_per_page' => 15,
					'post_type' => 'page',
				)
			);

		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$results[] = get_the_title();
			}
			/* Restore original Post Data */
			wp_reset_postdata();
		} else {
			$results = 'No results';
		}

			echo join( $results, "\n" );
			wp_die();
	}
);
