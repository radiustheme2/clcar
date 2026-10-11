<?php
/**
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RtclPro\Helpers\Fns;
use Rtcl\Models\Listing;
use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Pagination;
use RtclPro\Controllers\Hooks\TemplateHooks;
use radiustheme\ClCar\Listing_Functions;
?>

<?php
// Plain card colours unless the widget's "Highlight Promoted Listings" switch is on.
// Helper::promotion_color_class() needs Classified Listing Toolkits with that control.
$promo_color_class = method_exists( '\RadiusTheme\ClassifiedListingToolkits\Hooks\Helper', 'promotion_color_class' )
	? \RadiusTheme\ClassifiedListingToolkits\Hooks\Helper::promotion_color_class( $instance )
	: '';
?>
<div class="rtcl rtcl-listings-sc-wrapper clcar-elementor-widget <?php echo esc_attr( $promo_color_class ); ?>">
	<div class="rtcl-listings-wrapper">
		<?php
		$class  = '';
		$class .= ! empty( $view ) ? 'rtcl-' . $view . '-view ' : 'rtcl-list-view ';
		$class .= ! empty( $style ) ? 'rtcl-' . $style . '-view ' : 'rtcl-style-1-view ';

		$class .= ! empty( $instance['rtcl_listings_column'] ) ? 'columns-' . $instance['rtcl_listings_column'] . ' ' : ' columns-1';
		$class .= ! empty( $instance['rtcl_listings_column_tablet'] ) ? 'tab-columns-' . $instance['rtcl_listings_column_tablet'] . ' ' : ' tab-columns-2';
		$class .= ! empty( $instance['rtcl_listings_column_mobile'] ) ? 'mobile-columns-' . $instance['rtcl_listings_column_mobile'] . ' ' : ' mobile-columns-2';

		?>
		<div class="rtcl-listings <?php echo esc_attr( $class ); ?> ">
			<?php

			while ( $the_loops->have_posts() ) :
				$the_loops->the_post();
				$_id                 = get_the_ID();
				$post_meta           = get_post_meta( $_id );
				$listing             = new Listing( $_id );
				$listing_title       = null;
				$listing_meta        = null;
				$listing_description = null;
				$img                 = null;
				$labels              = null;
				$u_info              = null;
				$time                = null;
				$location            = null;
				$category            = null;
				$price               = null;
				$types               = null;
				$img_position_class  = '';
				$custom_field 		 = null;
				?>

			<div <?php Functions::listing_class( ['listing-box listing-item rtcl-listing-item', $img_position_class ] ); ?>>
				<?php if ( $instance['rtcl_show_image'] ) { ?>
					<div class="listing-thumb">
						<?php 
						$image_size    = $instance['rtcl_thumb_image_size'];
						$the_thumbnail = $listing->get_the_thumbnail( $image_size );
						if($the_thumbnail){
							echo wp_kses_post($the_thumbnail);
						}
						// Sold out banner and badges sit together on the photo
						$thumb_badges = '';
						if ( $listing && Fns::is_enable_mark_as_sold() && Fns::is_mark_as_sold( $listing->get_id() ) ) {
							$thumb_badges .= '<span class="rtcl-sold-out">' . wp_kses_post( apply_filters( 'rtcl_sold_out_banner_text', esc_html__( "Sold Out", 'clcar' ) ) ) . '</span>';
						}
						if ( $instance['rtcl_show_labels'] ) {
							$thumb_badges .= $listing->badges();
						}
						if ( trim( wp_strip_all_tags( $thumb_badges ) ) ) {
							echo '<div class="clcar-thumb-badges">' . wp_kses_post( $thumb_badges ) . '</div>';
						}
						?>
						<div class="listing-action-items">
							<div class="clcar-listing-action">
								<?php if($instance['rtcl_show_favourites']){
										echo wp_kses_post( Listing_Functions::get_favourites_link( $listing->get_id() ) );
									} 
								?>
								<?php if ( Fns::is_enable_compare() && $instance['rtcl_show_compare']) {
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
				<?php } ?>
				<div class="item-content">
                    <div class="brand-wrap">
	                    <?php
	                    if ( Functions::isEnableFb() ) {
		                    $logoId = get_post_meta( $listing->get_id(), 'listing_logo', true );
		                    if ( ! empty( $logoId ) ) {
			                    $logo_id = $logoId[0];
		                    } else {
			                    $logo_id = '';
		                    }
	                    } else {
		                    $logo_id = get_post_meta( $listing->get_id(), 'listing_logo_img', true );
	                    };
	                    if( $logo_id ){
		                    ?>
                            <div class="brand-logo">
			                    <?php Listing_Functions::listing_logo(); ?>
                            </div>
	                    <?php } ?>
					</div>
					<?php $listing_type = Listing_Functions::get_listing_type( $listing );
					if ( ! empty( $listing_type ) && $instance['rtcl_show_types']  ) : ?>
						<span class="listing-add-type">
							<?php echo wp_kses_post( sprintf( "%s %s", apply_filters( 'rtcl_type_prefix', __( 'For', 'clcar' ) ), esc_html( $listing_type['label'] ) ) ); ?>
						</span>
					<?php endif;
						if ( $listing->has_category() && $instance['rtcl_show_category'] && ! Functions::is_listing() ):
						$category = $listing->get_categories();
						$category = end( $category );
						?>
						<div class="listing-category">
							<a href="<?php echo esc_url( get_term_link( $category->term_id, $category->taxonomy ) ); ?>"><?php echo esc_html( $category->name ) ?></a>
						</div>
					<?php endif; ?>

					<?php if ( $instance['rtcl_show_price'] ) { ?>
						<div class="item-price"><?php echo wp_kses_post( $listing->get_price_html() ); ?></div>
					<?php } ?>
					<?php if ( $instance['rtcl_show_title'] ) {
								printf(
									'<h3 class="listing-title rtcl-listing-title"><a href="%1$s" title="%2$s">%2$s</a></h3>',
									esc_url(get_the_permalink()),
									esc_html( get_the_title() )
								);
							} 
					?>	
					<?php if ( $instance['rtcl_show_labels'] && ! $instance['rtcl_show_image'] ) {
							$listing->the_badges();
						}
					?>
					<ul class="entry-meta">
						<?php if ( $instance['rtcl_show_user'] ): ?>
							<li class="author">
								<i class="fas fa-user"></i><?php esc_html_e( 'by ', 'clcar' ); ?>
								<?php if ( $listing->can_add_user_link() && ! is_author() ) : ?>
									<a href="<?php echo esc_url( $listing->get_the_author_url() ); ?>"><?php $listing->the_author(); ?></a>
								<?php else : ?>
									<?php $listing->the_author(); ?>
								<?php endif; ?>
							</li>
						<?php endif; ?>
						<?php if ( $listing->has_location() && $instance['rtcl_show_location']): ?>
							<li><i class="icon-location"></i><?php $listing->the_locations(); ?></li>
						<?php endif; ?>
						<?php if ( $instance['rtcl_show_date'] ): ?>
							<li class="updated"><i class="icon-calendar-1"></i><?php $listing->the_time(); ?></li>
						<?php endif; ?>
						<?php if ( $instance['rtcl_show_views'] ): ?>
							<li class="rt-views">
								<i class="icon-view"></i>
								<?php echo esc_html( sprintf( _n( "%s view", "%s views", $listing->get_view_counts(), 'clcar' ),
									number_format_i18n( $listing->get_view_counts() ) ) ); ?>
							</li>
						<?php endif; ?>
					</ul>
					<?php if ( $instance['rtcl_show_description'] ) {
							$excerpt = get_the_excerpt( $_id );
							printf(
								'<div class="listing-excerpt"> %s </div>',
								wp_kses_post(wpautop( $excerpt ))
							);
						} 
					?>	
					<?php if (rtcl()->has_pro()) {
							if (!empty($instance['rtcl_show_custom_fields'])) {
								 TemplateHooks::loop_item_listable_fields();
							}
						}
					?>
				</div>
			</div>

			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>

		</div>
		<?php if ( ! empty( $instance['rtcl_listing_pagination'] ) ) { ?>
			<?php Pagination::pagination( $the_loops ); ?>
		<?php } ?>
	</div>
</div>
