<!DOCTYPE html>
<html>

<head>
	<title>blank</title>
	<meta name="viewport" content="initial-scale=1.0">
	<meta charset="utf-8">
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
</body>

</html>
