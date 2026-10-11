<?php
/**
 *
 * @var array $fields
 * @var int   $listing_id
 * @version       1.0.0
 *
 * @author        RadiusTheme
 * @package       classified-listing/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Models\RtclCFGField;
use radiustheme\ClCar\RDTheme;
$car_distance_cf_group_id = isset( RDTheme::$options['car_distance_cf_group_id'] ) ? RDTheme::$options['car_distance_cf_group_id'] : 0;
$car_distance_unit_value = isset( RDTheme::$options['car_distance_cf_unit_text'] ) ? RDTheme::$options['car_distance_cf_unit_text'] : '';
if ( count( $fields ) ) :
	ob_start();

	foreach ( $fields as $field ) :
		$field_parent_id = $field->post_parent;
		$field = new RtclCFGField( $field->ID ); 
		$value = $field->getFormattedCustomFieldValue( $listing_id );
		if ( $value ) :
			?>
            <li>
				<?php if ( $field->getIconClass() ): ?>
                    <i class='<?php echo esc_html( $field->getIconClass() ); ?>'></i>
				<?php else: ?>
                    <span class='listable-label'><?php echo esc_html( $field->getLabel().':' ); ?></span>
				<?php endif; ?>
                <span class='listable-value'>
                    <span class="value">
                    <?php
                    $value = strlen( $value ) == 1 ? '<span>0</span>' . $value : $value;
                    echo wp_kses_post( stripslashes_deep( $value ) );
                    if (!empty($field_parent_id) && $field_parent_id == $car_distance_cf_group_id){
                        echo esc_html(' '.$car_distance_unit_value);
                    }
                    ?>
                    </span>
                </span>
            </li>
		<?php endif;
	endforeach;

	$fields_html = ob_get_clean();
    $show_listing_custom_field = RDTheme::$options['show_listing_custom_fields'];
	if ( $fields_html && $show_listing_custom_field) {
		printf( '<div class="listable-items"><ul class="listing-features">%s</ul></div>', wp_kses_post( $fields_html ) );
	}
endif;
