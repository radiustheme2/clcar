<?php
/**
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
 * @author        RadiusTheme
 * @package       classified-listing/templates
 * @version       1.0.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Link;
use RtclPro\Helpers\Fns;
use \radiustheme\ClCar\RDTheme;
use \radiustheme\ClCar\Listing_Functions;
use RtclStore\Helpers\Functions as StoreFunctions;

global $listing;

$store = StoreFunctions::get_user_store($listing->get_owner_id());

$storeSlogan = $store->get_the_slogan();
$storeAddress = $store->get_address();
$storeEmail = $store->get_email();
$storePhone = $store->get_phone();
$storeWhatsapp = $store->get_whatsapp();
$website = $store->get_website();
$storeWebsite = str_replace(['https://', 'http://'], '', $website );

$rating_count               = $listing->get_rating_count();
$average_rating             = $listing->get_average_rating();
$listing_owner_widget_title = RDTheme::$options['listing_owner_widget_title'];
$user_login_class = is_user_logged_in() ? 'has-chat':'no-chat';

$settings = Functions::get_option( 'rtcl_membership_settings' );
$visibility = isset( $settings['store_archive_visibility'] ) && is_array( $settings['store_archive_visibility'] ) ? $settings['store_archive_visibility'] : [];

if (!empty($visibility)) {
	$show_store_ratings = in_array( 'show_store_ratings', $visibility ) ? true : false;
} else {
	$show_store_ratings = RDTheme::$options['show_store_ratings'] ?? false;
}

?>

<div class="rtcl-listing-user-info widget <?php echo esc_attr($user_login_class); ?>">
    <div class="widget-contact-form">
        <?php if(RDTheme::$options['show_owner_store_title']){ ?>
            <h3 class="listing-entry-inner-title">
                <?php
                    echo esc_html__( "Store Information", 'clcar' );
                ?>
            </h3>
        <?php } ?>
        <div class="rtcl-member-info-wrapper">
            <div class="member-header">
                <div class="member-img">
                    <a  href="<?php echo method_exists( $listing, 'get_the_author_url' ) ? esc_url( $listing->get_the_author_url() ) : '#';?>">
                        <?php  $store->the_logo( 'full' ); ?>
                    </a>
                </div>
                <div class="member-title">
                    <h4>
                        <a href="<?php echo esc_url( $store->get_the_permalink() ); ?>"><?php echo esc_html( $store->get_the_title() ); ?></a>
                    </h4>
	                <?php if ( $store->is_rating_enable() && $store->get_review_counts() && $show_store_ratings ): ?>
                        <div class="store-rating">
			                <?php echo wp_kses_post( Functions::get_rating_html( $store->get_average_rating(), $store->get_review_counts() ) ); ?>
                            <span class="reviews-rating-count">(<?php echo absint( $store->get_review_counts() ); ?>)</span>
                        </div>
	                <?php endif; ?>
	                <?php if ($storeSlogan){ ?>
                        <div class="store-slogan">
			                <?php echo esc_html( $storeSlogan ); ?>
                        </div>
	                <?php } ?>
                    <?php
                        $status = apply_filters( 'rtcl_user_offline_text', esc_html__( 'Offline Now', 'clcar' ) );
                        if ( Fns::is_online( $listing->get_owner_id() ) ) {
                            $status = apply_filters( 'rtcl_user_online_text', esc_html__( 'Online Now', 'clcar' ) );
                        }
                    ?>
                    <div class="rtin-user-item">
                        <?php do_action('rtcl_after_author_meta', $listing->get_owner_id() ); ?>
                    </div>
                </div>
            </div>
            <?php if ( $storeAddress || $storePhone || $storeWhatsapp || $storeEmail || $website ) : ?>
            <div class="member-content">
                <ul class="store-meta">
                    <?php if (Fns::registered_user_only('listing_seller_information') && !is_user_logged_in()) { ?>
                        <li>
                            <p class="login-message"><?php echo wp_kses(sprintf(__("Please <a href='%s'>login</a> to view the seller information.", "clcar"), esc_url(Link::get_my_account_page_link())), ['a' => ['href' => []]]); ?></p>
                        </li>
                    <?php } else { ?>

                        <?php if ( $storeAddress ) : ?>
                            <li class="item-number address">
                                <i class="icon-home"></i>
                                <?php echo esc_html( $storeAddress ); ?>
                            </li>
                        <?php endif; ?>

                        <?php if ( $storePhone && Functions::check_visibility( $listing->get_owner_id(), 'phone' ) ) : ?>
                            <li class="item-number phone">
                                <i class="icon-call"></i>
                                <?php Listing_Functions::clcar_phone_number( $listing, $storePhone ); ?>
                            </li>
                        <?php endif; ?>

                        <?php if ( $storeWhatsapp && Functions::check_visibility( $listing->get_owner_id(), 'whatsapp' ) ) :
                            $whatsapp_text = esc_html__('Need to discuss something to related','clcar');
                            $whatsapp_text = apply_filters('whatsapp_api_text', $whatsapp_text);
                            $url = get_the_permalink();
                            $from_text = esc_html__('from','clcar');
                            ?>
                            <li class="item-number whatsapp">
                                <i class="fa-brands fa-whatsapp"></i>
                                <?php Listing_Functions::clcar_whatsapp_number( $listing, $storeWhatsapp ); ?>
                            </li>
                        <?php endif; ?>

                        <?php if ( $storeEmail && RDTheme::$options['show_owner_store_email'] && Functions::check_visibility( $listing->get_author_id(), 'email' )) : ?>
                            <li class="store-email listing-mail">
                                <i class="icon-envelop"></i>
                                <a href="mailto:<?php echo esc_attr( $storeEmail ); ?>"><?php echo esc_html( $storeEmail ); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if($website && RDTheme::$options['show_owner_store_website']): ?>
                            <li class="store-website listing-website">
                                <i class="icon-web"></i>
                                <a href="<?php echo esc_url( $website ); ?>"><?php echo esc_html__('Visit Website','clcar'); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (Fns::registered_user_only('listing_seller_information') && !is_user_logged_in()) { } else {?>
                            <?php if(is_user_logged_in()){ ?>
                                <li class="rtcl-chat-website-link">
                                    <i class="icon-message"></i>
                                    <?php
                                    if ( Fns::is_enable_chat() && is_user_logged_in() ):
                                        $chat_btn_class = [ 'rtcl-chat-link' ];
                                        $chat_url = Link::get_my_account_page_link('chat');
                                        $is_chat = 'rtcl-contact-link';
                                        $chat_label = 'Live Chat';
                                        if ( is_user_logged_in() && $listing->get_author_id() !== get_current_user_id() ) {
                                            $chat_url   = '#';
                                            $chat_label = __('Quick Chat','clcar');
                                            $is_chat    = 'rtcl-contact-seller';
                                            array_push( $chat_btn_class, 'rtcl-contact-seller' );
                                        }
                                        ?>
                                        <span class='<?php echo esc_attr( $is_chat ); ?>'>
                                            <a class="<?php echo esc_attr( implode( ' ', $chat_btn_class ) ) ?>"
                                            href="<?php echo esc_url( $chat_url ) ?>" data-listing_id="<?php the_ID() ?>">
                                                <?php echo esc_html($chat_label ); ?>
                                                <span class="rtcl-chat-unread-count"></span>
                                            </a>
                                        </span>
                                    <?php endif; ?>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </ul>
                <?php do_action( 'rtcl_single_listing_social_profiles' ); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>