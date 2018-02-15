<?php
/**
 * Template Name: Test Template Page
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

<div id="main-content" class="main-content">



	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main" >
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();

					// Include the page content template.
					get_template_part( 'content', 'page' );

					
				endwhile;
			?>
                    <?php //echo do_shortcode("[crellyslider alias='slider_1']"); ?>
		</div><!-- #content -->
                
	</div><!-- #primary -->
</div><!-- #main-content -->

<?php
//get_sidebar();
get_footer();
