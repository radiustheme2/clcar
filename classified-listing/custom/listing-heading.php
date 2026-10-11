<?php
/**
 * This file is for showing listing header
 *
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Text;
use Rtcl\Models\Listing;
use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;
use radiustheme\ClCar\RDTheme;
use RtclMarketplace\Hooks\ActionHooks;
use Rtcl\Services\FormBuilder\FBField;
use radiustheme\ClCar\Listing_Functions;
use RtclClaimListing\Helpers\Functions as ClaimFunctions;

global $listing;

if ( ! $listing ) {
	return;
}

$listing_form = $listing->getForm();
$condition_value = '';
if ( ! empty( $listing_form ) ) {
	$fields          = $listing_form->getFieldAsGroup( FBField::CUSTOM );
	$condition_label = $fields['car-condition']['label'];
	$condition_value = get_post_meta( $listing->get_id(), 'car-condition', true );
}

$average_rating   = $listing->get_average_rating();
$rating_count     = $listing->get_rating_count();
$can_report_abuse = Functions::get_option_item( 'rtcl_single_listing_settings', 'has_report_abuse', '', 'checkbox' ) ? true : false;
$listing_type     = Listing_Functions::get_listing_type( $listing );

?>

<div class="listing-heading" id="listing-home">
    <div class="store-logo">
        <?php Listing_Functions::listing_logo(); ?>
    </div>
    <div class="listing-info">
        <div class="listing-heading-top">
            <?php if($listing->can_show_category() || $listing->can_show_ad_type()){ ?>
                <div class="listing-badge-wrap">
                    <?php if (Functions::isEnableFb() && !empty( $condition_value )){ ?>
                        <span class="listing-car-condition">
                            <?php echo sprintf( "%s %s", '<span class="label">' . esc_html( $condition_label ) . ':</span>', esc_html( $condition_value ) ); ?>
                        </span>
                    <?php } ?>

                    <?php
                    if ( $listing->has_category() && $listing->can_show_category()):
                        $category = $listing->get_categories();
                        $category = end( $category );
                        ?>
                        <a class="category" href="<?php echo esc_url(
                            get_term_link(
                                $category->term_id,
                                $category->taxonomy
                            )
                        ); ?>"><?php echo esc_html( $category->name ) ?></a>
                    <?php endif;
                    ?>

                    <?php if ( ! empty( $listing_type ) && $listing->can_show_ad_type()) : ?>
                        <span class="listing-add-type">
                            <?php echo sprintf( "%s %s", esc_html( apply_filters( 'rtcl_type_prefix', __( 'For', 'clcar' ) ) ), esc_html( $listing_type['label'] ) ); ?>
                        </span>
                    <?php endif; ?>

                </div>
            <?php } ?>
            <?php $listing->the_badges(); ?>
            <div class="listing-price-wrap">
                <?php if ( $listing->can_show_price() ): ?>
                    <div class="listing-price"><?php printf( "%s", wp_kses_post( $listing->get_price_html() ) ); ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="listing-heading-bottom">
            <div class="meta-content">
                <h2 class="listing-title"><?php $listing->the_title(); ?></h2>
                <ul class="entry-meta">
                    <?php if ( current( $listing->user_contact_location_at_single() ) ): ?>
                        <li>
                            <i class="icon-location2"></i><?php echo wp_kses_post( implode( '<span class="rtcl-delimiter">,</span> ', $listing->user_contact_location_at_single() ) ); ?>
                        </li>
                    <?php endif; ?>
                    <?php if ( $listing->can_show_date() ): ?>
                        <li><i class="icon-calendar-1"></i><?php $listing->the_time(); ?></li>
                    <?php endif; ?>
                    <?php if ( $listing->can_show_views() ): ?>
                        <li>
                            <i class="icon-view"></i><?php echo esc_html( sprintf( _n( '%s view', '%s views', $listing->get_view_counts(), 'clcar' ), number_format_i18n( $listing->get_view_counts() ) ) ); ?>
                        </li>
                    <?php endif; ?>
                    <?php if ( ! empty( $rating_count ) ): ?>
                        <li class="listing-rating">
                            <div class="item-icon">
                                <?php echo wp_kses_post( Functions::get_rating_html( $average_rating, $rating_count ) ); ?>
                            </div>
                            <div class="item-text"><?php echo wp_kses_post( apply_filters( 'clcar_rating_count_format', sprintf( __( '(<span>%s</span>) Reviews', 'clcar' ), esc_html( $rating_count ) ) ) ); ?></div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <?php if ( RDTheme::$options['show_listing_button_area'] ) : ?>
                <div class="button-area">
                    <?php
                        if ( class_exists('RtclMarketplace') ) {
                            ActionHooks::add_buy_button();
                        }
                    ?>
                    <ul>
                        <li><?php echo wp_kses_post( Functions::get_favourites_link( $listing->get_id() ) ); ?></li>
                        <li>
                            <?php if ( Fns::is_enable_compare() ) {
                                $compare_ids    = ! empty( $_SESSION['rtcl_compare_ids'] ) ? $_SESSION['rtcl_compare_ids'] : [];
                                $selected_class = '';
                                if ( is_array( $compare_ids ) && in_array( $listing->get_id(), $compare_ids ) ) {
                                    $selected_class = ' selected';
                                }
                                ?>
                                <a class="rtcl-compare <?php echo esc_attr( $selected_class ); ?>" href="#"
                                    data-listing_id="<?php echo absint( $listing->get_id() ) ?>">
                                    <i class="icon-compare"></i>
                                </a>
                            <?php } ?>
                        </li>
                        <?php if ( $can_report_abuse ): ?>
                            <li>
                                <?php if ( is_user_logged_in() ): ?>
                                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#rtcl-report-abuse-modal">
                                        <i class='fas fa-bug'></i>
                                        <?php echo esc_html( Text::report_abuse() ); ?>
                                    </a>
                                <?php else: ?>
                                    <a href="javascript:void(0)" class="rtcl-require-login">
                                        <i class='fas fa-bug'></i>
                                        <?php echo esc_html( Text::report_abuse() ); ?>
                                    </a>
                                <?php endif; ?>
                            </li>
                        <?php endif; ?>
                        <?php if($listing->the_social_share(false)) : ?>
                        <li>
                            <a href="#" id="share-btn"><i class="icon-share-1"></i></a>
                            <div class="share-icon">
                                <?php $listing->the_social_share(); ?>
                            </div>
                        </li>
                        <?php endif; ?>
                        <li><a href="#" onclick="window.print();"><i class="icon-print"></i></a></li>
                        <?php
                        if ( function_exists( 'rtclClaimListing' ) && ClaimFunctions::claim_listing_enable() ){ ?>
                            <li class='report-abuse-li' data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="<?php esc_attr_e( "Claim", "clcar" ) ?>">
                                <?php if ( is_user_logged_in() ): ?>
                                    <span data-bs-toggle="tooltip" data-original-title="<?php echo esc_html( ClaimFunctions::get_claim_action_title() ); ?>">
                                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#rtcl-claim-listing-modal">
                                            <i class="fas fa-exclamation-circle"></i>
                                        </a>
                                    </span>
                                <?php else: ?>
                                    <a href="javascript:void(0)" data-bs-toggle="tooltip" class="rtcl-require-login" data-original-title="<?php echo esc_html( ClaimFunctions::get_claim_action_title() ); ?>">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </a>
                                <?php endif; ?>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="rtcl-report-abuse-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="rtcl-report-abuse-form" class="form-vertical">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="rtcl-report-abuse-modal-label"><?php esc_html_e( 'Report Abuse', 'clcar' ); ?></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rtcl-report-abuse-message"><?php esc_html_e( 'Your Complaint', 'clcar' ); ?>
                            <span class="rtcl-star">*</span></label>
                        <textarea name="message" class="form-control" id="rtcl-report-abuse-message" rows="3"
                                  placeholder="<?php esc_attr_e( 'Message... ', 'clcar' ); ?>"
                                  required></textarea>
                    </div>
                    <div id="rtcl-report-abuse-g-recaptcha"></div>
                    <div id="rtcl-report-abuse-message-display"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit"
                            class="btn btn-primary"><?php esc_html_e( 'Submit', 'clcar' ); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php do_action( 'rtcl_single_listing_after_action', $listing->get_id() ); ?>
