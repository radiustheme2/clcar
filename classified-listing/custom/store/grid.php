<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.3.5
 *
 * @var $content_class string
 * @var $sidebar_position string
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use radiustheme\ClCar\RDTheme;
use radiustheme\ClCar\Listing_Functions;
use Rtcl\Helpers\Functions as RtclFunctions;
use RtclStore\Helpers\Functions as StoreFunctions;

global $store;

$settings = RtclFunctions::get_option( 'rtcl_membership_settings' );
$visibility = isset( $settings['store_archive_visibility'] ) && is_array( $settings['store_archive_visibility'] ) ? $settings['store_archive_visibility'] : [];

if (!empty($visibility)) {
	$show_store_ratings = in_array( 'show_store_ratings', $visibility ) ? true : false;
} else {
	$show_store_ratings = RDTheme::$options['show_store_ratings'] ?? false;
}

?>

<div <?php StoreFunctions::store_class( 'store-grid-layout', $store ) ?>>
	<div class="store-loop-item-inner">
		<?php
		/**
		 * Hook: rtcl_store_loop_item_start.
		 *
		 * @hooked open_store_link - 10
		 */
		do_action( 'rtcl_before_store_loop_item' );
		?>
		<div class="store-logo-part">
			<?php
			/**
			 * Hook: rtcl_store_loop_item_start.
			 *
			 * @hooked store_thumbnail - 10
			 */
			do_action( 'rtcl_store_loop_item_thumbnail' );
			?>
		</div>
		<div class="store-content-part">
			<div class="store-title">
				<h3 class="rtcl-store-title"><a href="<?php echo esc_url( $store->get_the_permalink() ); ?>"><?php echo esc_html( $store->get_the_title() ); ?></a></h3>
			</div>

			<?php
			//Listing_Functions::store_meta();
			/**
			 * Hook: rtcl_store_loop_item.
			 *
			 * @hooked loop_item_content_start - 5
			 * @hooked loop_item_store_title - 10
			 * @hooked store_meta - 20
			 * @hooked loop_item_content_end - 100
			 */
			do_action( 'rtcl_store_loop_item' );
			?>

			<?php Listing_Functions::store_excerpt(); ?>

			<?php if ( $store->is_rating_enable() && $store->get_review_counts() && $show_store_ratings ): ?>
				<div class="store-rating">
					<?php echo wp_kses_post( Functions::get_rating_html( $store->get_average_rating(), $store->get_review_counts() ) ); ?>
					<span class="reviews-rating-count">(<?php echo absint( $store->get_review_counts() ); ?>)</span>
				</div>
			<?php endif; ?>
		</div>
		<?php
		/**
		 * Hook: rtcl_after_store_loop_item.
		 *
		 * @hooked close_store_link - 5
		 */
		do_action( 'rtcl_after_store_loop_item' );
		?>
	</div>
</div>