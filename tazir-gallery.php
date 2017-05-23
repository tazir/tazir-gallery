<?php
/*
Plugin Name: Gallery with latest posts from specific category
Version: 1.0
Author: Tazir
Author URI: http://wp.bdidut.info/
License: GNU GPLv3
Parameters:
	category - parent category
	orderby - how to order the posts (DESC)
	max - how many post to show
	more - text for "read more"
	bgcolor - background color (for the overlay)
	color - font color
*/

function nut_gallery($atts) {

	$atts = shortcode_atts( array(
	    'category' => '',
	    'orderby' => 'date',
	    'max' => '12',
	    'more' => 'Read More...',
	    'bgcolor' => '00A859',
	    'color' => 'fff'
	), $atts, 'nutgallery' );

	/* Find sub-categories for the given category */
	$cats = get_categories('child_of='. $atts['category']);
	$ChildList = "{$atts['category']},";
	foreach ($cats as $findChild)
		$ChildList .= $findChild->term_id.',';
	echo "<!-- $ChildList -->";

	$args = array(
		'cat' => $ChildList,
		'post_type' => 'post',
		'orderby' => $atts['orderby'],
		'order'   => 'DESC',
		'posts_per_page' => $atts['max']
	);
	$the_query = new WP_Query( $args );
	ob_flush();
	// The Loop
	if ( $the_query->have_posts() ) { ?>
		<div id='tazir-slider'>
		<?php
		while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
			<div class="post-rel-element">
				<a href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark" class="post-rel-img image-link" role="link">
						<?php echo get_the_post_thumbnail(); ?>
				</a>
				<div class='post-rel-overlay'>
					<span id='post-rel-title'><?php the_title(); ?></span>
					<span class='post-rel-excerpt'><?php echo the_excerpt(); ?></span>
					<span class='post-rel-more'><a href="<?php echo get_permalink(); ?>" title="<?php echo the_title(); ?>" rel="bookmark" role="link">
							<?php echo $atts['more']; ?></a></span>
					
				</div>
			</div>
		<?php
		endwhile; ?>
		</div>
		<div class="clearfix"></div>
		<style>
		#tazir-slider .post-rel-element {
			width: 16.66%;
			float: right;
		}
		#tazir-slider .post-rel-overlay {
			position: relative;
			width: 100%;
			opacity: 0.9;
			height: 0px;
			overflow: hidden;
			color: #<?php echo $atts['color'];?>;
			background: #<?php echo $atts['bgcolor'];?>;
		}
		#tazir-slider .post-rel-overlay a {
			color: #fff;
			cursor: pointer;
		}
		#tazir-slider #post-rel-title {
			display: block;
			text-align: center;
			height: 30px;
			overflow: hidden;
		}
		#tazir-slider .post-rel-excerpt {
			font-size: 14px;
			line-height: 1.5em;
			overflow: hidden;
			display: inline-block;
			padding: 0 10px;
			height: 100px;
		}
		#tazir-slider .post-rel-more {
			padding: 0 10px;
		}
		<?php
		if($the_query->post_count > 8) { ?>
			@media screen and  (max-width: 1023px) and (min-width: 781px) {
				#tazir-slider .post-rel-element:nth-child(n+9) {
					display: none;
				}
				#tazir-slider .post-rel-element {
					width: 25%;
				}
			}
		<?php }
		if($the_query->post_count > 4) { ?>
			@media screen and (max-width: 780px) {
				#tazir-slider .post-rel-element:nth-child(n+5) {
					display: none;
				}
				#tazir-slider .post-rel-element {
					width: 50%;
				}
			}
		<?php } ?>
		</style>
		
		<script>
			jQuery(document).ready(function() {
				jQuery('#tazir-slider .post-rel-element').hover(
					function() {
						jQuery( this ).find(".post-rel-overlay").height( jQuery( this ).height() );
						var position = jQuery( this ).offset();
						jQuery( this ).find(".post-rel-overlay").offset({top: position.top, left: position.left});
						jQuery( this ).height( jQuery( this ).find(".post-rel-overlay").height() );
					}, function() {
						jQuery( this ).find(".post-rel-overlay").height(0);
						var position = jQuery( this ).offset();
						jQuery( this ).find(".post-rel-overlay").offset({top: position.top + jQuery( this ).height() , left: position.left});
				});
				// var max_img = 0;
				jQuery('#tazir-slider .post-rel-element').each(function() {
					var img_height = jQuery( this ).find(".post-rel-img").height();
					if(img_height > 80) {
						jQuery( this ).find(".post-rel-excerpt").height(img_height - 45);
					}
				});
			});
		</script>
		<?php
	}
	ob_flush();

}
// register shortcode
add_shortcode('nutgallery', 'nut_gallery');
?>
