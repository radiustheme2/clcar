<?php
/**
 * Store single content
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.3.21
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use RtclStore\Helpers\Functions as StoreFunctions;
use radiustheme\ClCar\Helper;
use radiustheme\ClCar\Listing_Functions;
use radiustheme\ClCar\RDTheme;
use Rtcl\Controllers\Hooks\TemplateHooks;
use RtclPro\Controllers\Hooks\TemplateHooks as ProTemplateHooks;

global $store;

if ( StoreFunctions::is_store_expired() ) {
	do_action( 'rtcl_single_store_expired_content' );

	return;
}

$store_ads_query = Listing_Functions::store_query();
 
RDTheme::$listing_max_page_num = $store_ads_query->max_num_pages;

$post_num   = Listing_Functions::listing_post_num( $store_ads_query );

$count_text = Listing_Functions::listing_count_text( $post_num );

$banner_class = $store->get_banner_url() ? '' : ' clcar-noimage';

$member_since = esc_html__( 'Member since - ', 'clcar' ) . get_the_time( get_option( 'date_format' ) );

if ( isset( $_GET['view'] ) && in_array( $_GET['view'], [ 'grid', 'list' ], true ) ) {
	$view = esc_attr( $_GET['view'] );
} else {
	$view = Functions::get_option_item( 'rtcl_archive_listing_settings', 'default_view', 'list' );
}
$list_class = ( $view == 'grid' ) ? 'rtcl-grid-view' : 'rtcl-list-view';

do_action( 'rtcl_before_single_store' );
?>

    <div class="clcar-store-banner-wrap">
		<?php do_action( 'rtcl_before_single_store_content' ); ?>
        <div class="clcar-banner-img<?php echo esc_attr( $banner_class ); ?>">
			<?php if ( ! $banner_class ): ?>
				<?php $store->the_banner(); ?>
			<?php endif; ?>
        </div>
        <div class="clcar-banner-content">
			<?php if ( $store->get_logo_url() ): ?>
                <div class="clcar-store-logo">
					<?php $store->the_logo(); ?>
                </div>
			<?php endif; ?>
            <div class="clcar-store-info">
                <!-- Store Title -->
                <h2 class="clcar-store-title"><?php $store->the_title(); ?></h2>

				<?php if ( $store->get_category() ) : ?>
                    <h4 class="clcar-store-category">
                        <?php
                        echo wp_kses( $store->get_category(), [
	                        'a' => [
		                        'href'  => [],
		                        'title' => [],
	                        ],
                        ] );
                        ?>
                    </h4>
				<?php endif; ?>

				<?php if ( $store->get_the_slogan() && RDTheme::$options['single_store_slogan']): ?>
                    <div class="clcar-store-slogan"><?php $store->the_slogan(); ?></div>
				<?php endif; ?>

                <!-- Store Information -->
                <ul class="clcar-store-meta">
					<?php if ( $store_address = $store->get_address() ): ?>
                        <li><i class="icon-location2"></i><?php echo esc_html( $store_address ); ?></li>
					<?php endif; ?>
					<li><i class="icon-user"></i><?php echo esc_html( $member_since );?></li>
					<?php if ( $stor_view_count = Listing_Functions::rt_get_post_view_count( $store->get_id() ) ) : ?>
					<li>
						<i class="icon-view"></i><?php $label = $stor_view_count < 10 ? esc_html__( 'View: ', 'clcar' ) : esc_html__( 'Views: ', 'clcar' ); echo wp_kses_post( sprintf( "<span class='count-label'>%s</span><span class='count-number'>%s</span>",
							esc_html( $label ),
							esc_html( Helper::rt_number_shorten( $stor_view_count, 1 ) )
						) );
						?>
					</li>
					<?php endif; ?>
					
					<?php if ( $store->is_rating_enable() && $store->get_review_counts() ):
						$r_label = absint( $store->get_review_counts() ) < 2 ? esc_html__( 'Review', 'clcar' ) : esc_html__( 'Reviews', 'clcar' );
						?>
                        <li class="rating">
                            <i class="fa-solid fa-trophy"></i>
							<?php echo wp_kses_post( Functions::get_rating_html( $store->get_average_rating(), $store->get_review_counts() ) ); ?>
                            <span class="reviews-rating-count">
                                <?php echo esc_html( sprintf( "(%s %s)", $r_label, absint( $store->get_review_counts() ) ) ); ?>
                            </span>
                        </li>
					<?php endif; ?>

                </ul>
            </div>
        </div>
    </div>

    <div class="row store-information">
        <div class="col-lg-9">
			<div class="store-listing-wrapper">
				<div class="rtcl-listings-actions">
					<div class="rtcl-result-count"><?php echo esc_html( $count_text ); ?></div>
					<?php TemplateHooks::catalog_ordering(); ?>
					<?php ProTemplateHooks::view_switcher(); ?>
        		</div>
				<div class="rtcl rtcl-listings store-ad-listing-wrapper2 rtcl-listings-<?php echo esc_attr( $view ); ?>">
                    <?php
                    if ( $post_num ):
                        $temp = Helper::wp_set_temp_query( $store_ads_query );
                        Listing_Functions::listing_query( $view, $store_ads_query );
                        Helper::get_template_part( 'template-parts/pagination' );
                        Helper::wp_reset_temp_query( $temp );
                    else:
                        printf('<h4>%s</h4>',esc_html__('No Listing published from this store yet','clcar'));
                    endif;
                    ?>
        		</div>
			</div> 
		</div>	   
        <div class="col-lg-3">
            <div id="sticky_sidebar" class="store-info sidebar-widget">
				<?php Helper::get_custom_store_template( 'sidebar-store', true, get_defined_vars() ); ?>
            </div>
        </div>
    </div>

<?php
do_action( 'rtcl_after_single_store' );