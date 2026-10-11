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
use WP_Customize_Media_Control;
use WP_Customize_Color_Control;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Footer_Apps_Banner_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Add Controls
		add_action( 'customize_register', [ $this, 'register_error_controls' ] );
	}

	public function register_error_controls( $wp_customize ) {
		$wp_customize->add_setting( 'footer_apps_banner_section',
			[
				'default'           => $this->defaults['footer_apps_banner_section'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'footer_apps_banner_section',
			[
				'label'   => __( 'Show Footer Apps Banner ?', 'clcar' ),
				'section' => 'footer_apps_banner_section',
			]
		) );
		$wp_customize->add_setting( 'footer_apps_banner_title',
			[
				'default'           => $this->defaults['footer_apps_banner_title'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_text_field',
			]
		);
		$wp_customize->add_control( 'footer_apps_banner_title',
			[
				'label'   => __( 'Footer Apps Banner Title', 'clcar' ),
				'section' => 'footer_apps_banner_section',
				'type'    => 'text',
			]
		);
		$wp_customize->add_setting( 'footer_apps_banner_text',
			[
				'default'           => $this->defaults['footer_apps_banner_text'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_textarea_field',
			]
		);
		$wp_customize->add_control( 'footer_apps_banner_text',
			[
				'label'   => __( 'Footer Apps Banner Text', 'clcar' ),
				'section' => 'footer_apps_banner_section',
				'type'    => 'textarea',
			]
		);
		$wp_customize->add_setting( 'footer_apps_banner_img',
        array(
            'default' => $this->defaults['footer_apps_banner_img'],
            'transport' => 'refresh',
            'sanitize_callback' => 'absint',
        )
        );
        $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'footer_apps_banner_img',
            array(
                'label' => __( 'Footer Apps Banner Image', 'clcar' ),
                'description' => esc_html__( 'This is the description for the Media Control', 'clcar' ),
                'section' => 'footer_apps_banner_section',
                'mime_type' => 'image',
                'button_labels' => array(
                    'select' => __( 'Select File', 'clcar' ),
                    'change' => __( 'Change File', 'clcar' ),
                    'default' => __( 'Default', 'clcar' ),
                    'remove' => __( 'Remove', 'clcar' ),
                    'placeholder' => __( 'No file selected', 'clcar' ),
                    'frame_title' => __( 'Select File', 'clcar' ),
                    'frame_button' => __( 'Choose File', 'clcar' ),
                ),
            )
        ) );
		$wp_customize->add_setting( 'footer_apps_ios_url',
			[
				'default'           => $this->defaults['footer_apps_ios_url'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'esc_url',
			]
		);
		$wp_customize->add_control( 'footer_apps_ios_url',
			[
				'label'   => __( 'IOS Button Url', 'clcar' ),
				'section' => 'footer_apps_banner_section',
				'type'    => 'url',
			]
		);
		$wp_customize->add_setting( 'footer_apps_google_url',
			[
				'default'           => $this->defaults['footer_apps_google_url'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'esc_url',
			]
		);
		$wp_customize->add_control( 'footer_apps_google_url',
			[
				'label'   => __( 'Google Play Button Url', 'clcar' ),
				'section' => 'footer_apps_banner_section',
				'type'    => 'url',
			]
		);
		$wp_customize->add_setting( 'footer_apps_banner_google_btn',
			[
				'default'           => $this->defaults['footer_apps_banner_google_btn'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'footer_apps_banner_google_btn',
			[
				'label'   => __( 'Show Google Play Button', 'clcar' ),
				'section' => 'footer_apps_banner_section',
			]
		) );
		$wp_customize->add_setting( 'footer_apps_banner_ios_btn',
			[
				'default'           => $this->defaults['footer_apps_banner_ios_btn'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'footer_apps_banner_ios_btn',
			[
				'label'   => __( 'Show IOS Button', 'clcar' ),
				'section' => 'footer_apps_banner_section',
			]
		) );
	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Footer_Apps_Banner_Settings();
}
