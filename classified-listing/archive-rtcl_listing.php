<?php
/**
 * @package ClassifiedListing/Templates
 * @version 1.5.4
 */

use radiustheme\ClCar\Helper;
use radiustheme\ClCar\RDTheme;
use Rtcl\Helpers\Functions;
use RtclPro\Helpers\Fns;

defined( 'ABSPATH' ) || exit;

get_header( 'listing' );

/**
 * Hook: rtcl_before_main_content.
 *
 * @hooked rtcl_output_content_wrapper - 10 (outputs opening divs for the content)
 */
do_action( 'rtcl_before_main_content' );
?>
    <div class="container rtcl-widget-border-enable rtcl-widget-is-sticky listing-title-wrap-enable">
		<?php
            do_action( 'clcar_listing_loop_top_actions' );

		    if ( 'full-width' == RDTheme::$layout || ! Helper::has_sidebar() ) {
                echo wp_kses_post( render_ajax_filter_by_id( 'ajax-filter' ) );
            }
	    ?>

        <div class="row clcar-listing">
            <div class="<?php Helper::the_layout_class(); ?>">
				<?php if ( rtcl()->wp_query()->have_posts() ) { ?>
                    <div class="listing-content-wrap">
						<?php
						/**
						 * Hook: rtcl_before_listing_loop.
						 *
						 * @hooked TemplateHooks::output_all_notices() - 10
						 * @hooked TemplateHooks::listings_actions - 20
						 *
						 */
						
						 do_action( 'rtcl_before_listing_loop' );

						Functions::listing_loop_start();

						/**
						 * Top listings
						 */
						if ( Fns::is_enable_top_listings() ) {
							do_action( 'rtcl_listing_loop_prepend_data' );
						}

						while ( rtcl()->wp_query()->have_posts() ) : rtcl()->wp_query()->the_post();

							/**
							 * Hook: rtcl_listing_loop.
							 */
							do_action( 'rtcl_listing_loop' );

							Functions::get_template_part( 'content', 'listing' );

						endwhile;

						Functions::listing_loop_end();
						?>
                    </div>
					<?php
					/**
					 * Hook: rtcl_after_listing_loop.
					 *
					 * @hooked TemplateHook::pagination() - 10
					 */
					do_action( 'rtcl_after_listing_loop' );
				} else {
					/**
					 * Top listings
					 */
					if ( class_exists('RtclPro') && Fns::is_enable_top_listings() ) {
						Functions::listing_loop_start();
						do_action( 'rtcl_top_listings' );
						Functions::listing_loop_end();
					}

					/**
					 * Hook: rtl_no_listings_found.
					 *
					 * @hooked no_listings_found - 10
					 */
					do_action( 'rtcl_no_listings_found' );
				}
				?>
            </div>
			<?php
                if ( Helper::has_sidebar() && 'full-width' !== RDTheme::$layout ) {
                    do_action( 'rtcl_sidebar' );
                }
			?>
        </div>

    </div>

<?php



/**
 * Hook: rtcl_after_main_content.
 *
 * @hooked rtcl_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'rtcl_after_main_content' );

get_footer( 'listing' );
