<?php
/**
 * jetpack.
 *
 * @link https://jetpack.com/
 */

namespace radiustheme\ClCar;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Functions;
use RtclStore\Helpers\Functions as StoreFunctions;

/**
 * ThemeJetpack Class
 */
class Shortcode {

	protected static $instance = null;

	public static $shortcode_list = [
		'header'    => [
			'tag'      => 'clcar_listing_header',
			'callback' => 'render_listing_header',
		],
		'gallery'    => [
			'tag'      => 'clcar_listing_gallery',
			'callback' => 'render_listing_gallery',
		],
		'sidebar'    => [
			'tag'      => 'clcar_listing_sidebar',
			'callback' => 'render_listing_sidebar',
		],
        'description' => [
			'tag'      => 'clcar_listing_description',
			'callback' => 'render_listing_description',
		],
        'custom_fields' => [
			'tag'      => 'clcar_listing_custom_fields',
			'callback' => 'render_listing_custom_fields',
		],
        'video' => [
			'tag'      => 'clcar_listing_video',
			'callback' => 'render_listing_video',
		],
        'map' => [
			'tag'      => 'clcar_listing_map',
			'callback' => 'render_listing_map',
		],
        'review' => [
			'tag'      => 'clcar_listing_review',
			'callback' => 'render_listing_review',
		],
	];

	/**
	 * register default hooks and actions for WordPress
	 *
	 * @return
	 */
	public function __construct() {
		add_filter(
			'rtcl/fb/single_layout/fields',
			function ( $fields ) {
				$shortcode_hints = '';
				foreach ( self::$shortcode_list as $label => $shortcode ) {
					$shortcode_hints .= '<br><b>' . ucfirst( $label ) . ":</b> [{$shortcode['tag']}]";
				}

				$fields['shortcode']['editor']['hints']['value'] = 'Available Shortcodes: ' . $shortcode_hints;

				return $fields;
			}
		);

		foreach ( self::$shortcode_list as $shortcode ) {
			add_shortcode( $shortcode['tag'], [ $this, $shortcode['callback'] ] );
		}
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * @return false|string
	 */
	public function render_listing_header() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();
			?>

            <!--Listing Heading-->
            <div class="listing-single-layout-1">
			    <?php Helper::get_custom_listing_template( 'listing-heading' ); ?>
            </div>
            <!--End Listing Heading-->

			<?php
			return ob_get_clean();
		}

		return '';
	}

	/**
	 * @return false|string
	 */
	public function render_listing_gallery() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();
			?>

            <div class="listing-gallery-wrapper">
				<?php do_action('rt_listing_galley' ); ?>
            </div>

			<?php
			return ob_get_clean();
		}

		return '';
	}

    /**
	 * Listing sidebar
	 *
	 * @return false|string
	 */
	public function render_listing_sidebar() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();

			?>

            <div class="listing-sidebar">
				<?php
				if ( $listing->can_show_user() ) {
					if ( (RDTheme::$options['show_user_info_on_details'] === 'show_store_info') && StoreFunctions::get_user_store($listing->get_owner_id())) {
						Helper::get_custom_listing_template( 'listing-store-info' );
					} elseif ( RDTheme::$options['show_user_info_on_details'] === 'show_owner_info' ) {
						Helper::get_custom_listing_template( 'listing-owner-info' );
					} else {
						Helper::get_custom_listing_template( 'general-info' );
					}
				}
				?>
				<?php
				$email = get_post_meta($listing->get_id(),'email',true);
				$has_contact_form = Functions::get_option_item( 'rtcl_single_listing_settings', 'has_contact_form', false, 'checkbox' );
				?>
				<?php if ( $email && $has_contact_form) : ?>
                    <div class='rtcl-do-email'>
						<?php $listing->email_to_seller_form(); ?>
                    </div>
				<?php endif; ?>
				<?php do_action( 'rtcl_after_single_listing_sidebar', $listing->get_id() ); ?>
				<?php if ( is_active_sidebar( 'single-listing-sidebar' ) ): ?>
                    <aside class="sidebar-widget">
						<?php dynamic_sidebar( 'single-listing-sidebar' ); ?>
                    </aside>
				<?php endif; ?>
            </div>

            <?php
			return ob_get_clean();
		}

		return '';
	}

	/**
	 * @return false|string
	 */
	public function render_listing_description() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();
			?>

            <!-- Description -->
			<?php if ( $listing->get_the_content() ) { ?>
                <div class="clcar-listing-description single-content">
                    <h3 class="listing-entry-inner-title">
						<?php esc_html_e('Description','clcar'); ?>
                    </h3>
					<?php $listing->the_content(); ?>
                </div>
			<?php } ?>

			<?php
			return ob_get_clean();
		}

		return '';
	}

	/**
	 * @return false|string
	 */
	public function render_listing_custom_fields() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();

			$listing->custom_fields();

			return ob_get_clean();
		}

		return '';
	}

	/**
	 * @return false|string
	 */
	public function render_listing_video() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();

			$video_urls       = [];
			if ( ! Functions::is_video_urls_disabled() ) {
				$video_urls = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
				$video_urls = ! empty( $video_urls ) && is_array( $video_urls ) ? $video_urls : [];
			}

			?>

            <!-- Video -->
			<?php if ( ! empty( $video_urls ) ): ?>
                <div class="listing-video single-content" id="video">
                    <h3 class="listing-entry-inner-title"><?php esc_html_e( 'Listing Video', 'clcar' ); ?></h3>
                    <div class="item-img">
						<?php $listing->the_thumbnail( 'rtcl-gallery' ); ?>
                        <div class="video-icon">
                            <a class="play-btn popup-youtube" href="<?php echo esc_url( $video_urls[0] ); ?>">
                                <i class="fas fa-play"></i>
                            </a>
                        </div>
                    </div>
                </div>
			<?php endif; ?>

			<?php
			return ob_get_clean();
		}

		return '';
	}

	/**
	 * @return false|string
	 */
	public function render_listing_map() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();

			$hide_listing_map   = get_post_meta( get_the_ID(), 'hide_map', true );

			?>

            <!-- Map -->
			<?php if ( method_exists( 'Rtcl\Helpers\Functions', 'has_map' ) && Functions::has_map() && ! $hide_listing_map ){ ?>
                <div class="listing-single-map single-content">
                    <div class="listing-map-content">
						<?php do_action( 'rtcl_single_listing_content_end', $listing ); ?>
                    </div>
                </div>
			<?php } ?>

			<?php
			return ob_get_clean();
		}

		return '';
	}

    /**
	 * @return false|string
	 */
	public function render_listing_review() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();

            do_action( 'rtcl_single_listing_review' );

			return ob_get_clean();
		}

		return '';
	}

}

Shortcode::instance();
