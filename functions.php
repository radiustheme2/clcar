<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! isset( $content_width ) ) {
	$content_width = 1240;
}

define( 'CLCAR_BASE_URL',    get_template_directory_uri(). '/' );
define( 'CLCAR_ASSETS_URL',  CLCAR_BASE_URL . 'assets/' );
define( 'CLCAR_CSS_URL',     CLCAR_ASSETS_URL . 'css/' );
define( 'CLCAR_JS_URL',      CLCAR_ASSETS_URL . 'js/' );

class ClCar_Main {

	public $theme = 'clcar';
	public $action = 'clcar_theme_init';

	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'load_textdomain' ] );
		$this->includes();
	}

	public function load_textdomain() {
		load_theme_textdomain( $this->theme, get_template_directory() . '/languages' );
	}

	public function includes() {
		require_once get_template_directory() . '/inc/constants.php';
		require_once get_template_directory() . '/inc/helper.php';
		require_once get_template_directory() . '/inc/includes.php';

		do_action( $this->action );
	}
}

new ClCar_Main;




 