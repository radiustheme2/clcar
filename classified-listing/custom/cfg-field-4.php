<?php
/**
 * This file is for showing listing header
 *
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use radiustheme\ClCar\RDTheme;
use Rtcl\Helpers\Functions;
use Rtcl\Models\RtclCFGField;

global $listing;

$group_id = isset( RDTheme::$options['custom_group_individual4'] ) ? RDTheme::$options['custom_group_individual4'] : 0;

if ( $group_id ) {
	$field_ids   = Functions::get_cf_ids_by_cfg_id( $group_id );
	$group_title = get_the_title( $group_id );
}

?>
<div class="listing-amenities cfg-field-3">
    <ul class="amenities-list">
		<?php
		if ( ! empty( $field_ids ) ) { 
			$count = 1;
			foreach ( $field_ids as $single_field ) {

				$field = new RtclCFGField( $single_field );
				$value = $field->getFormattedCustomFieldValue( $listing->get_id() );
				if ( ! $value || empty( $value ) ) {
					continue;
				}
				$_order = $_is_check_box = $_check_box_item = NULL;
				if ( $field->getType() === 'checkbox' ) {
					$amenities = Functions::get_cf_data( $single_field );
					$data      = $amenities['value'];
					$options   = $amenities['options']['choices'];

					$_is_check_box = 'is-check-box';
					$_order        = "order:20".$count;
					$_check_box_item = count($options)>1 ? $_is_check_box : '';
				}

				?>
                <li class="<?php echo esc_attr( $_is_check_box ); ?> rtcl-field-<?php echo esc_attr( $field->getType() ) ?>">
						<?php if ( $field->getLabel() ): ?>
                            <div class="heading-title rtcl-field-<?php echo esc_attr( $field->getType() ) ?>">
								<?php echo esc_html( $field->getLabel() ).':'; ?>
							</div>
						<?php endif; ?>
                        <span class="cfp-value">
                            <?php
                            if ( $field->getType() === 'checkbox' ) {
	                            foreach ( $options as $key => $value ) {
		                            if ( in_array( $key, $data ) === true ) {
			                            printf( "<span class='aminities-list-item'><i class='icon-correct3'></i>%s</span>", esc_html( $value ) );
		                            }
	                            }
	                            ?>
	                            <?php
                            } else {
	                            if ( ! empty( $value ) ): ?>
		                            <?php if ( $field->getType() === 'url' ):
			                            $nofollow = ! empty( $field->getNofollow() ) ? ' rel="nofollow"' : ''; ?>
                                        <a href="<?php echo esc_url( $value ); ?>"
                                           target="<?php echo esc_attr( $field->getTarget() ) ?>"
                                                    <?php echo esc_html( $nofollow ) ?>><?php echo esc_html( $field->getLabel() ) ?></a>
		                            <?php else: ?>
			                            <?php Functions::print_html( $value ); ?>
		                            <?php endif; ?>
	                            <?php endif;
                            }
                            ?>
                        </span>
                </li>
				<?php
				$count ++;
			}
		}
		?>
    </ul>
</div>