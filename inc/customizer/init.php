<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


add_action( 'after_setup_theme', 'clcar_load_customizer_settings_properly', 9 );

function clcar_load_customizer_settings_properly() {

	radiustheme\ClCar\Helper::requires( 'customizer/controls/switch-control.php' );
	radiustheme\ClCar\Helper::requires( 'customizer/controls/image-radio-control.php' );
	radiustheme\ClCar\Helper::requires( 'customizer/controls/separator-control.php' );
	radiustheme\ClCar\Helper::requires( 'customizer/controls/gallery-control.php' );
	radiustheme\ClCar\Helper::requires( 'customizer/controls/select2-control.php' );
	radiustheme\ClCar\Helper::requires( 'customizer/controls/heading-control.php' );
	radiustheme\ClCar\Helper::requires( 'customizer/controls/alfa-color.php' );
	radiustheme\ClCar\Helper::requires( 'customizer/typography/typography-controls.php' );
	radiustheme\ClCar\Helper::requires( 'customizer/typography/typography-customizer.php' );
	radiustheme\ClCar\Helper::requires( 'customizer/controls/sanitization.php' );
	radiustheme\ClCar\Helper::requires( 'customizer/customizer.php' );

	// Settings files
	radiustheme\ClCar\Helper::requires( 'customizer/settings/general.php' );
	radiustheme\ClCar\Helper::requires( 'customizer/settings/header.php' );
	radiustheme\ClCar\Helper::requires( 'customizer/settings/contact-info.php' );
	radiustheme\ClCar\Helper::requires( 'customizer/settings/breadcrumb.php' );
	radiustheme\ClCar\Helper::requires( 'customizer/settings/footer.php' );
	radiustheme\ClCar\Helper::requires( 'customizer/settings/color.php' );
	radiustheme\ClCar\Helper::requires( 'customizer/settings/blog.php' );

	if ( class_exists( 'Rtcl' ) ) {
		radiustheme\ClCar\Helper::requires( 'customizer/settings/listing-archive-layout.php' );
		radiustheme\ClCar\Helper::requires( 'customizer/settings/listing-single-layout.php' );
		radiustheme\ClCar\Helper::requires( 'customizer/settings/listings.php' );
	}

	if ( class_exists( 'RtclStore' ) ) {
		radiustheme\ClCar\Helper::requires( 'customizer/settings/store-archive-layout.php' );
		radiustheme\ClCar\Helper::requires( 'customizer/settings/store.php' );
		radiustheme\ClCar\Helper::requires( 'customizer/settings/store-single-layout.php' );
	}
}