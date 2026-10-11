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
use radiustheme\ClCar\Helper;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Error_Layout_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Register Page Controls
		add_action( 'customize_register', [ $this, 'register_error_layout_controls' ] );
	}

	public function register_error_layout_controls( $wp_customize ) {
		// Top bar
		$wp_customize->add_setting( 'error_top_bar',
			[
				'default'           => $this->defaults['error_top_bar'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'error_top_bar', [
			'type'    => 'select',
			'section' => 'error_layout_section',
			'label'   => esc_html__( 'Top Bar', 'clcar' ),
			'choices' => [
				'default' => esc_html__( 'Default', 'clcar' ),
				'on'      => esc_html__( 'Enable', 'clcar' ),
				'off'     => esc_html__( 'Disable', 'clcar' ),
			],
		] );
		// Header Layout
		$wp_customize->add_setting( 'error_header_style',
			[
				'default'           => $this->defaults['error_header_style'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'error_header_style', [
			'type'    => 'select',
			'section' => 'error_layout_section',
			'label'   => esc_html__( 'Header Layout', 'clcar' ),
			'choices' => Helper::get_clcar_header_list(),
		] );



		//Header width
		$wp_customize->add_setting( 'error_header_width', [
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'rttheme_text_sanitization',
			'default'           => $this->defaults['error_header_width'],
		] );

		$wp_customize->add_control( 'error_header_width', [
			'type'    => 'select',
			'section' => 'error_layout_section', // Add a default or your own section
			'label'   => __( 'Header Width', 'clcar' ),
			'choices' => [
				'default'   => __( 'Default', 'clcar' ),
				'box-width' => __( 'Box width', 'clcar' ),
				'fullwidth' => __( 'Fullwidth', 'clcar' ),
			],
		] );

		// Transparent Header
		$wp_customize->add_setting( 'error_tr_header',
			[
				'default'           => $this->defaults['error_tr_header'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'error_tr_header', [
			'type'    => 'select',
			'section' => 'error_layout_section',
			'label'   => esc_html__( 'Transparent Header', 'clcar' ),
			'choices' => [
				'default' => esc_html__( 'Default', 'clcar' ),
				'on'      => esc_html__( 'Enable', 'clcar' ),
				'off'     => esc_html__( 'Disable', 'clcar' ),
			],
		] );
		// Breadcrumb
		$wp_customize->add_setting( 'error_breadcrumb',
			[
				'default'           => $this->defaults['error_breadcrumb'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'error_breadcrumb', [
			'type'    => 'select',
			'section' => 'error_layout_section',
			'label'   => esc_html__( 'Breadcrumb', 'clcar' ),
			'choices' => [
				'default' => esc_html__( 'Default', 'clcar' ),
				'on'      => esc_html__( 'Enable', 'clcar' ),
				'off'     => esc_html__( 'Disable', 'clcar' ),
			],
		] );
		// Padding Top
		$wp_customize->add_setting( 'error_padding_top',
			[
				'default'           => $this->defaults['error_padding_top'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'error_padding_top',
			[
				'label'       => esc_html__( 'Content Padding Top', 'clcar' ),
				'description' => esc_html__( '404 Page Content Padding Top ', 'clcar' ),
				'section'     => 'error_layout_section',
				'type'        => 'text',
			]
		);
		// Padding Bottom
		$wp_customize->add_setting( 'error_padding_bottom',
			[
				'default'           => $this->defaults['error_padding_bottom'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'error_padding_bottom',
			[
				'label'       => esc_html__( 'Content Padding Bottom', 'clcar' ),
				'description' => esc_html__( '404 Page Content Padding Bottom', 'clcar' ),
				'section'     => 'error_layout_section',
				'type'        => 'text',
			]
		);
		// Footer Layout
		$wp_customize->add_setting( 'error_footer_style',
			[
				'default'           => $this->defaults['error_footer_style'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'error_footer_style', [
			'type'    => 'select',
			'section' => 'error_layout_section',
			'label'   => esc_html__( 'Footer Layout', 'clcar' ),
			'choices' => [
				'default' => esc_html__( 'Default', 'clcar' ),
				'1'       => esc_html__( 'Layout 1', 'clcar' ),
				'2'       => esc_html__( 'Layout 2', 'clcar' ),
			],
		] );
	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Error_Layout_Settings();
}
