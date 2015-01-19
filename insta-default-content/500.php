<?php
/*
This is the 500 Internal Server Error Template

*/
// Exit if accessed directly
if ( !defined('ABSPATH') ) {
	exit;
}
get_header();
?>

<article class="default-page">
	<header><h1>500 Internal Server Error</h1></header>

	<section>
		<p>The application is temporarily out of service. Please try again later.</p>
		<p>Sorry for inconvenience.</p>
	</section>
</article>

<?php get_footer(); ?>
