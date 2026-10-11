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

use radiustheme\ClCar\Customizer\Controls\Customizer_Image_Radio_Control;
use radiustheme\ClCar\Customizer\RDTheme_Customizer;
use radiustheme\ClCar\Customizer\Controls\Customizer_Switch_Control;
use WP_Customize_Media_Control;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Footer_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Add Controls
		add_action( 'customize_register', [ $this, 'register_footer_controls' ] );
	}

	public function register_footer_controls( $wp_customize ) {
		// Footer Style
		$wp_customize->add_setting( 'footer_style',
			[
				'default'           => $this->defaults['footer_style'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_radio_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Image_Radio_Control( $wp_customize, 'footer_style',
			[
				'label'       => __( 'Footer Layout', 'clcar' ),
				'description' => esc_html__( 'Select the header style', 'clcar' ),
				'section'     => 'footer_section',
				'choices'     => [
					'1' => [
						'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/footer-1.png',
						'name'  => __( 'Style 1', 'clcar' ),
					],
					'2' => [
						'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/footer-2.png',
						'name'  => __( 'Style 2', 'clcar' ),
					],
				],
			]
		) );
		
		// Footer Border
		$wp_customize->add_setting( 'footer_border',
			[
				'default'           => $this->defaults['footer_border'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'footer_border',
			[
				'label'   => __( 'Footer Border Top', 'clcar' ),
				'section' => 'footer_section',
			]
		) );

		// Copyright Area Control
		$wp_customize->add_setting( 'copyright_area',
			[
				'default'           => $this->defaults['copyright_area'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'copyright_area',
			[
				'label'   => __( 'Display Copyright Area', 'clcar' ),
				'section' => 'footer_section',
			]
		) );
		// Copyright Text
		$wp_customize->add_setting( 'copyright_text',
			[
				'default'           => $this->defaults['copyright_text'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_textarea_sanitization',
			]
		);
		$wp_customize->add_control( 'copyright_text',
			[
				'label'           => __( 'Copyright Text', 'clcar' ),
				'section'         => 'footer_section',
				'type'            => 'textarea',
				'active_callback' => 'rttheme_is_copyright_area_enabled',
			]
		);

		//footer payment image

		$wp_customize->add_setting( 'payment_image',
        array(
            'default' => $this->defaults['payment_image'],
            'transport' => 'refresh',
            'sanitize_callback' => 'absint',
        )
        );
        $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'payment_image',
            array(
                'label' => __( 'Payment Image', 'clcar' ),
                'description' => esc_html__( 'This is the description for the Media Control', 'clcar' ),
                'section' => 'footer_section',
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
		
	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Footer_Settings();
}
