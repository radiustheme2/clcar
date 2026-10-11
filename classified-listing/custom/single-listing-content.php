<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;
use radiustheme\ClCar\Helper;
use radiustheme\ClCar\RDTheme;
use radiustheme\ClCar\Listing_Functions;

global $listing;
$custom_field_ids = Functions::get_custom_field_ids( $listing->get_last_child_category_id() );
$video_urls       = [];
if ( ! Functions::is_video_urls_disabled() ) {
	$video_urls = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
	$video_urls = ! empty( $video_urls ) && is_array( $video_urls ) ? $video_urls : [];
}

$hide_listing_map   = get_post_meta( get_the_ID(), 'hide_map', true );

?>
<div class="clcar-listing-single-content-wrapper">

    <!-- Description -->
    <?php if ( $listing->get_the_content() ) { ?>
        <div class="clcar-listing-description single-content">
            <h3 class="listing-entry-inner-title">
                <?php esc_html_e('Description','clcar'); ?>
            </h3>
            <?php $listing->the_content(); ?>
        </div>
    <?php } ?>

    <!-- Custom Fields -->
    <?php
        if ( Functions::isEnableFb() ){
	        $listing->custom_fields();
         } else {
            $cfg_group_1_id = isset( RDTheme::$options['custom_group_individual'] ) ? RDTheme::$options['custom_group_individual'] : 0;
            $cfg_group_2_id = isset( RDTheme::$options['custom_group_individual2'] ) ? RDTheme::$options['custom_group_individual2'] : 0;
            $cfg_group_3_id = isset( RDTheme::$options['custom_group_individual3'] ) ? RDTheme::$options['custom_group_individual3'] : 0;
            $cfg_group_4_id = isset( RDTheme::$options['custom_group_individual4'] ) ? RDTheme::$options['custom_group_individual4'] : 0;

            if ( ! empty( $custom_field_ids ) && RDTheme::$options['cfg_field_1_visibility'] && Listing_Functions::clcar_has_cfg_group_data( $cfg_group_1_id, $listing->get_id() ) ) { ?>
                <div class="clcar-custom-field-section-1 single-content">
                    <?php if ( RDTheme::$options['individual_group_field_title'] ) { ?>
                        <h3 class="listing-entry-inner-title">
                            <?php  echo esc_html( RDTheme::$options['individual_group_field_title'] ); ?>
                        </h3>
                    <?php } ?>
                    <div class="clcar-custom-feild">
                        <?php Helper::get_custom_listing_template( 'cfg-field-1' ); ?>
                    </div>
                </div>
            <?php } ?>
            <!-- Custom Fields -->
            <?php if ( ! empty( $custom_field_ids ) && RDTheme::$options['cfg_field_2_visibility'] && Listing_Functions::clcar_has_cfg_group_data( $cfg_group_2_id, $listing->get_id() ) ) { ?>
                <div class="clcar-custom-field-section-2 single-content">
                    <?php if ( RDTheme::$options['individual_group_field_title2'] ) { ?>
                        <h3 class="listing-entry-inner-title">
                            <?php  echo esc_html( RDTheme::$options['individual_group_field_title2'] ); ?>
                        </h3>
                    <?php } ?>
                    <div class="clcar-custom-feild">
                        <?php Helper::get_custom_listing_template( 'cfg-field-2' ); ?>
                    </div>
                </div>
            <?php } ?>
            <!-- Custom Fields -->
            <?php if ( ! empty( $custom_field_ids ) && RDTheme::$options['cfg_field_3_visibility'] && Listing_Functions::clcar_has_cfg_group_data( $cfg_group_3_id, $listing->get_id() ) ) { ?>
                <div class="clcar-custom-field-section-3 single-content">
                    <?php if ( RDTheme::$options['individual_group_field_title3'] ) { ?>
                        <h3 class="listing-entry-inner-title">
                            <?php  echo esc_html( RDTheme::$options['individual_group_field_title3'] ); ?>
                        </h3>
                    <?php } ?>
                    <div class="clcar-custom-feild">
                        <?php Helper::get_custom_listing_template( 'cfg-field-3' ); ?>
                    </div>
                </div>
            <?php } ?>
            <!-- Custom Fields -->
            <?php if ( ! empty( $custom_field_ids ) && RDTheme::$options['cfg_field_4_visibility'] && Listing_Functions::clcar_has_cfg_group_data( $cfg_group_4_id, $listing->get_id() ) ) { ?>
                <div class="clcar-custom-field-section-4 single-content">
                    <?php if ( RDTheme::$options['individual_group_field_title4'] ) { ?>
                        <h3 class="listing-entry-inner-title">
                            <?php  echo esc_html( RDTheme::$options['individual_group_field_title4'] ); ?>
                        </h3>
                    <?php } ?>
                    <div class="clcar-custom-feild">
                        <?php Helper::get_custom_listing_template( 'cfg-field-4' ); ?>
                    </div>
                </div>
            <?php } ?>

             <!-- Showing without selected custom fields from customizer -->
             <?php if(RDTheme::$options['feature_aminities_show_hide']){ ?>
                <?php do_action( 'rtcl_single_listing_custom_field' ); ?>
            <?php } ?>
    <?php } ?>

    <!-- Video -->
    <?php if ( ! empty( $video_urls ) ): ?>
        <div class="listing-video single-content" id="video">
            <h3 class="listing-entry-inner-title"><?php esc_html_e( 'Listing Video', 'clcar' ); ?></h3>
            <div class="item-img">
                <?php $listing->the_thumbnail( 'rtcl-gallery' ); ?>
                <div class="video-icon">
                    <a class="play-btn popup-youtube" href="<?php echo esc_url( $video_urls[0] ); ?>">
                        <i class="fas fa-play"></i>
                    </a>
                </div>
            </div>
        </div>
	<?php endif; ?>
       
    <!-- Map -->
    <?php if ( method_exists( 'Rtcl\Helpers\Functions', 'has_map' ) && Functions::has_map() && ! $hide_listing_map ){ ?>
        <div class="listing-single-map single-content">
            <div class="listing-map-content">
               <?php do_action( 'rtcl_single_listing_content_end', $listing ); ?>
            </div>
        </div>   
    <?php } ?>

    <?php do_action( 'rtcl_single_listing_review' ); ?>
</div>
