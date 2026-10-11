<?php
/**
 * Login Form Information
 *
 * @var Listing $listing
 * @var int     $title_limit
 * @var array   $hidden_fields
 * @var string  $selected_type
 * @var string  $title
 * @var string  $price_type
 * @var string  $price
 * @var string  $post_content
 * @var string  $editor
 * @var int     $category_id
 * @var int     $post_id
 * @var int     $description_limit
 * @author        RadiusTheme
 * @package       classified-listing/templates
 * @version       1.0.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use Rtcl\Models\Listing;
use Rtcl\Resources\Options;
use radiustheme\ClCar\Listing_Functions;

?>

<div class="rtcl-post-details rtcl-post-section">
    <div class="rtcl-post-listing-logo rtcl-post-section">
        <div class="rtcl-post-section-title">
            <h3><i class="rtcl-icon rtcl-icon-picture"></i><?php esc_html_e( 'Listing Logo', 'clcar' ); ?></h3>
        </div>
        
        <?php $logoID = get_post_meta( $post_id, "listing_logo_img", true ); ?>
        <?php if ( ! empty( $logoID ) ): ?>
            <div class="logo-image">
                <input name="logo_attachment_id" type="hidden" value="<?php echo esc_attr( $logoID ); ?>">
                <?php echo wp_get_attachment_image( $logoID, 'full' ); ?>
                <div class="remove-logo-image">
                    <a href="#" data-post_id="<?php echo esc_attr( $post_id ); ?>" data-attachment_id="<?php echo esc_attr( $logoID ); ?>">
                        <i class="rtcl-icon rtcl-icon-trash"></i>
                    </a>
                </div>
            </div>
        <?php endif; ?>
        <div class="logo-input-wrapper <?php echo esc_attr( $logoID ? 'd-none' : '' ); ?>">
            <input name="listing_logo_img" class="listygo-logo-image" type="file"/>
        </div>
    </div>

    <div class="rtcl-post-section-title">
        <h3>
            <i class="rtcl-icon rtcl-icon-picture"></i><?php esc_html_e( "Listing Information", "clcar" ); ?>
        </h3>
    </div>
    <div class="form-group">
        <label for="rtcl-title"><?php esc_html_e( 'Title', 'clcar' ); ?><span
                    class="require-star">*</span></label>
        <input type="text"
			<?php echo esc_attr( $title_limit ? 'data-max-length="3" maxlength="' . $title_limit . '"' : '' ); ?>
               class="form-control"
               value="<?php echo esc_attr( $title ); ?>"
               id="rtcl-title"
               name="title"
               required/>
		<?php
		if ( $title_limit ) {
			echo wp_kses_post( sprintf( '<div class="rtcl-hints">%s</div>',
				apply_filters( 'rtcl_listing_title_character_limit_hints', sprintf( __( "Character limit <span class='target-limit'>%s</span>", 'clcar' ), $title_limit )
				) ) );
		}
		?>
    </div>
	<?php if ( ! in_array( 'price', $hidden_fields ) ):
		$listingPricingTypes = Options::get_listing_pricing_types();
		?>
        <div id="rtcl-pricing-wrap">
            <?php if (!Functions::is_pricing_disabled()) { ?>
                <div class="rtcl-post-section-title">
                    <h3><?php esc_html_e( "Pricing:", "clcar" ); ?></h3>
                </div>
                <div class="rtcl-from-group rtcl-checkbox-list rtcl-checkbox-inline rtcl-listing-pricing-types">
                    <?php
                    foreach ( $listingPricingTypes as $type_id => $type ) {
                        $checked = ( $listing_pricing === $type_id ) ? 'checked' : '';
                        ?>
                        <div class="rtcl-checkbox rtcl-listing-pricing-type">
                            <input type="radio" name="_rtcl_listing_pricing"
                                   id="_rtcl_listing_pricing_<?php echo esc_attr( $type_id ) ?>"
                                <?php echo esc_attr( $checked ); ?>
                                   value="<?php echo esc_attr( $type_id ) ?>">
                            <label for="_rtcl_listing_pricing_<?php echo esc_attr( $type_id ) ?>">
                                <?php echo esc_html( $type ); ?>
                            </label>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            <?php } ?>
            <div id="rtcl-pricing-items" class="<?php echo esc_attr( 'rtcl-pricing-' . $listing_pricing ) ?>">
				<?php if ( ! Functions::is_price_type_disabled() ): ?>
                    <div class="form-group rtcl-pricing-item rtcl-from-group">
                        <label for="rtcl-price-type">
							<?php esc_html_e( 'Price Type', 'clcar' ); ?>
                            <span class="require-star">*</span>
                        </label>
                        <select class="form-control" id="rtcl-price-type" name="price_type">
							<?php
							$price_types = Options::get_price_types();
							foreach ( $price_types as $key => $type ) {
								$slt = $price_type == $key ? " selected" : null;
								echo wp_kses_post( "<option value='" . esc_attr( $key ) . "'" . esc_attr( $slt ) . ">" . esc_html( $type ) . "</option>" );
							}
							?>
                        </select>
                    </div>
				<?php endif; ?>
                <?php do_action( 'rtcl_listing_form_price_items', $listing ); ?>
                <div id="rtcl-price-items"
                     class="rtcl-pricing-item<?php echo ! Functions::is_price_type_disabled() ? ' rtcl-price-type-' . esc_attr( $price_type ) : '' ?>">
                     <div class="form-group rtcl-price-item" id="rtcl-price-wrap">
                        <div class="price-wrap">
                            <label
                                    for="rtcl-price"><?php echo wp_kses_post( sprintf( '<span class="price-label">%s [<span class="rtcl-currency-symbol">%s</span>]</span>',
					                esc_html__( "Price", 'clcar' ),
					                apply_filters( 'rtcl_listing_price_currency_symbol', Functions::get_currency_symbol(), $listing )
				                ) ); ?>
                                <span
                                        class="require-star">*</span></label>
                            <input type="text"
                                   class="form-control rtcl-price"
                                   value="<?php echo $listing ? esc_attr( $listing->get_price() ) : ''; ?>"
                                   name="price"
                                   id="rtcl-price"<?php echo esc_attr( ! $price_type || $price_type == 'fixed' ? " required" : '' ) ?>>
                        </div>
                        <div class="price-wrap rtcl-max-price rtcl-hide">
                            <label
                                    for="rtcl-max-price"><?php echo wp_kses_post( sprintf( '<span class="price-label">%s [<span class="rtcl-currency-symbol">%s</span>]</span>',
					                esc_html__( "Max Price", 'clcar' ),
					                apply_filters( 'rtcl_listing_price_currency_symbol', Functions::get_currency_symbol(), $listing )
				                ) ); ?><span
                                        class="require-star">*</span></label>
                            <input type="text"
                                   class="form-control rtcl-price"
                                   value="<?php echo $listing ? esc_attr( $listing->get_max_price() ) : ''; ?>"
                                   name="_rtcl_max_price"
                                   id="rtcl-max-price"<?php echo esc_attr( ! $price_type || $price_type == 'fixed' ? " required" : '' ) ?>>
                        </div>
                    </div>
					<?php do_action( 'rtcl_listing_form_price_unit', $listing, $category_id ); ?>
                </div>
            </div>
        </div>
	<?php endif; ?>
    <div id="rtcl-custom-fields-list" data-post_id="<?php echo esc_attr( $post_id ); ?>">
		<?php do_action( 'wp_ajax_rtcl_custom_fields_listings', $post_id, $category_id ); ?>
    </div>
    <?php if ( ! in_array( 'description', $hidden_fields ) ): ?>
        <div class="form-group">
            <label for="description"><?php esc_html_e( 'Description', 'clcar' ); ?></label>
			<?php

			if ( 'textarea' == $editor ) { ?>
                <textarea
                        id="description"
                        name="description"
                        class="form-control"
                        <?php echo esc_attr( $description_limit ? 'maxlength="' . $description_limit . '"' : '' ); ?>
                        rows="8"><?php Functions::print_html( $post_content ); ?></textarea>
				<?php
			} else {
				wp_editor(
					$post_content,
					'description',
					[
						'media_buttons' => false,
						'editor_height' => 200,
					]
				);
			}

			if ( $description_limit ) {
				echo wp_kses_post( sprintf( '<div class="rtcl-hints">%s</div>',
					apply_filters( 'rtcl_listing_description_character_limit_hints',
						sprintf( __( "Character limit <span class='target-limit'>%s</span>", 'clcar' ), $description_limit )
					) ) );
			}
			?>
        </div>
	<?php endif; ?>
</div>