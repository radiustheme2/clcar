<?php
/**
 * @author        RadiusTheme
 * @package       classified-listing/templates
 * @version       1.0.0
 *
 * @var WP_Query $rtcl_related_query
 * @var array $slider_options
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use radiustheme\ClCar\Helper;

$related_count = $rtcl_related_query->post_count;

if ( ! $rtcl_related_query->have_posts() ) {
	return;
}

$posts_per_page = $rtcl_related_query->get( 'posts_per_page' );
if ( $posts_per_page <= $related_count ) {
	$slider_options['loop'] = true;
}

?>
<div class="rtcl rtcl-related-listing-wrap rtcl-listings">
    <div class="rtcl-related-title title-inner-wrapper">
        <h2 class="rt-section-title"><?php esc_html_e( "Explore Similar Car", "classified-listing" ); ?></h2>
        <div class="rt-heading-shape"><i class="icon-wheel"></i></div>
    </div>
    <div class="rtcl-related-listings">
        <div class="rtcl-related-slider-wrap">
            <div class="rtcl-related-slider rtcl-carousel-slider" id="rtcl-related-slider"
                 data-options="<?php
				 // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				 echo htmlspecialchars( wp_json_encode( $slider_options ) ); // WPCS: XSS ok. ?>">
                <div class="swiper-wrapper">
					<?php
					global $post;
					while ( $rtcl_related_query->have_posts() ):
						$rtcl_related_query->the_post();
						$listing = rtcl()->factory->get_listing( get_the_ID() );
						?>
                        <div class="swiper-slide rtcl-related-slider-item listing-item rtcl-listing-item rtcl-listing-card">
                            <div class="related-item-inner grid-item listing-box">
                                <?php Helper::get_custom_listing_template( 'grid' ); ?>
                            </div>
                        </div>
					<?php endwhile;
					wp_reset_postdata();
					?>
                </div>
                <?php if ( $related_count > $posts_per_page ){ ?>
                    <div class="swiper-button-next"><i class="fa-solid fa-chevron-right"></i></div>
                    <div class="swiper-button-prev"><i class="fa-solid fa-chevron-left"></i></div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>