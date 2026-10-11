<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Rtcl\Models\Listing;
use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;
use radiustheme\ClCar\Helper;
use radiustheme\ClCar\Listing_Functions;

$data = [
	'template'              => 'elementor/listing-ads-slider/slider-header',
	'view'                  => $view,
	'style'                 => $style,
	'instance'              => $instance,
	'the_loops'             => $the_loops,
	'default_template_path' => $default_template_path,
];
$data = apply_filters( 'rtcl_el_listing_slider_filter_data', $data );
Functions::get_template( $data['template'], $data, '', $data['default_template_path'] );

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
	$img_position_class  = '';
	$types               = null;
	$custom_field  = null;
	$phone               = get_post_meta( $_id, 'phone', true );

	?>

	<div <?php Functions::listing_class( [ 'listing-box rtcl-widget-listing-item', 'listing-item swiper-slide-customize', $img_position_class ] ); ?>>


		<?php if ( $instance['rtcl_show_image'] ) { ?>
            <div class="listing-thumb">

				<?php $listing_type = Listing_Functions::get_listing_type( $listing );
				if ( ! empty( $listing_type ) && $instance['rtcl_show_types']  ) : ?>
                    <span class="listing-add-type">
                                <?php echo esc_html( $listing_type['label'] ); ?>
                            </span>
				<?php endif; ?>

				<?php
				$image_size    = $instance['rtcl_thumb_image_size'];
				$the_thumbnail = $listing->get_the_thumbnail( $image_size );
				if( $the_thumbnail ){
					echo wp_kses_post( $the_thumbnail );
				}
				if ( $listing && Fns::is_enable_mark_as_sold() && Fns::is_mark_as_sold( $listing->get_id() ) ) {
					echo '<span class="rtcl-sold-out">' . wp_kses_post( apply_filters( 'rtcl_sold_out_banner_text', esc_html__( "Sold Out", 'clcar' ) ) ) . '</span>';
				}
				?>

                <div class="listing-action-items">
					<?php if ( $instance['rtcl_show_user'] ): ?>
                        <div class="author">
							<?php
							echo wp_kses_post( $listing->get_author_logo() );
							if ( $listing->can_add_user_link() && ! is_author() ) : ?>
                                <a href="<?php echo esc_url( $listing->get_the_author_url() ); ?>"><?php $listing->the_author(); ?></a>
							<?php else : ?>
								<?php $listing->the_author(); ?>
							<?php endif; ?>
                        </div>
					<?php endif; ?>

                    <div class="clcar-listing-action">
						<?php

						if($instance['rtcl_show_brand_logo']){
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
							if( $logo_id ){ ?>
                                <div class="brand-logo">
									<?php Listing_Functions::listing_logo(); ?>
                                </div>
							<?php }
						}
						?>

						<?php if($instance['rtcl_show_favourites']){ ?>
                            <div class="add-to-favourite-action">
								<?php echo wp_kses_post( Listing_Functions::get_favourites_link( $listing->get_id() ) ); ?>
                            </div>
						<?php } ?>

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
			<?php
			if ( $listing->has_category() && $instance['rtcl_show_category'] && ! Functions::is_listing() ):
				$category = $listing->get_categories();
				$category = end( $category );
				?>
                <div class="listing-category">
                    <a href="<?php echo esc_url( get_term_link( $category->term_id, $category->taxonomy ) ); ?>"><?php echo esc_html( $category->name ) ?></a>
                </div>
			<?php endif; ?>
			<?php if ( $instance['rtcl_show_title'] ) {
				printf(
					'<h3 class="listing-title rtcl-listing-title"><a href="%1$s" title="%2$s">%2$s</a></h3>',
					esc_url(get_the_permalink()),
					esc_html( get_the_title() )
				);
			}
			?>
			<?php
			if ( rtcl()->has_pro() ) {
				if ( ! empty( $instance['rtcl_show_custom_fields'] ) ) {
					$args = [
						'fields_label' => $instance['rtcl_show_custom_fields_label']
					];
					Helper::get_custom_listing_template( 'c-fields-archive-meta', true, $args );
				}
			}
			?>
			<?php if ( $instance['rtcl_show_labels'] ) {
				$listing->the_badges();
			}
			?>

			<?php if ($instance['rtcl_show_location'] || $instance['rtcl_show_date'] || $instance['rtcl_show_views'] ){ ?>
                <ul class="entry-meta">
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
			<?php } ?>

			<?php if ( $instance['rtcl_show_description'] ) {
				$excerpt = get_the_excerpt( $_id );
				printf(
					'<div class="listing-excerpt"> %s </div>',
					wp_kses_post(wpautop( $excerpt ))
				);
			}
			?>
			<?php if ( $instance['rtcl_show_price'] ) { ?>
                <div class="item-price"><?php echo wp_kses_post( $listing->get_price_html() ); ?></div>
			<?php } ?>
        </div>

	</div>

<?php endwhile; ?>
<?php wp_reset_postdata(); ?>

<?php

$data = [
	'template'              => 'elementor/listing-ads-slider/slider-footer',
	'view'                  => $view,
	'style'                 => $style,
	'instance'              => $instance,
	'the_loops'             => $the_loops,
	'default_template_path' => $default_template_path,
];
$data = apply_filters( 'rtcl_el_listing_slider_filter_data', $data );
Functions::get_template( $data['template'], $data, '', $data['default_template_path'] );
