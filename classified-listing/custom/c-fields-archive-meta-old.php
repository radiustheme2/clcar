<?php
/**
 * Car Specifications Display for Archive/Single Page
 * Only shows fields that have "Display at archive page" checked in backend
 */

if (!defined('ABSPATH')) exit;

// ধরে নিচ্ছি তুমি RadiusTheme এর Classified Listing প্লাগিন ব্যবহার করছো (RtCL)
// এই ফাংশনগুলো আছে: rtcl()->get_field_display_data() অথবা get_post_meta()

$post_id = get_the_ID();
if (!$post_id) return;

// RtCL এর জন্য ফিল্ড visibility চেক করার সঠিক উপায়
$fields = apply_filters('rtcl_listing_custom_fields', [], $post_id); // কিছু ক্ষেত্রে এভাবে পাওয়া যায়
// অথবা সরাসরি meta থেকে নেওয়া যায় (নিচে সেফ উপায়)

$display_fields = [
	'select_make'           => 'Make',
	'select_model'          => 'Model',
	'year-of-manufacture'   => 'Build Year',
	'body-type'             => 'Body Type',
	'mileage-km'            => 'Mileage (KM)',
	'fuel-type'             => 'Fuel Type',
	'engine-capacity-cc'    => 'Engine Capacity (CC)',
	'transmission'          => 'Transmission',
	'transmission-type'     => 'Transmission Type',
	'drive-type'            => 'Drive Type',
	'number-of-doors'       => 'Number of Doors',
	'number-of-seats'       => 'Number of Seats',
	'exterior-color'        => 'Exterior Color',
	'interior-color'        => 'Interior Color',
	'registration'          => 'Registration',
];

$specs = [];

foreach ($display_fields as $key => $label) {
	$value = get_post_meta($post_id, $key, true);

	// Visibility চেক: "Display at archive page" চেক করা আছে কিনা
	$show_in_archive = get_post_meta($post_id, "show_in_archive_{$key}", true); // RtCL এভাবেই সেভ করে
	// কিছু ক্ষেত্রে অন্য নামে সেভ হতে পারে, নিচে ব্যাকআপ চেক
	if ($show_in_archive === '' || $show_in_archive === false) {
		$show_in_archive = get_post_meta($post_id, "_show_{$key}", true); // বিকল্প
	}
	if ($show_in_archive === '' || $show_in_archive === false) {
		$show_in_archive = apply_filters("rtcl_show_field_in_archive_{$key}", true, $post_id);
	}

	// যদি visibility চেক করা না থাকে → দেখাবে না
	if (!$show_in_archive || $value === '' || $value === '0') {
		continue;
	}

	// লেবেল ফরম্যাটিং (যদি taxonomy হয়)
	if (in_array($key, ['make', 'model', 'body_type', 'fuel_type', 'transmission_type', 'drive_type', 'exterior_color', 'interior_color'])) {
		$term = get_term($value);
		$value = $term && !is_wp_error($term) ? $term->name : $value;
	}

	// Year, Mileage, Engine CC ফরম্যাট
	if ($key === 'year' && $value) {
		$value = $value;
	}
	if ($key === 'mileage' && $value) {
		$value = number_format_i18n($value) . ' KM';
	}
	if ($key === 'engine_cc' && $value) {
		$value = number_format_i18n($value) . ' CC';
	}
	if ($key === 'doors' && $value) {
		$value = $value;
	}
	if ($key === 'seats' && $value) {
		$value = $value;
	}

	$specs[] = [
		'label' => $label,
		'value' => $value
	];
}

// যদি কোনো ফিল্ড না থাকে → কিছুই দেখাবে না
if (empty($specs)) {
	return;
}
?>

<div class="rt-car-specs-grid">
	<?php foreach ($specs as $spec): ?>
        <div class="rt-spec-item">
            <span class="rt-spec-label"><?php echo esc_html($spec['label']); ?>:</span>
            <span class="rt-spec-value"><?php echo esc_html($spec['value']); ?></span>
        </div>
	<?php endforeach; ?>
</div>

<style>
    .rt-car-specs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
        margin: 20px 0;
        padding: 15px;
        background: #f9f9f9;
        border-radius: 8px;
        border: 1px solid #eee;
    }
    .rt-spec-item {
        display: flex;
        flex-direction: column;
        font-size: 14px;
    }
    .rt-spec-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 4px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .rt-spec-value {
        color: var(--rt-primary-color, #0179e8);
        font-weight: 600;
    }
    @media (max-width: 768px) {
        .rt-car-specs-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
</style>