<?php
/**
 * @author        RadiusTheme
 * @package       classified-listing/templates
 * @version       1.1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use radiustheme\ClCar\Helper;
use radiustheme\ClCar\RDTheme;
use RtclStore\Helpers\Functions as StoreFunctions;

global $listing;
$sidebar_position = Functions::get_option_item( 'rtcl_single_listing_settings', 'detail_page_sidebar_position', 'right' );

$sidebar_class = [
	'col-lg-3 col-md-12 offset-lg-0',
	'order-2',
];
if ( $sidebar_position == "left" ) {
	$sidebar_class   = array_diff( $sidebar_class, [ 'order-2' ] );
	$sidebar_class[] = 'order-1';
} elseif ( $sidebar_position == "bottom" ) {
	$sidebar_class   = array_diff( $sidebar_class, [ 'col-lg-3 col-md-12 offset-lg-0 ' ] );
	$sidebar_class[] = 'rtcl-listing-bottom-sidebar';
}

?>

<!-- Seller / User Information -->
<div id="sticky_sidebar" class="<?php echo esc_attr( implode( ' ', $sidebar_class ) ); ?>">
    <div class="listing-sidebar">
		<?php
			if ( $listing->can_show_user() ) {
				if ( (RDTheme::$options['show_user_info_on_details'] === 'show_store_info') && StoreFunctions::get_user_store($listing->get_owner_id())) {
					Helper::get_custom_listing_template( 'listing-store-info' );
				} elseif ( RDTheme::$options['show_user_info_on_details'] === 'show_owner_info' ) {
					Helper::get_custom_listing_template( 'listing-owner-info' );
				} else {
					Helper::get_custom_listing_template( 'general-info' );
				}
			}
		?>
		<?php 
			$email = get_post_meta($listing->get_id(),'email',true);
			$has_contact_form = Functions::get_option_item( 'rtcl_single_listing_settings', 'has_contact_form', false, 'checkbox' );
		?>
		<?php if ( $email && $has_contact_form) : ?>
            <div class='rtcl-do-email'>
                <?php $listing->email_to_seller_form(); ?>
            </div>
		<?php endif; ?>
		<?php do_action( 'rtcl_after_single_listing_sidebar', $listing->get_id() ); ?>
		<?php if ( is_active_sidebar( 'single-listing-sidebar' ) ): ?>
            <aside class="sidebar-widget">
				<?php dynamic_sidebar( 'single-listing-sidebar' ); ?>
            </aside>
		<?php endif; ?>
    </div>
</div>
