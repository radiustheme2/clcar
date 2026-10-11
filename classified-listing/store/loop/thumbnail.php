<?php

use radiustheme\ClCar\RDTheme;
use RtclStore\Helpers\Functions as StoreFunction;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $store;

use Rtcl\Helpers\Functions as RtclFunctions;
$settings = RtclFunctions::get_option( 'rtcl_membership_settings' );
$visibility = isset( $settings['store_archive_visibility'] ) && is_array( $settings['store_archive_visibility'] ) ? $settings['store_archive_visibility'] : [];
if (!empty($visibility)) {
	$show_ad_count = in_array( 'show_ad_count', $visibility ) ? true : false;
} else {
    $show_ad_count = RDTheme::$options['show_ad_count'] ?? false;
}
?>
<div class="store-thumb">
	<?php if( $show_ad_count && ! StoreFunction::is_single_store()){
		$store->the_metas();
	} ?>
	<?php $store->the_logo( 'full' ); ?>
</div>
