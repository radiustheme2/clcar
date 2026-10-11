<?php
/**
 * Listing Thumbnail
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;
use radiustheme\ClCar\Listing_Functions;

global $listing;
global $rtclIsAjax;

if ( isset( $_GET['view'] ) && in_array( $_GET['view'], [ 'grid', 'list' ], true ) ) {
	$view = esc_attr( $_GET['view'] );
} else {
	$view = Functions::get_option_item( 'rtcl_archive_listing_settings', 'default_view', 'list' );
}

if ( Functions::isEnableFb() ) {
	$logoId = get_post_meta( $listing->get_id(), 'listing_logo', true );
	if ( ! empty( $logoId ) ) {
		$logo_id = $logoId[0];
	} else {
		$logo_id = '';
	}
} else {
	$logo_id = get_post_meta( $listing->get_id(), 'listing_logo_img', true );
}

$listing_type = Listing_Functions::get_listing_type( $listing );

?>

<div class="listing-thumb">

	<?php if ( ! empty( $listing_type ) ) : ?>
        <span class="listing-add-type">
            <?php echo esc_html( $listing_type['label'] ); ?>
        </span>
	<?php endif; ?>

	<a href="<?php echo esc_url($listing->get_the_permalink()); ?>" aria-label="<?php echo esc_attr( $listing->get_the_title() ); ?>" class="thumb-link"></a>
	<?php $images = Functions::get_listing_images( $listing->get_id() ); ?>

	<?php
        foreach ( $images as $index => $image ):
            echo wp_get_attachment_image( $image->ID, 'rdtheme-size2' );
            break;
        endforeach;
	?>

	<?php if ( $listing && Fns::is_enable_mark_as_sold() && Fns::is_mark_as_sold( $listing->get_id() ) ) {
		echo '<span class="rtcl-sold-out">' . wp_kses_post( apply_filters( 'rtcl_sold_out_banner_text', esc_html__( "Sold Out", 'clcar' ) ) ) . '</span>';
	}
	?>
	<div class="listing-action-items">
        <div class="brand-wrap">
            <?php if ( ! empty( $logo_id ) ) { ?>
            <div class="brand-logo">
                <?php Listing_Functions::listing_logo(); ?>
            </div>
            <?php } ?>
        </div>
		<div class="clcar-listing-action">
			<?php echo wp_kses_post( Listing_Functions::get_favourites_link( $listing->get_id() ) ); ?>
			<?php if ( Fns::is_enable_compare() ) {
				$compare_ids    = ! empty( $_SESSION['rtcl_compare_ids'] ) ? $_SESSION['rtcl_compare_ids'] : [];
				$selected_class = '';
				if ( is_array( $compare_ids ) && in_array( $listing->get_id(), $compare_ids ) ) {
					$selected_class = ' selected';
				}
				?>
				<a class="rtcl-compare <?php echo esc_attr( $selected_class ); ?>" href="#" data-listing_id="<?php echo absint( $listing->get_id() ) ?>">
					<i class="icon-compare2"></i>
				</a>
			<?php } ?>
		</div>
	</div>
</div>
