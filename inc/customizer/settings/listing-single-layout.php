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

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Listing_Single_Layout_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Register Page Controls
		add_action( 'customize_register', [ $this, 'register_listing_single_layout_controls' ] );
	}

	public function register_listing_single_layout_controls( $wp_customize ) {

		// Top bar
		$wp_customize->add_setting( 'listing_single_top_bar',
			[
				'default'           => $this->defaults['listing_single_top_bar'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_single_top_bar', [
			'type'    => 'select',
			'section' => 'listing_single_layout_section',
			'label'   => esc_html__( 'Top Bar', 'clcar' ),
			'choices' => [
				'default' => esc_html__( 'Default', 'clcar' ),
				'on'      => esc_html__( 'Enable', 'clcar' ),
				'off'     => esc_html__( 'Disable', 'clcar' ),
			],
		] );
		// Header Layout
		$wp_customize->add_setting( 'listing_single_header_style',
			[
				'default'           => $this->defaults['listing_single_header_style'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_single_header_style', [
			'type'    => 'select',
			'section' => 'listing_single_layout_section',
			'label'   => esc_html__( 'Header Layout', 'clcar' ),
			'choices' => [
				'default' => esc_html__( 'Default', 'clcar' ),
				'1'       => esc_html__( 'Layout 1', 'clcar' ),
				'2'       => esc_html__( 'Layout 2', 'clcar' ),
				'3'       => esc_html__( 'Layout 2', 'clcar' ),
				'4'       => esc_html__( 'Layout 2', 'clcar' ),
			],
		] );



		//Header width
		$wp_customize->add_setting( 'listing_single_header_width', [
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'rttheme_text_sanitization',
			'default'           => $this->defaults['listing_single_header_width'],
		] );

		$wp_customize->add_control( 'listing_single_header_width', [
			'type'    => 'select',
			'section' => 'listing_single_layout_section', // Add a default or your own section
			'label'   => __( 'Header Width', 'clcar' ),
			'choices' => [
				'default'   => __( 'Default', 'clcar' ),
				'box-width' => __( 'Box width', 'clcar' ),
				'fullwidth' => __( 'Fullwidth', 'clcar' ),
			],
		] );

		// Transparent Header
		$wp_customize->add_setting( 'listing_single_tr_header',
			[
				'default'           => $this->defaults['listing_single_tr_header'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_single_tr_header', [
			'type'    => 'select',
			'section' => 'listing_single_layout_section',
			'label'   => esc_html__( 'Transparent Header', 'clcar' ),
			'choices' => [
				'default' => esc_html__( 'Default', 'clcar' ),
				'on'      => esc_html__( 'Enable', 'clcar' ),
				'off'     => esc_html__( 'Disable', 'clcar' ),
			],
		] );
		// Breadcrumb
		$wp_customize->add_setting( 'listing_single_breadcrumb',
			[
				'default'           => $this->defaults['listing_single_breadcrumb'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_single_breadcrumb', [
			'type'    => 'select',
			'section' => 'listing_single_layout_section',
			'label'   => esc_html__( 'Breadcrumb', 'clcar' ),
			'choices' => [
				'default' => esc_html__( 'Default', 'clcar' ),
				'on'      => esc_html__( 'Enable', 'clcar' ),
				'off'     => esc_html__( 'Disable', 'clcar' ),
			],
		] );
		// Footer Layout
		$wp_customize->add_setting( 'listing_single_footer_style',
			[
				'default'           => $this->defaults['listing_single_footer_style'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_single_footer_style', [
			'type'    => 'select',
			'section' => 'listing_single_layout_section',
			'label'   => esc_html__( 'Footer Layout', 'clcar' ),
			'choices' => [
				'default' => esc_html__( 'Default', 'clcar' ),
				'1'       => esc_html__( 'Layout 1', 'clcar' ),
				'2'       => esc_html__( 'Layout 2', 'clcar' ),
			],
		] );

		// Padding Top
		$wp_customize->add_setting( 'listing_single_padding_top',
			[
				'default'           => $this->defaults['listing_single_padding_top'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_single_padding_top',
			[
				'label'       => esc_html__( 'Content Padding Top', 'clcar' ),
				'description' => esc_html__( 'Listing Single Content Padding Top(Use px unit after digit)', 'clcar' ),
				'section'     => 'listing_single_layout_section',
				'type'        => 'text',
			]
		);
		// Padding Bottom
		$wp_customize->add_setting( 'listing_single_padding_bottom',
			[
				'default'           => $this->defaults['listing_single_padding_bottom'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_single_padding_bottom',
			[
				'label'       => esc_html__( 'Content Padding Bottom', 'clcar' ),
				'description' => esc_html__( 'Listing Single Content Padding Bottom(Use px unit after digit)', 'clcar' ),
				'section'     => 'listing_single_layout_section',
				'type'        => 'text',
			]
		);
	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Listing_Single_Layout_Settings();
}
