<?php
/**
 * @package ClassifiedListing/Templates
 * @version 1.5.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use radiustheme\ClCar\Helper;

if ( isset( $_GET['layout'] ) && in_array( $_GET['layout'], ['1','2','3','4','5'] ) ) {
	$style = absint( $_GET['layout'] );
}
else {
	$style = Functions::get_option_item( 'rtcl_archive_listing_settings', 'listing_style', '1' );
}
$style = absint( $style );

Helper::get_custom_listing_template( 'archive/content-grid-'. $style );

?>

