<?php
/**
 * @var array $fields
 * @var int   $listing_id
 * @version       1.0.0
 *
 * @author        RadiusTheme
 * @package       classified-listing/views/public
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use radiustheme\ClCar\RDTheme;
use Rtcl\Helpers\Functions;
use Rtcl\Models\RtclCFGField;

$field_list = array();
$field_list1=array();
$field_list2=array();
$field_list3=array();
$field_list4=array();

$group_id1 = isset( RDTheme::$options['custom_group_individual'] ) ? RDTheme::$options['custom_group_individual'] : 0;

$group_id2 = isset( RDTheme::$options['custom_group_individual2'] ) ? RDTheme::$options['custom_group_individual2'] : 0;

$group_id3 = isset( RDTheme::$options['custom_group_individual3'] ) ? RDTheme::$options['custom_group_individual3'] : 0;

$group_id4 = isset( RDTheme::$options['custom_group_individual4'] ) ? RDTheme::$options['custom_group_individual4'] : 0;

if ( $group_id1 ) {
	$field_list1 = Functions::get_cf_ids_by_cfg_id( $group_id1 );
}
if ( $group_id2 ) {
	$field_list2 = Functions::get_cf_ids_by_cfg_id( $group_id2 );
}
if($group_id3){
	$field_list3 = Functions::get_cf_ids_by_cfg_id( $group_id3 );
}
if($group_id4){
	$field_list4 = Functions::get_cf_ids_by_cfg_id( $group_id4 );
}

$field_list = array_merge( $field_list, $field_list1, $field_list2, $field_list3, $field_list4 );
$car_distance_cf_group_id = isset( RDTheme::$options['car_distance_cf_group_id'] ) ? RDTheme::$options['car_distance_cf_group_id'] : 0;
$car_distance_unit_value = isset( RDTheme::$options['car_distance_cf_unit_text'] ) ? RDTheme::$options['car_distance_cf_unit_text'] : '';

if ( count( $fields ) ):
	ob_start();
	foreach ( $fields as $field ):
		// if ( ! empty( $field_list ) && in_array( $field->ID, $field_list ) ) {
		// 	continue;
		// }
		$field_parent_id = $field->post_parent;
		$field = new RtclCFGField( $field->ID );
		$value = $field->getFormattedCustomFieldValue( $listing_id );
		$field_title = $field->getLabel();
		if ( ! empty( $value ) ): ?>
            <div class="field-item rtcl-field-<?php echo esc_attr( $field->getType() ) ?>">

				<?php if ( $field_title && $field->getType() === 'checkbox') { ?>
					<h3 class="listing-entry-inner-title">
						<?php echo esc_html( $field_title ); ?>
					</h3>
				<?php } ?>

				<?php if ( $field->getType() === 'url' ){
					$nofollow = ! empty( $field->getNofollow() ) ? ' rel="nofollow"' : ''; ?>
                    <a href="<?php echo esc_url( $value ); ?>"
                       target="<?php echo esc_attr( $field->getTarget() ) ?>"
						<?php echo esc_html( $nofollow ) ?>>
						<?php echo esc_html( $field->getLabel() ) ?>
                    </a>
				<?php } if($field->getType() === 'checkbox'){ ?>
                    <div class="checkbox-field cfp-value">
						<?php
						$c_val = explode( ',', $value );
						$available=__('Yes','clcar');
						echo "<ul>";
						foreach ( $c_val as $val ) {
							printf("<li><span>%s</span><span class='label'><span class='icon'><i class='icon-correct3'></i>%s</span></span></li>",esc_html( $val ),esc_html( $available ));
						}
						echo "</ul>";
						?>
                    </div>
				<?php } else {?>
					<h5 class="cfp-label"><span><?php echo esc_html( $field->getLabel() ) ?></span> : </h5>
                    <span class="text-muted cfp-value">
                        <?php
                            Functions::print_html( $value );
                            if (!empty($field_parent_id) && $field_parent_id == $car_distance_cf_group_id){
                                echo esc_html(' '.$car_distance_unit_value);
                            }
                        ?>
                    </span>
				<?php } ?>
            </div>
		<?php endif; ?>
	<?php endforeach;
	$fieldData = ob_get_clean();
	?>
	<?php
	if ( $fieldData ):
		printf( '<div class="rtcl-custom-field-section single-content default-custom-field">%s</div>', wp_kses_post( $fieldData ) );
	endif; ?>

<?php endif;
