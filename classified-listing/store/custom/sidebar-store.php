<?php
/**
 * Modal
 *
 * @var Store  $store
 * @var string $store_oh_type
 * @var array  $store_oh_hours
 * @var string $today
 * @package    classified-listing-store/templates
 * @version    1.0.0
 *
 * @author     RadiusTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use radiustheme\ClCar\RDTheme;
use Rtcl\Helpers\Functions;
use radiustheme\ClCar\Helper;
use RtclStore\Models\Store;
use RtclStore\Resources\Options;

global $store;
$store_oh_type  = get_post_meta( $store->get_id(), 'oh_type', true );
$store_oh_hours = get_post_meta( $store->get_id(), 'oh_hours', true );
$store_oh_hours = is_array( $store_oh_hours ) ? $store_oh_hours : ( $store_oh_hours ? (array) $store_oh_hours : array() );
$days           = Options::store_open_hour_days();
$today          = strtolower( date( 'l' ) );
?>
    <?php if($store->get_the_description() || $store->get_social_media_html()){ ?>
        <div class="store-details widget">
            <h3 class="widget-heading"><?php esc_html_e( 'Details', 'clcar' ); ?></h3>
            <p><?php echo esc_html($store->get_the_description()); ?></p>
            <?php if ( $store->get_social_media() ): ?>
	        <div class="clcar-socials">
                <div class="social-title"><?php esc_html_e('Socials:','clcar'); ?></div>
                <?php echo wp_kses_post( $store->get_social_media_html() ); ?>
            </div>
	    <?php endif; ?>
        </div>
    <?php } ?>

    <div class="store-information-wrapper widget">
        <h3 class="widget-heading"><?php esc_html_e( 'Store Information', 'clcar' ); ?></h3>
        <div class="store-more-details">
            <ul>
				<?php if ( $store_phone = $store->get_phone() ) : ?>
                    <li>
                        <i class="icon-call"></i>
                        <a target="_blank" href="tel:<?php echo esc_attr( $store_phone ) ?>">
							<?php echo esc_html( $store_phone ) ?>
                        </a>
                    </li>
				<?php endif; ?>

				<?php if ( $store_email = $store->get_email() ) : ?>
                    <li>
                        <i class="icon-envelop"></i>
                        <a href="mailto:<?php echo esc_attr( $store_email ) ?>">
							<?php echo esc_html( $store_email ) ?>
                        </a>
                    </li>
				<?php endif; ?>

				<?php if ( $store_website = $store->get_website() ) : ?>
                    <li>
                        <i class="icon-web"></i>
                        <a target="_blank" href="<?php echo esc_url_raw( $store_website ) ?>" <?php echo Functions::is_external( $store_website ) ? ' rel="nofollow"'
							: ''; ?>><?php esc_html_e('Visit Website','clcar'); ?>
                        </a>
                    </li>
				<?php endif; ?>
            </ul>

            <div class="more-item store-hours-list-wrap">
                <div class="rtin-oh-title">
                    <h4><?php esc_html_e( "Opening Hours", "clcar" ) ?></h4>
                </div>
                <div class="store-hours-list">
					<?php if ( $store_oh_type == "selected" ): ?>
						<?php if ( is_array( $store_oh_hours ) && ! empty( $store_oh_hours ) ): ?>
							<?php foreach ( $store_oh_hours as $hKey => $oh_hour ): ?>
                                <div class="row store-hour<?php echo esc_attr( ( $hKey == $today ) ? ' current-store-hour' : '' ); ?>">
                                    <div class="col-4">
                                        <span class="hour-day"><?php echo esc_html( $days[ $hKey ] ?? $hKey ); ?></span>
                                    </div>
                                    <div class="col-8 oh-hours-wrap">
										<?php if ( isset( $oh_hour['active'] ) ): ?>
                                            <div class="oh-hours">
                                                <span class="open-hour"><?php echo isset( $oh_hour['open'] ) ? esc_html( $oh_hour['open'] ) : ''; ?></span>
                                                <span class="close-hour"><?php echo isset( $oh_hour['close'] ) ? esc_html( $oh_hour['close'] ) : ''; ?></span>
                                            </div>
										<?php else: ?>
                                            <span class="off-day"><?php esc_html_e( "Closed", "clcar" ) ?></span>
										<?php endif; ?>
                                    </div>
                                </div>
							<?php endforeach; ?>
						<?php else: ?>
                            <div class="always-open"><?php esc_html_e( "Permanently Close", "clcar" ) ?></div>
						<?php endif; ?>
					<?php elseif ( $store_oh_type == 'always' ): ?>
                        <div class="always-open"><?php esc_html_e( "Always Open", "clcar" ) ?></div>
					<?php endif; ?>
                </div>
            </div>

        </div>
    </div>

<?php if ( $store_email = $store->get_email() && RDTheme::$options['store_owner_contact_form']) : ?>
    <div class="store-form-wrapper widget">
        <h3 class="widget-heading"><?php esc_html_e( 'Message Store Owner', 'clcar' ); ?></h3>
		<?php Functions::get_template( 'store/contact-form' ); ?>
    </div>
<?php endif; ?>