<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.3.5
 *
 * @var $content_class string
 * @var $sidebar_position string
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use radiustheme\ClCar\Helper;
use radiustheme\ClCar\Listing_Functions;
use radiustheme\ClCar\RDTheme;
use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;

global $listing;

$custom_field_ids = Functions::get_custom_field_ids( $listing->get_last_child_category_id() );
$video_urls       = [];
if ( ! Functions::is_video_urls_disabled() ) {
	$video_urls = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
	$video_urls = ! empty( $video_urls ) && is_array( $video_urls ) ? $video_urls : [];
}
$hide_listing_map   = get_post_meta( get_the_ID(), 'hide_map', true );
$group_id           = isset( RDTheme::$options['custom_group_individual'] ) ? RDTheme::$options['custom_group_individual'] : 0;
$get_class_by_title = get_post_field( 'post_name', $group_id ). ' '.'listing-single-layout-2';

?>

<div id="rtcl-listing-<?php the_ID(); ?>" <?php Functions::listing_class( $get_class_by_title, $listing ); ?>>

    <div class="container rtcl-widget-border-enable rtcl-widget-is-sticky">
        <div class="rtcl-main-content-wrapper">
            <div class="rtcl-content-wrapper">

                <div class="listing-gallery-wrapper">
					<?php do_action('rt_listing_galley' ); ?>
                </div>

                <!--Listing Heading-->
	            <?php Helper::get_custom_listing_template( 'listing-heading' ); ?>
                <!--End Listing Heading-->

                <div class="row rtcl-listing-content-area">
                    <div class="<?php echo esc_attr( implode( ' ', $content_class ) ); ?>">
                        <div class="rtcl-single-listing-details">

							<?php Helper::get_custom_listing_template( 'single-listing-content' ); ?>

							<?php if ( RDTheme::$options['listing_detail_sidebar'] && $sidebar_position === "bottom" ): ?>
                                <!-- Sidebar -->
								<?php do_action( 'rtcl_single_listing_sidebar' ); ?>
							<?php endif; ?>
                        </div>
                    </div>

					<?php if ( RDTheme::$options['listing_detail_sidebar'] && in_array( $sidebar_position, [ 'left', 'right' ] ) ): ?>
						<?php do_action( 'rtcl_single_listing_sidebar' ); ?>
					<?php endif; ?>
                </div>
            </div>
        </div>
    </div>

	<?php
    if ( RDTheme::$options['show_related_listing']) :
        ob_start();
        $listing->the_related_listings();
        $related_content = ob_get_clean();
        if ( trim( $related_content ) && ! str_contains( $related_content, 'no listings found' ) ) :
		?>
        <div class="clcar-related-listing">
            <div class="container">
                <div class="row">
                    <div class="col-12 listing-title-wrap-enable">
						<?php $listing->the_related_listings(); ?>
                    </div>
                </div>
            </div>
        </div>
	    <?php endif; ?>
	<?php endif; ?>
</div>