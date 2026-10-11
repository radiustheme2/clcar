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

use radiustheme\ClCar\Helper;
use radiustheme\ClCar\Listing_Functions;
use Rtcl\Controllers\Hooks\TemplateHooks;

global $listing;

$args = [
	'fields_label' => ''
];

?>

<?php

/**
 * Hook: rtcl_before_listing_loop_item.
 *
 * @hooked rtcl_template_loop_product_link_open - 10
 */
do_action( 'rtcl_before_listing_loop_item' );

?>

<?php
/**
 * Hook: rtcl_listing_loop_item.
 *
 * @hooked listing_thumbnail - 10
 */
do_action( 'rtcl_listing_loop_item_start' );
?>

<div class="item-content layout-2">
<?php
do_action( 'loop_item_wrap_start' );
/**
 * Hook: rtcl_listing_loop_item.
 *
 * @hooked loop_item_wrap_start - 10
 * @hooked loop_item_listing_title - 20
 * @hooked loop_item_labels - 30
 * @hooked loop_item_listable_fields - 40
 * @hooked loop_item_meta - 50
 * @hooked loop_item_excerpt - 60
 * @hooked loop_item_wrap_end - 100
 */
TemplateHooks::loop_item_badges();
Listing_Functions::loop_item_listing_category();
TemplateHooks::loop_item_listing_title();
Helper::get_custom_listing_template( 'c-fields-archive-meta', true, $args );
TemplateHooks::loop_item_meta();
TemplateHooks::loop_item_excerpt();
?>
    <div class="item-cat-price">
        <?php TemplateHooks::listing_price(); ?>
    </div>
</div>
<?php
/**
 * Hook: rtcl_after_listing_loop_item.
 *
 * @hooked listing_loop_map_data - 50
 */
do_action( 'rtcl_after_listing_loop_item' );