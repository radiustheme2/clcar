<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// OOP Format - Add this to your existing class

use Rtcl\Services\FormBuilder\FBHelper;

class CarMakeModelHandler {

    private $table_name;
    private $form_slug;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'rtcl_forms';
        $this->form_slug = 'car-listing'; // Change if your form slug is different

        // Register AJAX actions Make to Model
	    add_action( 'wp_ajax_get_models_by_make', array($this,'ajax_get_models_by_make'));
	    add_action( 'wp_ajax_nopriv_get_models_by_make', array($this,'ajax_get_models_by_make'));

        // Register AJAX actions Model to Year
	    add_action( 'wp_ajax_get_year_by_model', array($this,'ajax_get_year_by_model'));
	    add_action( 'wp_ajax_nopriv_get_year_by_model', array($this,'ajax_get_year_by_model'));

        // Enqueue scripts
	    add_action( 'wp_footer', array($this,'enqueue_make_model_scripts'));
    }


	// Function to get models based on selected make from database
	public function get_models_by_make( $selected_make = '' ) {
		global $wpdb;

		// If no make selected, return empty array
		if (empty($selected_make)) {
			return array();
		}

		// Get the form data from wp_rtcl_forms table
		$table_name = $wpdb->prefix . 'rtcl_forms';

		// Get the model field data
		$form_data = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT fields FROM $table_name WHERE slug = %s",
				'car-listing' // Your form slug
			),
			ARRAY_A
		);

		if (!$form_data || empty($form_data['fields'])) {
			return array();
		}

		// Decode the JSON fields data
		$fields = json_decode($form_data['fields'], true);

		if (!is_array($fields)) {
			return array();
		}

		// Find the model field (uuid: mdh2qqt0)
		$model_field = null;
		foreach ($fields as $field) {
			if (isset($field['name']) && $field['name'] === 'select_model') {
				$model_field = $field;
				break;
			}
		}

		if (!$model_field || !isset($model_field['options'])) {
			return array();
		}

		// Filter models based on selected make
		$filtered_models = array();
		foreach ($model_field['options'] as $option) {
			if (isset($option['depends_on']) && is_array($option['depends_on'])) {
				// Check if selected make is in depends_on array
				if (in_array($selected_make, $option['depends_on'])) {
					$filtered_models[] = array(
						'label' => $option['label'],
						'value' => $option['value']
					);
				}
			}
		}

		return $filtered_models;
	}

	public function get_year_by_model( $selected_model = '' ) {
		global $wpdb;

		// Get the form data from wp_rtcl_forms table
		$table_name = $wpdb->prefix . 'rtcl_forms';

		// Get the model field data
		$form_data = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT fields FROM $table_name WHERE slug = %s",
				'car-listing' // Your form slug
			),
			ARRAY_A
		);

		if (!$form_data || empty($form_data['fields'])) {
			return array();
		}

		// Decode the JSON fields data
		$fields = json_decode($form_data['fields'], true);

		if (!is_array($fields)) {
			return array();
		}

		// Find the model field (uuid: mdh2qqt0)
		$model_field = null;
		foreach ($fields as $field) {
			if (isset($field['name']) && $field['name'] === 'build_year') {
				$model_field = $field;
				break;
			}
		}

		if (!$model_field || !isset($model_field['options'])) {
			return array();
		}

		// Filter years based on selected model, keeping all years as fallback
		$all_years      = array();
		$filtered_years = array();
		foreach ($model_field['options'] as $option) {
			if ( ! isset( $option['value'] ) || '' === $option['value'] ) {
				continue;
			}
			$year = array(
				'label' => $option['label'],
				'value' => $option['value']
			);
			$all_years[] = $year;
			if ( $selected_model && isset($option['depends_on']) && is_array($option['depends_on']) && in_array($selected_model, $option['depends_on']) ) {
				$filtered_years[] = $year;
			}
		}

		// No model selected or no year linked to this model: show all years
		return $filtered_years ? $filtered_years : $all_years;
	}

	// AJAX handler for getting models
	function ajax_get_models_by_make() {

		$make = isset($_POST['make']) ? sanitize_text_field($_POST['make']) : '';

		$models = $this->get_models_by_make( $make );

		wp_send_json_success($models);
	}

    // AJAX handler for getting years
	function ajax_get_year_by_model() {

		$model = isset($_POST['model']) ? sanitize_text_field($_POST['model']) : '';

		$years = $this->get_year_by_model( $model );

		wp_send_json_success($years);
	}

	/**
	 * Enqueue scripts for AJAX handling
	 */
	public function enqueue_make_model_scripts() {
		$select_model_text = esc_js(__('Select Model', 'clcar-core'));
		$select_year_text = esc_js(__('Select Year', 'clcar-core'));
		?>
        <script>
            jQuery(document).ready(function($) {
                var select_model_text = '<?php echo esc_html( $select_model_text ); ?>';
                var select_year_text  = '<?php echo esc_html( $select_year_text ); ?>';
                var ajax_url          = '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';

                // Load years for a model; all years when no model or no linked years
                function loadYears(selectedModel) {
                    var yearSelect = $('select[name="cf_build_year"]');
                    if (!yearSelect.length) {
                        return;
                    }

                    // Reset Year
                    yearSelect.html('<option value="">' + select_year_text + '</option>');

                    $.ajax({
                        url: ajax_url,
                        type: 'POST',
                        data: {
                            action: 'get_year_by_model',
                            model: selectedModel || ''
                        },
                        success: function (response) {
                            if (response.success && response.data.length > 0) {
                                $.each(response.data, function (index, year) {
                                    yearSelect.append($('<option></option>').attr('value', year.value).text(year.label));
                                });
                            }
                        }
                    });
                }

                // Make → Model
                $('select[name="cf_select_make"]').on('change', function () {
                    var selectedMake = $(this).val();
                    var modelSelect = $('select[name="cf_select_model"]');

                    // Reset Model and Year
                    modelSelect.html('<option value="">' + select_model_text + '</option>');
                    loadYears('');

                    if (selectedMake) {
                        $.ajax({
                            url: ajax_url,
                            type: 'POST',
                            data: {
                                action: 'get_models_by_make',
                                make: selectedMake
                            },
                            success: function (response) {
                                if (response.success && response.data.length > 0) {
                                    $.each(response.data, function (index, model) {
                                        modelSelect.append($('<option></option>').attr('value', model.value).text(model.label));
                                    });
                                }
                            }
                        });
                    }
                });

                // Model → Year
                $('select[name="cf_select_model"]').on('change', function () {
                    loadYears($(this).val());
                });

                // Year is usable before any Model is picked (older ClCar Core outputs these as disabled)
                $('select[name="cf_select_model"], select[name="cf_build_year"]').prop('disabled', false);
                loadYears('');
            });
        </script>
		<?php
	}
}

// Initialize the class
new CarMakeModelHandler();