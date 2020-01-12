<!DOCTYPE html>
<html>

<head>
	<title>blank</title>
	<meta name="viewport" content="initial-scale=1.0">
	<meta charset="utf-8">
	<?php wp_head(); ?>
</head>

<body>
<?php
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		// https://developer.wordpress.org/themes/basics/the-loop/#what-the-loop-can-display
		next_post_link();
		previous_post_link();
		the_author();
		the_category();
		the_content();
		the_excerpt();
		the_ID();
		the_meta();
		the_shortlink();
		the_tags();
		the_time();
		the_title();
	endwhile;
endif;
?>
<?php
/**
 * Render Google Map of all locations
 */
?>
<div id="map"></div>
<?php
$key     = get_option( 'google_maps_api_key' );
$markers = array();
$terms   = get_terms(
	array(
		'taxonomy'   => 'location',
		'hide_empty' => false,
	)
);
foreach ( $terms as $term ) {
	$location  = get_term_meta( $term->term_id, 'latLng', true );
	$markers[] = esc_attr( $location );
}
?>
	<style>
		#map {
			height: 600px;
			margin: 0 auto;
			width: 600px		}
	</style>
	<script>
		const markers = <?php echo json_encode($markers) ?>;

		function initMap() {
			const map = new google.maps.Map(document.getElementById('map'), {
				center: { lat: 41.763031, lng: -73.044465 },
				zoom: 17
			});

			/* Render saved marker */ 
			if(markers){
				markers.forEach(elem => {
					const cords = elem.replace('(', '').replace(')', '').split(',');
					const position = new google.maps.LatLng(cords[0], cords[1]);
					new google.maps.Marker({
						position,
						map
					});
				})
			}
		}
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo get_option( 'google_maps_api_key' ); ?>&callback=initMap"async defer></script>
</body>

</html>
