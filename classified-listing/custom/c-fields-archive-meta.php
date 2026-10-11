<?php
/**
 * Car Specs for Archive/Grid Page
 * Works with BOTH: Form Builder (Pro) + Old Custom Fields (Free/Pro)
 * Only shows fields that are marked to display on archive/listing
 */

if (!defined('ABSPATH')) exit;

use Rtcl\Helpers\Functions;
use Rtcl\Services\FormBuilder\FBField;

global $listing;
$listing_id = get_the_ID();
if (!$listing_id || empty($listing)) return;

$archive_specs = [];

// Checking Form Builder is active
if (class_exists('Rtcl\Services\FormBuilder\FBHelper') && \Rtcl\Services\FormBuilder\FBHelper::isEnabled()) {

	$form = $listing->getForm();
	if ($form && method_exists($form, 'getFieldAsGroup')) {
		$fields = $form->getFieldAsGroup(FBField::CUSTOM);

		if ($fields) {
			$fields = \Rtcl\Services\FormBuilder\FBHelper::reOrderCustomField($fields);

			foreach ($fields as $field_data) {
				$field = new FBField($field_data);

				// Display at archive page
				if (!$field->getData('archive_view', false)) {
					continue;
				}

				$value = $field->getFormattedCustomFieldValue($listing_id);
				if (empty($value)) continue;

				$icon = $field->getIconData();
				$icon_class = (!empty($icon['type']) && $icon['type'] === 'class' && !empty($icon['class']))
					? $icon['class'] : '';

				$archive_specs[] = [
					'label' => $field->getLabel(),
					'value' => $value,
					'icon'  => $icon_class,
					'name'  => $field->getName(),
				];
			}
		}
	}
}
// Old System
else {
	$category_id = Functions::get_term_child_id_for_a_post($listing->get_categories());
	$custom_field_ids = Functions::get_custom_field_ids($category_id);

	if (!empty($custom_field_ids)) {
		$args = [
			'post_type'      => rtcl()->post_type_cf,
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'post__in'       => $custom_field_ids,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'meta_query'     => [
				[
					'key'     => '_listable',
					'value'   => 1,
					'compare' => '='
				]
			]
		];

		$fields = get_posts($args);

		foreach ($fields as $field_post) {
			$field = new \Rtcl\Models\RtclCFGField($field_post->ID);
			$value = $field->getFormattedCustomFieldValue($listing_id);

			if (empty($value)) continue;

			$archive_specs[] = [
				'label' => $field->getLabel(),
				'value' => $value,
				'icon'  => '', // Old system has no icon
			];
		}
	}
}

if (empty($archive_specs)) return;

?>

<?php
// Filter out invalid specs before rendering.
$valid_specs = array_filter( $archive_specs, function( $spec ) {
	return ! empty( $spec['name'] ) && ! empty( $spec['value'] );
} );
?>

<?php if ( ! empty( $valid_specs ) ) : ?>
    <div class="rt-car-specs-archive">
        <div class="rt-car-specs-grid">
			<?php foreach ( $valid_specs as $spec ) : ?>
                <div class="rt-spec-item field-name-<?php echo esc_attr( $spec['name'] ); ?>">
                    <div class="rt-spec-label-wrap">
						<?php if ( ! empty( $spec['icon'] ) ) { ?>
                            <div class="rtcl-field-icon"><i class="<?php echo esc_attr( $spec['icon'] ); ?>"></i></div>
						<?php } ?>

						<?php if ( ! empty( $fields_label ) ) { ?>
                            <div class="cfp-label"><?php echo esc_html( $spec['label'] ); ?></div>
						<?php } ?>
                    </div>
                    <div class="cfp-value">
						<?php
						echo wp_kses_post( $spec['value'] );
						if ( 'mileage-km' === $spec['name'] ) {
							echo ' Km';
						}
						?>
                    </div>
                </div>
			<?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>