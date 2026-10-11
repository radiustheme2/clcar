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

use radiustheme\ClCar\Customizer\RDTheme_Customizer;
use radiustheme\ClCar\Customizer\Controls\Customizer_Switch_Control;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Store_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Register Page Controls
		add_action( 'customize_register', [ $this, 'register_listings_controls' ] );
	}

	public function register_listings_controls( $wp_customize ) {
		// Show or Hide Listing sidebar
		$wp_customize->add_setting(
			'show_ad_count',
			[
				'default'           => $this->defaults['show_ad_count'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_ad_count',
				[
					'label'   => esc_html__( 'Show Ad Count', 'clcar' ),
					'section' => 'store_archive_section',
				]
			) 
		);
        $wp_customize->add_setting(
			'show_store_views',
			[
				'default'           => $this->defaults['show_store_views'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_store_views',
				[
					'label'   => esc_html__( 'Show Store Views', 'clcar' ),
					'section' => 'store_archive_section',
				]
			) 
		);
        $wp_customize->add_setting(
			'show_store_ratings',
			[
				'default'           => $this->defaults['show_store_ratings'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_store_ratings',
				[
					'label'   => esc_html__( 'Show Store Ratings', 'clcar' ),
					'section' => 'store_archive_section',
				]
			) 
		);
        $wp_customize->add_setting(
			'show_store_location',
			[
				'default'           => $this->defaults['show_store_location'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_store_location',
				[
					'label'   => esc_html__( 'Show Store Location', 'clcar' ),
					'section' => 'store_archive_section',
				]
			) 
		);
        $wp_customize->add_setting(
			'show_store_excerpt',
			[
				'default'           => $this->defaults['show_store_excerpt'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_store_excerpt',
				[
					'label'   => esc_html__( 'Show Store Excerpt', 'clcar' ),
					'section' => 'store_archive_section',
				]
			) 
		);
        $wp_customize->add_setting(
			'show_store_phone',
			[
				'default'           => $this->defaults['show_store_phone'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_store_phone',
				[
					'label'   => esc_html__( 'Show Store Phone', 'clcar' ),
					'section' => 'store_archive_section',
				]
			) 
		);
        $wp_customize->add_setting(
			'show_store_mail',
			[
				'default'           => $this->defaults['show_store_mail'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_store_mail',
				[
					'label'   => esc_html__( 'Show Store Email', 'clcar' ),
					'section' => 'store_archive_section',
				]
			) 
		);
        $wp_customize->add_setting(
			'show_store_webaddress',
			[
				'default'           => $this->defaults['show_store_webaddress'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_store_webaddress',
				[
					'label'   => esc_html__( 'Show Store Website Link', 'clcar' ),
					'section' => 'store_archive_section',
				]
			) 
		);

		//store single settings

		$wp_customize->add_setting(
			'single_store_slogan',
			[
				'default'           => $this->defaults['single_store_slogan'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'single_store_slogan',
				[
					'label'   => esc_html__( 'Store Slogan Visibility', 'clcar' ),
					'section' => 'store_single_section',
				]
			) 
		);
		$wp_customize->add_setting(
			'single_store_slogan',
			[
				'default'           => $this->defaults['single_store_slogan'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'single_store_slogan',
				[
					'label'   => esc_html__( 'Store Slogan Visibility', 'clcar' ),
					'section' => 'store_single_section',
				]
			) 
		);
		$wp_customize->add_setting(
			'store_owner_contact_form',
			[
				'default'           => $this->defaults['store_owner_contact_form'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'store_owner_contact_form',
				[
					'label'   => esc_html__( 'Store Owner Contact Form Visibility', 'clcar' ),
					'section' => 'store_single_section',
				]
			) 
		);
	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Store_Settings();
}
