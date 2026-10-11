<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\ClCar\Customizer\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use radiustheme\ClCar\Customizer\Controls\Customizer_Switch_Control;
use radiustheme\ClCar\Customizer\RDTheme_Customizer;
use Rtcl\Helpers\Functions;
use radiustheme\ClCar\Customizer\Controls\Customizer_Heading_Control;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Listings_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Register Page Controls
		add_action( 'customize_register', [ $this, 'register_listings_controls' ] );
	}

	public function register_listings_controls( $wp_customize ) {
		$group_list = $this->custom_field_group_list();


		// Group Field 1 Visibility
		$wp_customize->add_setting(
			'cfg_field_1_visibility',
			[
				'default'           => $this->defaults['cfg_field_1_visibility'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'cfg_field_1_visibility',
				[
					'label'   => esc_html__( 'Individual Group Field 1 Visibility', 'clcar' ),
					'section' => 'listing_single_section',
				]
		) );

		// Group Field 2 Visibility
		$wp_customize->add_setting(
			'cfg_field_2_visibility',
			[
				'default'           => $this->defaults['cfg_field_2_visibility'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'cfg_field_2_visibility',
				[
					'label'   => esc_html__( 'Individual Group Field 2 Visibility', 'clcar' ),
					'section' => 'listing_single_section',
				]
		) );

		// Group Field 3 Visibility
		$wp_customize->add_setting(
			'cfg_field_3_visibility',
			[
				'default'           => $this->defaults['cfg_field_3_visibility'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'cfg_field_3_visibility',
				[
					'label'   => esc_html__( 'Individual Group Field 3 Visibility', 'clcar' ),
					'section' => 'listing_single_section',
				]
		) );

		// Group Field 4 Visibility
		$wp_customize->add_setting(
			'cfg_field_4_visibility',
			[
				'default'           => $this->defaults['cfg_field_4_visibility'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'cfg_field_4_visibility',
				[
					'label'   => esc_html__( 'Individual Group Field 4 Visibility', 'clcar' ),
					'section' => 'listing_single_section',
				]
		) );
		// Custom Field Group List
		$wp_customize->add_setting(
			'custom_group_individual',
			[
				'default'           => $this->defaults['custom_group_individual'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'custom_group_individual',
			[
				'label'       => esc_html__( 'Individual Custom Field Group 1', 'clcar' ),
				'section'     => 'listing_single_section',
				'description' => esc_html__( 'Select a select group field  to show in listing details page as different section as like demo', 'clcar' ),
				'type'        => 'select',
				'active_callback'   => 'custom_group_individual1_enabled',
				'choices'     => $group_list,
			]
		);
		// Custom Field 2 Group List
		$wp_customize->add_setting(
			'custom_group_individual2',
			[
				'default'           => $this->defaults['custom_group_individual2'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'custom_group_individual2',
			[
				'label'       => esc_html__( 'Individual Custom Field Group 2', 'clcar' ),
				'section'     => 'listing_single_section',
				'description' => esc_html__( 'Select a text group field to show in listing details page as different section as like demo', 'clcar' ),
				'type'        => 'select',
				'active_callback'   => 'custom_group_individual2_enabled',
				'choices'     => $group_list,
			]
		);

		// Custom Field Group List 3
		$wp_customize->add_setting(
			'custom_group_individual3',
			[
				'default'           => $this->defaults['custom_group_individual3'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'custom_group_individual3',
			[
				'label'       => esc_html__( 'Individual Custom Field Group 3', 'clcar' ),
				'section'     => 'listing_single_section',
				'description' => esc_html__( 'Select a text group field to show in listing details page as different section after second group field', 'clcar' ),
				'type'        => 'select',
				'active_callback'   => 'custom_group_individual3_enabled',
				'choices'     => $group_list,
			]
		);
		// Custom Field Group List 4
		$wp_customize->add_setting(
			'custom_group_individual4',
			[
				'default'           => $this->defaults['custom_group_individual4'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'custom_group_individual4',
			[
				'label'       => esc_html__( 'Individual Custom Field Group 4', 'clcar' ),
				'section'     => 'listing_single_section',
				'description' => esc_html__( 'Select a text,checkbox group field to show in listing details page as after third group field', 'clcar' ),
				'type'        => 'select',
				'active_callback'   => 'custom_group_individual4_enabled',
				'choices'     => $group_list,
			]
		);
		//General Feature Title
		$wp_customize->add_setting(
			'individual_group_field_title',
			[
				'default'           => $this->defaults['individual_group_field_title'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'individual_group_field_title',
			[
				'label'       => esc_html__( 'Individual Group Field Title', 'clcar' ),
				'description' => esc_html__( 'You may change Group Field title from here', 'clcar' ),
				'section'     => 'listing_single_section',
				'active_callback'   => 'custom_group_individual1_enabled',
				'type'        => 'text',
			]
		);
		//General Feature Title
		$wp_customize->add_setting(
			'individual_group_field_title2',
			[
				'default'           => $this->defaults['individual_group_field_title2'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'individual_group_field_title2',
			[
				'label'       => esc_html__( 'Individual Group Field Title 2', 'clcar' ),
				'description' => esc_html__( 'You may change Group Field title from here', 'clcar' ),
				'section'     => 'listing_single_section',
				'active_callback'   => 'custom_group_individual2_enabled',
				'type'        => 'text',
			]
		);

		//Technical Feature Title
		$wp_customize->add_setting(
			'individual_group_field_title3',
			[
				'default'           => $this->defaults['individual_group_field_title3'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'individual_group_field_title3',
			[
				'label'       => esc_html__( 'Individual Group Field Title 3', 'clcar' ),
				'description' => esc_html__( 'You may change Group Field title from here', 'clcar' ),
				'section'     => 'listing_single_section',
				'active_callback'   => 'custom_group_individual3_enabled',
				'type'        => 'text',
			]
		);

		//Extra Feature Title
		$wp_customize->add_setting(
			'individual_group_field_title4',
			[
				'default'           => $this->defaults['individual_group_field_title4'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'individual_group_field_title4',
			[
				'label'       => esc_html__( 'Individual Group Field Title 4', 'clcar' ),
				'description' => esc_html__( 'You may change Group Field title from here', 'clcar' ),
				'section'     => 'listing_single_section',
				'active_callback'   => 'custom_group_individual4_enabled',
				'type'        => 'text',
			]
		);
		
		// Show or Hide Listing sidebar
		$wp_customize->add_setting(
			'listing_detail_sidebar',
			[
				'default'           => $this->defaults['listing_detail_sidebar'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'listing_detail_sidebar',
				[
					'label'   => esc_html__( 'Listing Sidebar Visibility', 'clcar' ),
					'section' => 'listing_single_section',
				]
			) 
		);

		// Features & Amenities Visibility
		$wp_customize->add_setting(
			'feature_aminities_show_hide',
			[
				'default'           => $this->defaults['feature_aminities_show_hide'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'feature_aminities_show_hide',
				[
					'label'   => esc_html__( 'Aminites Visibility', 'clcar' ),
					'section' => 'listing_single_section',
				]
		) );


		$wp_customize->add_setting(
			'show_related_listing',
			[
				'default'           => $this->defaults['show_related_listing'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_related_listing',
				[
					'label'       => esc_html__( 'Show Related Listing', 'clcar' ),
					'description' => esc_html__( 'Show or hide related listing from listing details page', 'clcar' ),
					'section'     => 'listing_single_section',
				]
		) );

		$wp_customize->add_setting(
				'show_listing_button_area',
				[
					'default'           => $this->defaults['show_listing_button_area'],
					'transport'         => 'refresh',
					'sanitize_callback' => 'rttheme_switch_sanitization',
				]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_listing_button_area',
				[
					'label'       => esc_html__( 'Show Button Area', 'clcar' ),
					'description' => esc_html__( 'Show or hide button area from single listing page header', 'clcar' ),
					'section'     => 'listing_single_section',
				]
		) );

		// Show Store Info on details page
		$wp_customize->add_setting(
			'show_user_info_on_details',
			[
				'default'           => $this->defaults['show_user_info_on_details'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_radio_sanitization',
			]
		);
		$wp_customize->add_control(
			'show_user_info_on_details',
			[
				'label'       => esc_html__( 'Select Owner/Store info', 'clcar' ),
				'section'     => 'listing_single_section',
				'description' => esc_html__( 'Show Store or Owner info on Details page', 'clcar' ),
				'type'        => 'select',
				'choices'     => [
					'show_listing_info' => esc_html__( 'Show Listing Info', 'clcar' ),
					'show_owner_info' => esc_html__( 'Show Owner Info', 'clcar' ),
					'show_store_info' => esc_html__( 'Show Store Info', 'clcar' ),
				],
			]
		);
		$wp_customize->add_setting(
			'show_owner_store_title',
			[
				'default'           => $this->defaults['show_owner_store_title'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_owner_store_title',
				[
					'label'       => esc_html__( 'Store/Owner Widget Title', 'clcar' ),
					'description' => esc_html__( 'Show or hide Store/Owner Widget Title', 'clcar' ),
					'section'     => 'listing_single_section',
				]
		) );
		$wp_customize->add_setting(
			'show_owner_store_whatsapp',
			[
				'default'           => $this->defaults['show_owner_store_whatsapp'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_owner_store_whatsapp',
				[
					'label'       => esc_html__( 'Owner Widget What\'s App', 'clcar' ),
					'description' => esc_html__( 'Show or hide Owner Widget What\'s App Number', 'clcar' ),
					'section'     => 'listing_single_section',
				]
		) );
		$wp_customize->add_setting(
			'show_owner_store_email',
			[
				'default'           => $this->defaults['show_owner_store_email'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_owner_store_email',
				[
					'label'       => esc_html__( 'Store/Owner Widget Email', 'clcar' ),
					'description' => esc_html__( 'Show or hide Store/Owner Widget Email', 'clcar' ),
					'section'     => 'listing_single_section',
				]
		) );
		$wp_customize->add_setting(
			'show_owner_store_website',
			[
				'default'           => $this->defaults['show_owner_store_website'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_owner_store_website',
				[
					'label'       => esc_html__( 'Store/Owner Widget Website', 'clcar' ),
					'description' => esc_html__( 'Show or hide Store/Owner Widget Website', 'clcar' ),
					'section'     => 'listing_single_section',
				]
		) );
		$wp_customize->add_setting(
			'show_owner_store_address',
			[
				'default'           => $this->defaults['show_owner_store_address'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_owner_store_address',
				[
					'label'       => esc_html__( 'Store Widget Address', 'clcar' ),
					'description' => esc_html__( 'Show or hide Store Widget Address', 'clcar' ),
					'section'     => 'listing_single_section',
				]
		) );
		$wp_customize->add_setting(
			'show_owner_store_rating',
			[
				'default'           => $this->defaults['show_owner_store_rating'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_owner_store_rating',
				[
					'label'       => esc_html__( 'Store Widget Rating', 'clcar' ),
					'description' => esc_html__( 'Show or hide Store Widget Rating', 'clcar' ),
					'section'     => 'listing_single_section',
				]
		) );

		//Listing owner title text
		$wp_customize->add_setting(
			'listing_owner_widget_title',
			[
				'default'           => $this->defaults['listing_owner_widget_title'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'listing_owner_widget_title',
			[
				'label'       => esc_html__( 'Listing owner widget title', 'clcar' ),
				'description' => esc_html__( 'You may change Listing widget title', 'clcar' ),
				'section'     => 'listing_single_section',
				'type'        => 'text',
			]
		);

		// Related Listing Post
		$wp_customize->add_setting('listing_related_heading', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control($wp_customize, 'listing_related_heading', array(
            'label' => __( 'Related Listing Settings', 'clcar' ),
            'section' => 'listing_single_section',
        )));

		$wp_customize->add_setting(
			'show_related_listing_navigation',
			[
				'default'           => $this->defaults['show_related_listing_navigation'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_related_listing_navigation',
				[
					'label'       => esc_html__( 'Slider Navigation', 'clcar' ),
					'description' => esc_html__( 'Show or hide related listing navigation', 'clcar' ),
					'section'     => 'listing_single_section',
				]
		) );
		$wp_customize->add_setting(
			'slider_autoplay',
			[
				'default'           => $this->defaults['slider_autoplay'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'slider_autoplay',
				[
					'label'       => esc_html__( 'Slider Autoplay', 'clcar' ),
					'description' => esc_html__( 'Enable or disable related listing slider autoplay', 'clcar' ),
					'section'     => 'listing_single_section',
				]
		) );
		// Remove Listing Type Prefix
		$wp_customize->add_setting(
			'remove_listing_type_prefix',
			[
				'default'           => $this->defaults['remove_listing_type_prefix'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'remove_listing_type_prefix',
				[
					'label'       => esc_html__( 'Remove Listing Type Prefix (Eg. For)', 'clcar' ),
					'description' => esc_html__( 'Check for hiding listing type prefix (For) from everywhere.', 'clcar' ),
					'section'     => 'listing_common_section',
				]
			) );

		//Features & Amenities Title
		$wp_customize->add_setting(
			'listing_type_prefix_text',
			[
				'default'           => $this->defaults['listing_type_prefix_text'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'listing_type_prefix_text',
			[
				'label'       => esc_html__( 'Listing type prefix text', 'clcar' ),
				'description' => esc_html__( 'You may change Listing type prefix text from here', 'clcar' ),
				'section'     => 'listing_common_section',
				'type'        => 'text',
			]
		);

		

		// listing archive page settings
		
		$wp_customize->add_setting( 'show_listing_custom_fields',
			[
				'default'           => $this->defaults['show_listing_custom_fields'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'show_listing_custom_fields',
			[
				'label'       => esc_html__( 'Show Listing Custom Field', 'clcar' ),
				'description' => esc_html__( 'Show or hide listing custom fields from listing archive page', 'clcar' ),
				'section' => 'listing_archive_section',
			]
		) );

	
		$wp_customize->add_setting( 'listing_arexcerpt_limit',
            array(
                'default' => $this->defaults['listing_arexcerpt_limit'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'listing_arexcerpt_limit',
            array(
                'label' => __( 'Listing Archive Excerpt Limit', 'clcar' ),
                'section' => 'listing_archive_section',
                'type' => 'number',
            )
        );

		$wp_customize->add_setting('map_listing_heading', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control($wp_customize, 'map_listing_heading', array(
            'label' => __( 'Map Listing Archive Page', 'clcar' ),
            'section' => 'listing_archive_section',
        )));

		$wp_customize->add_setting( 'listing_map_per_page', [
			'transport' => 'refresh',
			'default'           => $this->defaults['listing_map_per_page'],
			'sanitize_callback' => 'rttheme_text_sanitization',
		] );

		$wp_customize->add_control( 'listing_map_per_page', [
			'type'    => 'number',
			'section' => 'listing_archive_section', // Add a default or your own section
			'label'   => __( 'Listing Per Page', 'clcar' ), 
		] );


		//Listing Min Price
		$wp_customize->add_setting(
			'listing_widget_min_price',
			[
				'default'           => $this->defaults['listing_widget_min_price'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'listing_widget_min_price',
			[
				'label'       => esc_html__( 'Listing min price ', 'clcar' ),
				'description' => esc_html__( 'This settings for listing map search width', 'clcar' ),
				'section'     => 'listing_common_section',
				'type'        => 'text',
			]
		);
		//Listing Max Price
		$wp_customize->add_setting(
			'listing_widget_max_price',
			[
				'default'           => $this->defaults['listing_widget_max_price'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'listing_widget_max_price',
			[
				'label'       => esc_html__( 'Listing max price ', 'clcar' ),
				'description' => esc_html__( 'This settings for listing map search width', 'clcar' ),
				'section'     => 'listing_common_section',
				'type'        => 'text',
			]
		);

		//search filtering 

		// Custom Field Group List 3
		$wp_customize->add_setting(
			'custom_group_search_field',
			[
				'default'           => $this->defaults['custom_group_search_field'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'custom_group_search_field',
			[
				'label'       => esc_html__( 'Select Custom Group Field For Search Form', 'clcar' ),
				'section'     => 'listing_common_section',
				'description' => esc_html__( 'Select a car branding custom group field for search filtering widget,map page', 'clcar' ),
				'type'        => 'select',
				'choices'     => $group_list,
			]
		);
		//Car Distance Custom Field Group
		$wp_customize->add_setting(
			'car_distance_cf_group_id',
			[
				'default'           => $this->defaults['car_distance_cf_group_id'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'car_distance_cf_group_id',
			[
				'label'       => esc_html__( 'Select Car Distance Custom Group Field For Search Form', 'clcar' ),
				'section'     => 'listing_common_section',
				'description' => esc_html__( 'Select a car distance custom group field for search filtering widget', 'clcar' ),
				'type'        => 'select',
				'choices'     => $group_list,
			]
		);

		$wp_customize->add_setting(
			'car_distance_cf_unit_text',
			[
				'default'           => $this->defaults['car_distance_cf_unit_text'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'car_distance_cf_unit_text',
			[
				'label'       => esc_html__( 'Car Distance Unit', 'clcar' ),
				'section'     => 'listing_common_section',
				'description' => esc_html__( 'Car distance unit for car distance custom field in listing page', 'clcar' ),
				'type'        => 'text',
			]
		);
		
	}

	public function custom_field_group_list() {
		$group_ids = Functions::get_cfg_ids();

		$list = [];
		foreach ( $group_ids as $id ) {
			$list[ $id ] = get_the_title( $id );
		}

		return $list;
	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Listings_Settings();
}
