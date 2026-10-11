<?php
/**
 *
 * @author     RadiusTheme
 * @package    classified-listing-store/templates
 * @version    1.0.0
 *
 * @var Store $store
 */

use RtclStore\Models\Store;
use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Link;
use RtclPro\Helpers\Fns;
use \radiustheme\ClCar\RDTheme;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} 

?>

<div class='rtcl-member-store-info'>
    <div class='rtcl-member-info-wrapper'>
        <div class='member-header'>
            <div class="store-logo">
                <?php if ( $store->has_logo() ): ?>
                    <a href="<?php $store->the_permalink(); ?>">
                        <?php $store->the_logo('full'); ?>
                    </a>
                <?php endif; ?>
            </div>
            <div class="store-title">
                <h4><a href="<?php $store->the_permalink(); ?>"><?php $store->the_title() ?></a></h4>
                <?php if ( $store->is_rating_enable() && $store->get_review_counts() && RDTheme::$options['show_owner_store_rating']): ?>	
                    <div class="product-rating store-rating">
                        <?php echo wp_kses_post( Functions::get_rating_html( $store->get_average_rating(), $store->get_review_counts() ) ); ?>
                        <?php
                            printf('<span class="reviews-rating-count">(%s %s)</span>',
                                absint( $store->get_review_counts() ),
                                esc_html__('Reviews', 'clcar')
                            )
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class='member-content'>
            <?php 
            $mobileClass = wp_is_mobile() ? " rtcl-mobile" : null; 
            $phone_options = [
                'safe_phone'   => mb_substr($store->get_phone(), 0, mb_strlen($store->get_phone()) - 3) . apply_filters('rtcl_phone_number_placeholder', 'XXX'),
                'phone_hidden' => mb_substr($store->get_phone(), -3)
            ];
            ?>
            <ul class="store-meta">
                <li class='item-number reveal-phone<?php echo esc_attr($mobileClass); ?>' data-options="<?php echo esc_attr(wp_json_encode($phone_options)); ?>">
                    <span class='numbers'><i class="icon-call"></i><?php echo esc_html($phone_options['safe_phone']); ?></span>
                    <span class="show"><?php esc_html_e("Show Number","clcar") ?></span>
                </li>
                <?php if(RDTheme::$options['show_owner_store_website'] && $store->get_website()){ ?>
                    <li class="store-website">
                        <span class="icon-holder"><i class="icon-web"></i></span>
                        <a href="<?php echo esc_url($store->get_website()); ?>" class="content-holder" target="_blank"><?php esc_html_e('Website','clcar'); ?></a>
                    </li>
                <?php } ?>
                <?php if(RDTheme::$options['show_owner_store_address'] && $store->get_address()){ ?>
                    <li class="store-address">
                        <span class="icon-holder"><i class="icon-location2"></i></span>
                        <div class="content-holder">
                            <?php echo esc_html($store->get_address()); ?>
                        </div>
                    </li>
                <?php } ?>
                <?php if(RDTheme::$options['show_owner_store_email'] && $store->get_email()){ ?>
                    <li class="store-email">
                        <span class="icon-holder"><i class="icon-envelop"></i></span>
                        <a href="mailto:<?php echo esc_attr($store->get_email()); ?>" class="content-holder"><?php echo esc_html($store->get_email()); ?></a>
                    </li>
                <?php } ?>
                <?php if ( Fns::is_enable_chat() && is_user_logged_in() ): ?>
                    <li class="rtcl-chat-website-link">
                        <?php
                        $chat_btn_class = [ 'rtcl-chat-link' ];
                        $chat_url = Link::get_my_account_page_link();
                        if ( is_user_logged_in() ) {
                            $chat_url = '#';
                            array_push( $chat_btn_class, 'rtcl-contact-seller' );
                        } else {
                            array_push( $chat_btn_class, 'rtcl-no-contact-seller' );
                        }
                        ?>
                        <span class='rtcl-contact-seller'>
                            <i class="icon-message"></i>
                            <a class="<?php echo esc_attr( implode( ' ', $chat_btn_class ) ) ?>"
                                href="<?php echo esc_url($chat_url ) ?>" data-listing_id="<?php the_ID() ?>">
                                <?php esc_html_e("Quick Chat", "clcar" ) ?>
                            </a>
                        </span>
                    </li>
                <?php endif; ?>
            </ul>
            <?php if($store->get_social_media()){ ?>
                <ul class="social-icons">
                    <li><?php esc_html_e('Socials:','clcar'); ?></li>
                    <?php foreach ($store->get_social_media() as $key => $value) {
                        if($key=='facebook'){
                            printf('<li class="%1$s"><a href="%2$s" target="_blank"><i class="fab fa-facebook-f"></i></a></li>',esc_html($key),esc_url($value));
                        }
                        else if($key=='twitter'){
                            printf('<li class="%1$s"><a href="%2$s" target="_blank"><i class="fa-brands fa-x-twitter"></i></a></li>',esc_html($key),esc_url($value));
                        }
                        else if($key=='youtube'){
                            printf('<li class="%1$s"><a href="%2$s" target="_blank"><i class="fab fa-youtube"></i></a></li>',esc_html($key),esc_url($value));
                        }
                        else if($key=='linkedin'){
                            printf('<li class="%1$s"><a href="%2$s" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a></li>',esc_html($key),esc_url($value));
                        }
                    } ?>
                </ul>
            <?php } ?>
        </div>
    </div>
	<?php ?>
</div>
