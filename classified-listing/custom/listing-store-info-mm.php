<?php
/**
 * @var int     $listing_id Listing id
 * @author       RadiusTheme
 * @package      cl-hotel/templates
 * @version      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RtclStore\Helpers\Functions as StoreFunctions;

$listing_id = $listing_id ?? get_the_ID();
$listing = rtcl()->factory->get_listing( $listing_id );

if ( ! $listing ) {
	return;
}

$store = StoreFunctions::get_user_store($listing->get_owner_id());

if ( ! $store ) {
	return;
}

$storeSlogan = $store->get_the_slogan();
$storeAddress = $store->get_address();
$storeEmail = $store->get_email();
$storePhone = $store->get_phone();
$storeWhatsapp = $store->get_whatsapp();
$website = $store->get_website();
$storeWebsite = str_replace(['https://', 'http://'], '', $website );

?>

<div class="listing-store-info">
    <h3 class="listing-entry-inner-title"><?php esc_html_e( "Posted By", 'clcar' ); ?></h3>
    <div class="list-group">
        <div class="listing-author listing-store">
            <div class="author-logo-wrapper store-information">
                <div class="author-avatar">
                    <?php $store->the_logo('full'); ?>
                </div>
            </div>
            <div class="author-info-wrapper">
                <h4 class="author-name">
                    <?php
                        $store->the_title();
                        do_action( 'rtcl_after_store_title', $store );
                    ?>
                </h4>

                <?php if ($storeSlogan){ ?>
                <div class="rtin-user-item">
                    <?php echo esc_html( $storeSlogan ); ?>
                </div>
                <?php } ?>
            </div>
        </div>
        <ul class="info-list">
            <?php if ( $storeAddress ){ ?>
                <li>
                    <i class="icon-location"></i>
                    <?php echo esc_html( $storeAddress ); ?>
                </li>
            <?php } if ( $storeEmail ){ ?>
                <li>
                    <a class="rtcl-phone-link" href="mailto:<?php echo esc_attr( $storeEmail ); ?>" target="_blank">
                        <i class="icon-envelop"></i>
                        <?php echo esc_html( $storeEmail ); ?>
                    </a>
                </li>
            <?php } if ( $storePhone ){ ?>
                <li>
                    <a class="rtcl-phone-link" href="tel:<?php echo esc_attr( $storePhone ); ?>" target="_blank">
                        <i class="icon-call"></i>
                        <?php echo esc_html( $storePhone ); ?>
                    </a>
                </li>
            <?php } if ( $storeWhatsapp ){ ?>
                <li>
                    <a class="rtcl-phone-link" target="_blank" href="https://wa.me/<?php echo esc_attr( $storeWhatsapp ); ?>">
                        <i class="fa-brands fa-whatsapp"></i>
		                <?php echo esc_html( $storeWhatsapp ); ?>
                    </a>
                </li>
            <?php } if ( $website ){ ?>
                <li>
                    <a class="rtcl-website-link" href="<?php echo esc_url( $website ); ?>" target="_blank">
                        <i class="demo-icon rt-custom-icon-glob"></i>
                        <?php echo esc_html( $storeWebsite ); ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>
    