<?php
/**
 * @var string  $address
 * @var string  $phone
 * @var string  $whatsapp_number
 * @var string  $email
 * @var string  $website
 * @var array   $phone_options
 * @var bool    $has_contact_form
 * @var string  $email_to_seller_form
 * @var Listing $listing
 * @var array   $locations
 * @var int     $listing_id Listing id
 * @author      RadiusTheme
 * @package     cl-hotel/templates
 * @version     1.0.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;
use RadiusTheme\ClCar\Helper;
use \radiustheme\ClCar\Listing_Functions;

global $listing;
$ownerEmail = '';

$owner_id = $listing->get_owner_id();
$ownerEmail = $listing->get_owner_email();
$ownerPhone = get_user_meta( $owner_id, '_rtcl_phone', true );
$website = get_user_meta( $owner_id, '_rtcl_website', true );
$owmerAddress = get_user_meta( $owner_id, '_rtcl_address', true );
$ownerWhatsapp = get_user_meta( $owner_id, '_rtcl_whatsapp_number', true );
$ownerWebsite = str_replace(['https://', 'http://'], '', $website );
$ownerUrl = get_author_posts_url($owner_id);

$status = apply_filters( 'rtcl_user_offline_text', esc_html__( 'Offline Now', 'cl-hotel' ) );
if ( class_exists('RtclPro') && Fns::is_online( $listing->get_owner_id() ) ) {
	$status = apply_filters( 'rtcl_user_online_text', esc_html__( 'Online Now', 'cl-hotel' ) );
}
?>

<div class="listing-store-info">
<div class="rtcl-listing-side-title">
	<h3><?php esc_html_e( "Owner Information", 'cl-hotel' ); ?></h3>
</div>
<div class="list-group">
    <div class="listing-author listing-owner">
        <div class="author-logo-wrapper">
            <a  href="<?php echo method_exists( $listing, 'get_the_author_url' ) ? esc_url( $listing->get_the_author_url() ) : '#';?>">
		        <?php Helper::get_listing_author_image( $listing,105 ); ?>
            </a>
        </div>
        <div class="author-info-wrapper">
            <h4 class="author-name">
                <?php if ( $listing->can_add_user_link() && ! is_author() ) : ?>
                    <a class="author-link" href="<?php echo esc_url( $ownerUrl ); ?>">
                        <?php echo esc_html( $listing->get_owner_name() ); ?>
                    </a>
                <?php else: ?>
                    <?php echo wp_kses_post( $listing->get_owner_name() ); ?>
                <?php endif; ?>
            </h4>
            <div class="rtin-user-item">
                <?php do_action('rtcl_after_author_meta', $listing->get_owner_id() ); ?>
            </div>
            <span class="rtcl-user-status <?php echo esc_attr( strtolower( $status ) ); ?>"><span class="user-staus-text"><?php echo wp_kses_post( $status ); ?></span></span>
        </div>
    </div>
    <ul class="info-list">
        <?php if ( $owmerAddress ){ ?>
            <li>
                <i class="icon-home"></i>
                <?php echo esc_html( $owmerAddress ); ?>
            </li>
        <?php } if ( $ownerEmail && Functions::check_visibility( $listing->get_author_id(), 'email' ) ){ ?>
            <li>
                <i class="icon-envelop"></i>
                <a class="rtcl-phone-link" href="mailto:<?php echo esc_attr( $ownerEmail ); ?>" target="_blank">
                    <?php echo esc_html( $ownerEmail ); ?>
                </a>
            </li>
        <?php } if ( $ownerPhone && Functions::check_visibility( $listing->get_author_id(), 'phone' ) ){ ?>
            <li>
                <i class="icon-call"></i>
	            <?php Listing_Functions::clcar_phone_number( $listing, $ownerPhone ); ?>
            </li>
        <?php } if ( $ownerWhatsapp && Functions::check_visibility( $listing->get_author_id(), 'whatsapp' ) ){ ?>
            <li>
                <i class="fab fa-whatsapp"></i>
	            <?php Listing_Functions::clcar_phone_number( $listing, $ownerWhatsapp ); ?>
            </li>
        <?php } if ( $website ){ ?>
            <li>
                <i class="icon-web"></i>
                <a class="rtcl-website-link" href="<?php echo esc_url( $website ); ?>" target="_blank">
                    <?php echo esc_html( $ownerWebsite ); ?>
                </a>
            </li>
        <?php } ?>
    </ul>
    <?php 
        $social_list = Functions::get_user_social_profile( $owner_id );
        if ( ! empty( $social_list ) ) {
    ?>
        <div class="rtcl-user-social">
            <?php

            foreach ( $social_list as $key => $value ) { ?>
                <a href="<?php echo esc_url( $value ) ?>" class="<?php echo esc_attr( $key ) ?>" target="_blank">
		            <?php
		            if ( 'twitter' === $key ) {
			            $iconClass = 'fa-brands fa-x-twitter';
		            } else {
			            $iconClass = 'rtcl-icon-' . $key;
		            }
		            ?>
                    <i class="rtcl-icon <?php echo esc_attr( $iconClass ); ?>"></i>
                </a>
                <?php
            }
            ?>
        </div>
    <?php } ?>
</div>
</div>
