<?php
/**
 * Plugin Name: Doing AutoComplete
 * [autocomplete_enqueue_scripts description
 *
 * @return [type] [description]
 */
function autocomplete_enqueue_scripts() {
	// Enqueue jQuery UI and autocomplete
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-autocomplete' );
}
add_action( 'wp_enqueue_scripts', 'autocomplete_enqueue_scripts' );


function setup_autocomplete_js() {
	$args = array(
		'post_type' => array( 'post' ),
		'post_status' => 'publish',
		'posts_per_page'   => -1, // all posts
		'posts_per_page'   => 10,
	);
	$posts = get_posts( $args );

	if ( $posts ) :
		foreach ( $posts as $key => $post ) {
			$source[ $key ]['ID'] = $post->ID;
			$source[ $key ]['label'] = $post->post_title; // The name of the post
			// $source[$key]['label'] = $comma_separated_tags;
			$source[ $key ]['permalink'] = get_permalink( $post->ID );
		}

		?>
		<script type="text/javascript">
			jQuery(document).ready(function($){
				var posts = <?php echo json_encode( array_values( $source ) ); ?>;

				jQuery( '#lookup' ).autocomplete({
				// jQuery( 'input[name="s"]' ).autocomplete({
					source: posts,
					minLength: 2,
					select: function(event, ui) {
						var permalink = ui.item.permalink; // Get permalink from the datasource
						window.location.replace(permalink);
					}
				});
			});
		</script>
		<?php
	endif;
	echo '<pre>';
	print_r( $source );
	echo '</pre>';
}
add_action( 'wp_footer', 'setup_autocomplete_js' );
