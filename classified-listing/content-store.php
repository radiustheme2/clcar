<?php
/**
 * Store single content
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.2.31
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use radiustheme\ClCar\Helper;

$store_layout = Functions::get_option_item( 'rtcl_membership_settings', 'store_layout', 'grid' );

if ( $store_layout == 'grid' ) {
    Helper::get_custom_listing_template( 'store/grid' );
} else {
    Helper::get_custom_listing_template( 'store/list' );
}

