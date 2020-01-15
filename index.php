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
 * Render Google Map of today's program.
 */
?>
<div id="map"></div>
<?php
$key     = get_option( 'google_maps_api_key' );
$markers = array();

/**
 * Get future activities.
 */
$today = date( 'Y-m-d' );
$query = new WP_Query(
	array(
		'post_type'      => 'activity',
		'posts_per_page' => '50',
		'paged'          => '1',
		'order'          => 'ASC',
		'orderby'        => 'meta_value',
		'meta_key'       => 'begin',
		'meta_type'      => 'DATETIME',
		'meta_compare'   => '>',
		'meta_value'     => $today,
	)
);

/**
 * Get each activity location.
 */
foreach ( $query->posts as $post ) {
	$location = wp_get_post_terms( $post->ID, array( 'location' ) )[0];
	$meta     = get_post_meta( $post->ID );
	$begin    = array_key_exists( 'begin', $meta ) ? $meta['begin'][0] : null;
	$end      = array_key_exists( 'end', $meta ) ? $meta['end'][0] : null;
	$latLng   = get_term_meta( $location->term_id, 'latLng', true );

	$markers[] = array(
		'begin'    => $begin,
		'end'      => $end,
		'latLng'   => $latLng,
		'location' => $location->name,
		'name'     => $post->post_title,
	);
}
?>
	<style>
		#map {
			height: 600px;
			margin: 0 auto;
			width: 600px		}
	</style>
	<script>
		const markers = <?php echo json_encode( $markers ); ?>;

		function initMap() {
			const map = new google.maps.Map(document.getElementById('map'), {
				center: { lat: 41.763031, lng: -73.044465 },
				zoom: 17
			});

			/* Render saved markers */ 
			if(markers){
				markers.forEach(elem => {
					const contentString = `<h1>${elem['name']}</h1>
					<p>From ${elem['begin']} at the ${elem['location']}</p>`;
					
					const cords = elem['latLng'].replace('(', '').replace(')', '').split(',');
					const infowindow = new google.maps.InfoWindow({
						content: contentString
					  });
					const position = new google.maps.LatLng(cords[0], cords[1]);
					
					const marker = new google.maps.Marker({
						animation: google.maps.Animation.DROP,
						map,
						position,
						title: elem['name']
					});

					marker.addListener('click', function() {
						infowindow.open(map, marker);
					  });
				})
			}
		}
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo get_option( 'google_maps_api_key' ); ?>&callback=initMap"async defer></script>
</body>

</html>
