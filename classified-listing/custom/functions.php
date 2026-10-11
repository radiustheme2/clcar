<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.5
 */

namespace radiustheme\ClCar;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Models\Listing;
use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Pagination;
use Rtcl\Models\RtclCFGField;
use Rtcl\Controllers\Hooks\Filters;
use Rtcl\Controllers\Hooks\TemplateHooks;
use Rtcl\Helpers\Functions as RtclFunctions;
use RtclStore\Controllers\Hooks\TemplateHooks as StoreHooks;
use RtclPro\Controllers\Hooks\TemplateHooks as NewTemplateHooks;

class Listing_Functions {

	protected static $instance = null;

	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'theme_support' ] );
		add_action( 'init', [ $this, 'classifiedads_rtcl_filter' ] );
		add_action( 'init', [ $this, 'classifiedads_rtcl_action' ] );
		add_action( 'template_redirect', [ $this, 'disable_lazy_load' ] );
		add_action( 'the_content', [ $this, 'rt_set_post_view_count' ], 9999 );

		add_action( 'clcar_listing_grid_search_filer', [ $this, 'listing_map_filter' ] );
		add_action( 'rtcl_shortcode_before_listings_loop_start', [ $this, 'listing_map_filter' ] );
		add_action( 'wp_ajax_delete_listing_logo_attachment', [ $this, 'delete_listing_logo_attachment' ] );

		add_filter( 'rtcl_store_the_excerpt', [ $this, 'rtcl_store_the_excerpt' ] );
		add_filter( 'rtcl_format_price_range', [ $this, 'rtcl_format_price_range' ], 10, 3 );
		add_filter( 'rtcl_type_prefix', [ $this, 'rtcl_type_prefix' ] );

		add_filter( 'rtcl_general_settings_options', [ $this, 'rtcl_general_settings_options' ] );
		add_action( 'rtcl_listing_form_after_save_or_update', [ $this, 'listing_form_save' ], 12, 5 ); // save extra listing form fields

		add_filter( 'rtcl_get_icon_list', [ $this, 'rtcl_get_icon_list_modify' ] );
		add_filter( 'rtcl_get_icon_class_list', [ $this, 'rtcl_get_icon_list_modify' ] );

		// store enable pagination by force
		add_action( 'template_redirect', [ $this,'store_enable_pagination' ], 0 );

		add_action( 'rtcl_category_add_form_fields', [ $this,'clcar_colorpicker_field_add_new_category' ] );
		add_action( 'rtcl_category_edit_form_fields', [ $this,'clcar_colorpicker_field_edit_category' ] );

		add_action( 'created_rtcl_category', [ $this,'clcar_save_termmeta' ] );
		add_action( 'edited_rtcl_category', [ $this,'clcar_save_termmeta' ] );

		// New Settings
		add_filter( 'rtcl_single_listing_settings_options', [ $this, 'listing_details_layout_settings' ] );
		add_action( 'rt_listing_galley', [ __CLASS__, 'listing_details_gallery' ], 10 );
		add_filter( 'rtcl_membership_settings_options', [ $this, 'listing_store_archive_visibility_settings' ] );

		add_action( 'wp', [ $this, 'migrate_clcar_logo_data' ] );
		add_action( 'admin_init', [ $this, 'migrate_clcar_logo_data' ] );

		add_filter( 'rtcl_archive_listing_settings_options', [ $this, 'rtcl_listing_style_option' ] );

		add_filter(
			'rtcl_related_slider_options',
			function ( $options ) {
				$options['breakpoints'][1200] = [
					'slidesPerView' => 4,
				];
				return $options;
			}
		);

        add_filter( 'rtcl_force_classic_style', '__return_true' );
    }

	function rtcl_general_settings_options( $options ) {

		unset( $options['load_bootstrap'] );

		return $options;
	}

	function rtcl_type_prefix( $prefix ) {
		if ( RDTheme::$options['remove_listing_type_prefix'] ) {
			return null;
		}
		if ( RDTheme::$options['listing_type_prefix_text'] ) {
			return esc_html( RDTheme::$options['listing_type_prefix_text'] );
		}

		return $prefix;
	}

	function rtcl_format_price_range( $price, $from, $to ) {
		$price = sprintf(
			_x(
				'<div class="rtcl-price-range"><span class="price-from">%1$s</span> <span class="dash">&ndash;</span> <span class="price-to">%2$s</span></div>',
				'Price range: from-to',
				'clcar'
			),
			is_numeric( $from ) ? Functions::price( $from ) : $from,
			is_numeric( $to ) ? Functions::price( $to ) : $to
		);

		return $price;
	}

	function disable_lazy_load() {
		$is_listing_archive = Functions::is_listings() || Functions::is_listing_taxonomy();
		if ( $is_listing_archive || is_singular( 'rtcl_listing' ) ) {
			add_filter( 'wp_lazy_loading_enabled', '__return_false' );
		}
	}

	public function rtcl_store_the_excerpt( $excerpt ) {
		$excerpt = wp_trim_words( $excerpt, 20, '' );

		return $excerpt;
	}

	public function rt_set_post_view_count( $content ) {
		// Set store View Count
		if ( is_singular() ) {
			$rt_store_views_key = 'rt_post_views_count';
			$store_view_count   = get_post_meta( get_the_ID(), $rt_store_views_key, true );
			if ( '' == $store_view_count ) {
				$store_view_count = 0;
				delete_post_meta( get_the_ID(), $rt_store_views_key );
				add_post_meta( get_the_ID(), $rt_store_views_key, $store_view_count );
			} else {
				$store_view_count++;
				update_post_meta( get_the_ID(), $rt_store_views_key, $store_view_count );
			}
		}

		return $content;
	}

	public static function rt_get_post_view_count( $storeID ) {
		// Get store View Count
		$rt_store_views_key = 'rt_post_views_count';
		$store_view_count   = get_post_meta( $storeID, $rt_store_views_key, true );

		return $store_view_count;
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function theme_support() {
		add_theme_support( 'rtcl' );
	}

	public function classifiedads_rtcl_filter() {

		add_filter(
			'rtcl_loop_listing_per_page',
			function ( $per_page ) {
				$post_per_pare = Functions::get_option_item( 'rtcl_archive_listing_settings', 'listing_top_per_page', 2 );
				if ( isset( $_GET['layout'] ) && 'fullwidth' == $_GET['layout'] ) {
					$per_page  = $per_page < 10 ? 9 : 12;
					$per_page -= absint( $post_per_pare );
				}

				return $per_page;
			}
		);

		// modify related slider options

		add_filter(
			'rtcl_related_slider_options',
			function ( $options ) {
				$options['autoplay']                            = RDTheme::$options['slider_autoplay'];
				$options['breakpoints']['768']['slidesPerView'] = 2;
				$options['breakpoints']['992']['slidesPerView'] = 3;

				return $options;
			}
		);

		// Override Related Listing Item Number
		add_filter(
			'rtcl_related_slider_options',
			function ( $options ) {
				$options['items']  = 3;
				$options['margin'] = 30;

				return $options;
			}
		);
		// Modify Add to favorite text
		add_filter(
			'rtcl_text_add_to_favourite',
			function ( $text ) {
				$text = '';

				return $text;
			}
		);
		// Modify remove from favorite text
		add_filter(
			'rtcl_text_remove_from_favourite',
			function ( $text ) {
				$text = '';

				return $text;
			}
		);
		// Remove report abuse text
		add_filter(
			'rtcl_text_report_abuse',
			function ( $text ) {
				return '';
			}
		);

		add_filter(
			'get_the_archive_title',
			function ( $title ) {
				if ( is_post_type_archive( 'rtcl_listing' ) ) {
					$title = esc_html__( 'Our Latest Listings', 'clcar' );
				}
				if ( is_post_type_archive( 'store' ) ) {
					$title = esc_html__( 'Store', 'clcar' );
				}
				return $title;
			}
		);

		// Override page template
		add_filter( 'template_include', [ $this, 'template_include' ] );

		// Override Store Ad count
		add_filter(
			'rtcl_store_get_ad_count_html',
			function ( $content, $obj, $count ) {
				$count_string = $count <= 0
					? apply_filters( 'clcar_store_no_ad_text', __( 'No Listing', 'clcar' ), $this, $count )
					: sprintf(
						_n( '%s Listing', '%s Listings', $count, 'clcar' ),
						number_format_i18n( $count )
					);
				$content      = sprintf( '<span class="ads-count">%s</span>', $count_string );

				return $content;
			},
			10,
			3
		);

		// Change Unit Text
		add_filter(
			'rtcl_get_price_unit_list',
			function ( $unitList ) {
				$unitList['year']['short']  = esc_html__( 'yr', 'clcar' );
				$unitList['month']['short'] = esc_html__( 'mo', 'clcar' );
				$unitList['week']['short']  = esc_html__( 'week', 'clcar' );
				$unitList['day']['short']   = esc_html__( 'day', 'clcar' );
				$unitList['hour']['short']  = esc_html__( 'hr', 'clcar' );
				return $unitList;
			}
		);

		// Seller icon change

		add_filter(
			'rtcl_sv_verified_icon_class',
			function ( $class ) {
				$class = 'fa-solid fa-certificate';
				return $class;
			}
		);

		add_filter( 'rtcl_bootstrap_dequeue', '__return_false' );
	}

	public function rtcl_get_icon_list_modify( $icons_lists ) {
		$new_icons = [
			' icon-truck',
			' icon-car',
			' icon-cc',
			' icon-distance',
			' icon-location',
			' icon-location2',
			' icon-wheel',
			' icon-hdd',
			' icon-taxi',
			' icon-grid',
			' icon-list',
			' icon-params',
			' icon-tag-1',
			' icon-tags-1',
			' icon-sedan',
			' icon-crossover',
			' icon-off-road',
			' icon-sports',
			' icon-covertible',
			' icon-mini-truc',
			' icon-van',
			' icon-minibus',
			' icon-hatchback',
			' icon-speed-meter',
			' icon-transmission_type',
			' icon-circle-checked',
			' icon-fuel-type',
			' icon-fuel-type2',
			' icon-speed-meter-2',
			' icon-drive-type',
			' icon-body-type',
			' icon-seats',
			' icon-doors',
			' icon-engine-type',
			' icon-fuel-type',
			' icon-transmission',
			' icon-car-color',
		];
		return array_merge( $icons_lists, $new_icons );
	}

	public function listing_map_filter( $atts ) {
		$loc_text = esc_attr__( 'Select Location', 'clcar' );
		$cat_text = esc_attr__( 'Select Category', 'clcar' );
		$typ_text = esc_attr__( 'Select Type', 'clcar' );
		$class    = is_archive() ? 'listing-map-filter' : 'page-filter';
		global $wp;
		?>
		<div class="listing-grid-box <?php echo esc_attr( $class ); ?>">
			<form action="<?php echo esc_url( home_url( $wp->request ) ); ?>" class="advance-search-form map-search-form rtcl-widget-search-form is-preloader">
				<div class="search-box">
					<div class="search-item search-keyword">
						<div class="input-group">
							<input type="text" data-type="listing" name="q" class="rtcl-autocomplete form-control"
								   placeholder="<?php esc_attr_e( 'Enter Keyword here ...', 'clcar' ); ?>"
								   value="
								   <?php
									if ( isset( $_GET['q'] ) ) {
										echo esc_attr( $_GET['q'] );
									}
									?>
								   "/>
						</div>
					</div>
					<?php
						$filter_group_id = isset( RDTheme::$options['custom_group_search_field'] ) ? RDTheme::$options['custom_group_search_field'] : 0;
					if ( $filter_group_id ) {
						$args      = [
							'is_searchable' => true,
							'group_ids'     => [ $filter_group_id ],
						];
						$fields_id = Functions::get_cf_ids( $args );
						$html      = '';
						foreach ( $fields_id as $field ) {
							$html .= self::get_advanced_search_ajax_filter_field_html( $field );
						}
						printf( '%s', $html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is escaped in the template generating $html.
					}
					?>
					<div class="search-item search-select rtin-category">
						<?php
						wp_dropdown_categories(
							[
								'show_option_none'  => $cat_text,
								'option_none_value' => '',
								'taxonomy'          => rtcl()->category,
								'name'              => 'rtcl_category',
								'id'                => 'rtcl-category-search-' . wp_rand(),
								'class'             => 'select2 rtcl-category-search',
								'selected'          => get_query_var( 'rtcl_category' ),
								'hierarchical'      => true,
								'value_field'       => 'slug',
								'depth'             => Functions::get_category_depth_limit(),
								'show_count'        => false,
								'hide_empty'        => false,
							]
						);
						?>
					</div>

					<?php if ( method_exists( 'Rtcl\Helpers\Functions', 'location_type' ) && 'local' === Functions::location_type() ) : ?>
						<div class="search-item search-select rtin-location">
							<?php
							wp_dropdown_categories(
								[
									'show_option_none'  => $loc_text,
									'option_none_value' => '',
									'taxonomy'          => rtcl()->location,
									'name'              => 'rtcl_location',
									'id'                => 'rtcl-location-search-' . wp_rand(),
									'class'             => 'select2 rtcl-location-search',
									'selected'          => get_query_var( 'rtcl_location' ),
									'hierarchical'      => true,
									'value_field'       => 'slug',
									'depth'             => Functions::get_location_depth_limit(),
									'show_count'        => false,
									'hide_empty'        => false,
								]
							);
							?>
						</div>
					<?php endif; ?>
				</div>
				<div class="search-box-2">
					<div class="search-item price-item-box">
						<div class="price-range">
							<label><?php esc_html_e( 'Price:', 'clcar' ); ?></label>
							<?php
							$currency  = Functions::get_currency_symbol();
							$data_form = '';
							$data_to   = '';
							if ( isset( $_GET['filters']['price']['min'] ) ) {
								$data_form .= sprintf( 'data-from=%s', absint( $_GET['filters']['price']['min'] ) );
							}
							if ( isset( $_GET['filters']['price']['max'] ) && ! empty( $_GET['filters']['price']['max'] ) ) {
								$data_to .= sprintf( 'data-to=%s', absint( $_GET['filters']['price']['max'] ) );
							}
							$min_price = RDTheme::$options['listing_widget_min_price'];
							$max_price = RDTheme::$options['listing_widget_max_price'];
							?>
							<input type="number"
								   class="ion-rangeslider" <?php echo esc_attr( $data_form ); ?> <?php echo esc_attr( $data_form ); ?> <?php echo esc_attr( $data_to ); ?>
								   data-min="<?php echo isset( $min_price ) && ! empty( $min_price ) ? esc_html( $min_price ) : 0; ?>"
								   data-max="<?php echo isset( $max_price ) && ! empty( $max_price ) ? esc_html( $max_price ) : 80000; ?>"
								   data-prefix="<?php echo esc_html( $currency ); ?>"/>
							<input type="hidden" class="min-volumn" name="filters[price][min]"
								   value="
								   <?php
									if ( isset( $_GET['filters']['price']['min'] ) ) {
										echo absint( $_GET['filters']['price']['min'] );
									}
									?>
								   ">
							<input type="hidden" class="max-volumn" name="filters[price][max]"
								   value="
								   <?php
									if ( isset( $_GET['filters']['price']['max'] ) ) {
										echo absint( $_GET['filters']['price']['max'] );
									}
									?>
								   ">
						</div>
					</div>

					<div class="search-item search-button">
						<button class="rtcl-item-visible-btn">
							<i class="icon-slider"></i>
						</button>
					</div>

					<div class="search-item search-btn">
						<button type="submit" class="submit-btn">
							<?php esc_html_e( 'Search', 'clcar' ); ?>
							<i class="icon-search3"></i>
						</button>
					</div>
				</div>
				<div class="form-cf-items expanded-wrap">
					<div class="cf-inner">
						<?php
							$args      = [
								'is_searchable'     => true,
								'exclude_group_ids' => [ $filter_group_id ],
							];
							$fields_id = Functions::get_cf_ids( $args );

							$html = '';
							foreach ( $fields_id as $field ) {
								$html .= self::get_advanced_search_field_html( $field );
							}
							printf( '%s', $html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is escaped in the template generating $html.
							?>
					</div>
				</div>
			</form>
		</div>
		<?php
	}

	public function classifiedads_rtcl_action() {

		remove_action( 'rtcl_edit_account_form_end', [ TemplateHooks::class, 'edit_account_form_submit_button' ], 10 );
		add_action( 'rtcl_edit_account_form_end', [ $this, 'edit_account_form_submit_button' ], 10 );
		remove_action( 'rtcl_after_listing_loop_item', [ NewTemplateHooks::class, 'sold_out_banner' ] );
		remove_action( 'rtcl_before_main_content', [ TemplateHooks::class, 'breadcrumb' ], 6 );
		remove_action( 'rtcl_before_main_content', [ TemplateHooks::class, 'output_main_wrapper_start' ], 8 );
		remove_action( 'rtcl_after_main_content', [ TemplateHooks::class, 'output_content_wrapper_end' ], 15 );
		remove_action( 'rtcl_sidebar', [ TemplateHooks::class, 'output_main_wrapper_end' ], 15 );

		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_listing_title' ], 20 );
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_badges' ], 30 );
		remove_action( 'rtcl_listing_loop_item', [ NewTemplateHooks::class, 'loop_item_listable_fields' ], 40 );
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_excerpt' ], 70 );
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'listing_price' ], 80 );
		remove_action( 'rtcl_listing_form_end', [ TemplateHooks::class, 'listing_form_submit_button' ], 50 );
		// add_action( 'rtcl_listing_loop_item', [$this, 'loop_item_listing_type' ], 10 );
		add_action( 'rtcl_listing_loop_item', [ $this, 'loop_item_listing_category' ], 15 );
		add_action( 'rtcl_listing_loop_item', [ $this, 'loop_item_listing_price' ], 20 );
		add_action( 'rtcl_listing_loop_item', [ $this, 'loop_item_title' ], 30 );
		add_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_badges' ], 40 );
		add_action( 'rtcl_listing_loop_item', [ $this, 'loop_item_excerpt' ], 50 );
		add_action( 'rtcl_listing_loop_item', [ NewTemplateHooks::class, 'loop_item_listable_fields' ], 60 );

		/**single listing hooks */

		remove_action( 'rtcl_single_listing_content', [ TemplateHooks::class, 'add_single_listing_title' ], 5 );
		remove_action( 'rtcl_single_listing_content', [ TemplateHooks::class, 'add_single_listing_meta' ], 10 );
		remove_action( 'rtcl_single_listing_inner_sidebar', [ TemplateHooks::class,'add_single_listing_inner_sidebar_custom_field' ], 10 );
		remove_action( 'rtcl_single_listing_inner_sidebar', [ TemplateHooks::class, 'add_single_listing_inner_sidebar_action' ], 20 );

		remove_action( 'rtcl_listing_seller_information', [ TemplateHooks::class, 'seller_location' ], 10 );
		remove_action( 'rtcl_listing_seller_information', [ TemplateHooks::class, 'seller_phone_whatsapp_number' ], 20 );
		remove_action( 'rtcl_listing_seller_information', [ TemplateHooks::class, 'seller_email' ], 30 );
		remove_action( 'rtcl_listing_seller_information', [ TemplateHooks::class, 'seller_website' ], 50 );

		add_action( 'rtcl_single_listing_custom_field', [ __CLASS__,'add_single_listing_custom_field' ] );

		// Store Archive Hooks
		remove_action( 'rtcl_before_store_loop_item', [ StoreHooks::class, 'open_store_link' ], 10 );
		remove_action( 'rtcl_after_store_loop_item', [ StoreHooks::class, 'close_store_link' ], 5 );
		remove_action( 'rtcl_store_loop_item', [ StoreHooks::class, 'store_meta' ], 20 );
		remove_action( 'rtcl_store_loop_item', [ StoreHooks::class, 'loop_item_store_title' ], 10 );

		add_action( 'rtcl_store_loop_item', [ __CLASS__, 'store_meta' ], 20 );

		add_filter( 'rtcl_related_slider_options', [ __CLASS__, 'clcar_custom_related_slider_options' ] );
	}

	public static function loop_item_listing_price() {
		Functions::get_template( 'listing/loop/price' );
	}

	public static function loop_item_title() {
		global $listing;
		echo '<h3 class="' . esc_attr( apply_filters( 'rtcl_listing_loop_title_classes', 'listing-title rtcl-listing-title' ) ) . '"><a href="' . esc_url( $listing->get_the_permalink() ) . '">' . wp_kses_post( $listing->get_the_title() ) . '</a></h3>';
	}

	public static function loop_item_excerpt() {
		global $listing;
		if ( $listing->can_show_excerpt() ) {
			$length  = RDTheme::$options['listing_arexcerpt_limit'];
			$excerpt = Helper::clcar_excerpt( $length );
			echo "<div class='listing-excerpt'>";
			echo wp_kses_post( $excerpt );
			echo '</div>';
		}
	}

	public static function loop_item_listing_type() {
		global $listing;
		$listing_type = self::get_listing_type( $listing );
		if ( ! empty( $listing_type ) && $listing->can_show_ad_type() ) :
			?>
			<span class="listing-add-type">
				<?php echo sprintf( '%s %s', esc_html( apply_filters( 'rtcl_type_prefix', __( 'For', 'clcar' ) ) ), esc_html( $listing_type['label'] ) ); ?>
			</span>
			<?php
		endif;
	}

	public static function loop_item_listing_category() {
		global $listing;
		if ( $listing->has_category() && $listing->can_show_category() && ! Functions::is_listing() ) :
			$category = $listing->get_categories();
			$category = end( $category );
			?>
			<div class="listing-category">
				<a href="<?php echo esc_url( get_term_link( $category->term_id, $category->taxonomy ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
			</div>
			<?php
		endif;
	}

	public function edit_account_form_submit_button() {
		?>
		<div class="form-group rtcl-form-group clcar-edit-account-submit">
			<input type="submit" name="submit" class="btn btn-primary"
				   value="<?php esc_html_e( 'Update Account', 'clcar' ); ?>"/>
		</div>
		<?php
	}

	public function template_include( $template ) {
		if ( Functions::is_account_page() ) {
			$new_template = Helper::get_custom_listing_template( 'listing-account', false );
			$new_template = locate_template( [ $new_template ] );

			return $new_template;
		}
		if ( Functions::is_listing_form_page() ) {
			$new_template = Helper::get_custom_listing_template( 'listing-form', false );
			$new_template = locate_template( [ $new_template ] );

			return $new_template;
		}

		return $template;
	}

	public static function get_listing_type( $listing ) {
		$listing_types = Functions::get_listing_types();
		$listing_types = empty( $listing_types ) ? [] : $listing_types;

		$type = $listing->get_ad_type();

		if ( $type && ! empty( $listing_types[ $type ] ) ) {
			$result = [
				'label' => $listing_types[ $type ],
				'icon'  => 'fa-tags',
			];
		} else {
			$result = false;
		}

		return $result;
	}

	public static function get_advanced_search_field_html( $field_id ) {
		$field      = new RtclCFGField( $field_id );
		$field_html = null;

		if ( $field_id && $field ) {
			$id = "rtcl_{$field->getType()}_{$field->getFieldId()}";

			switch ( $field->getType() ) {
				case 'text':
					$field_html = sprintf(
						'<div class="search-item"><input type="text" class="rtcl-text form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" placeholder="%s" value="" /></div>',
						$id,
						absint( $field->getFieldId() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
				case 'textarea':
					$field_html = sprintf(
						'<textarea class="rtcl-textarea form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" rows="%d" placeholder="%s"></textarea>',
						$id,
						absint( $field->getFieldId() ),
						absint( $field->getRows() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
				case 'select':
					$options      = $field->getOptions();
					$choices      = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$options_html = '<option value="">' . esc_html( $field->getLabel() ) . '</option>';
					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$_attr = '';
							if ( isset( $_GET['filters'][ '_field_' . $field->getFieldId() ] ) && $_GET['filters'][ '_field_' . $field->getFieldId() ] == $choice ) {
								$_attr .= ' selected';
							}
							$options_html .= sprintf( '<option value="%s"%s>%s</option>', $key, $_attr, $choice );
						}
						$field_html = sprintf(
							'<div class="search-item search-select"><select name="filters[_field_%d]" id="%s" data-placeholder="%s" class="select2">%s</select></div>',
							absint( $field->getFieldId() ),
							$id,
							$field->getLabel(),
							$options_html
						);
						break;
					}
				case 'checkbox':
					$options       = $field->getOptions();
					$value         = isset( $_GET['filters'][ '_field_' . $field->getFieldId() ] ) ? $_GET['filters'][ '_field_' . $field->getFieldId() ] : [];
					$choices       = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$check_options = null;
					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$_attr = '';
							if ( in_array( $key, $value ) ) {
								$_attr .= ' checked="checked"';
							}
							$check_options .= sprintf(
								'<div class="form-check"><input class="form-check-input" id="%s" type="checkbox" name="filters[_field_%d][]" value="%s"%s><label class="form-check-label" for="%s">%s</label></div>',
								$id . $key,
								absint( $field->getFieldId() ),
								$key,
								$_attr,
								$id . $key,
								$choice
							);
						}
					}
					$field_html = sprintf( '<div class="search-item checkbox-wrapper">%s</div>', $check_options );
					break;
				case 'radio':
					$options       = $field->getOptions();
					$choices       = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$check_options = null;
					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$check_options .= sprintf(
								'<div class="form-check"><input class="form-check-input" id="%s" type="radio" name="filters[_field_%d]" value="%s"><label class="form-check-label" for="%s">%s</label></div>',
								$id . $key,
								absint( $field->getFieldId() ),
								$key,
								$id . $key,
								$choice
							);
						}
					}
					$field_html = sprintf( '<div class="search-item search-type"><div class="search-check-box">%s</div></div>', $check_options );
					break;
				case 'number':
					$hidden_field  = sprintf(
						'<input type="hidden" class="min-volumn" name="filters[_field_%d][min]" value="%s">',
						absint( $field->getFieldId() ),
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) ? absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) : ''
					);
					$hidden_field .= sprintf(
						'<input type="hidden" class="max-volumn" name="filters[_field_%d][max]" value="%s">',
						absint( $field->getFieldId() ),
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) ? absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) : ''
					);

					$field_html = sprintf(
						'<div class="search-item">
                                                <div class="price-range">
                                                    <label>%s</label>
                                                    <input type="number" class="ion-rangeslider" id="%s" data-step="%s" %s %s data-min="%d" data-max="%s" />
                                                    %s
                                                </div>
                                             </div>',
						esc_attr( $field->getLabel() ),
						$id,
						$field->getStepSize() ? esc_attr( $field->getStepSize() ) : 'any',
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) ? sprintf(
							'data-from="%s"',
							absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] )
						) : '',
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) && ! empty( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) ? sprintf(
							'data-to="%s"',
							absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] )
						) : '',
						$field->getMin() !== '' ? absint( $field->getMin() ) : '',
						! empty( $field->getMax() ) ? absint( $field->getMax() ) : absint( $field->getMin() ) + 100,
						$hidden_field
					);
					break;
				case 'url':
					$field_html = sprintf(
						'<input type="url" class="rtcl-url form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" placeholder="%s" value="" />',
						$id,
						absint( $field->getFieldId() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
			}
		}

		return $field_html;
	}

	public static function get_advanced_search_toggle_field_html( $customFieldToggle, $field ) {
		$field_html = null;

		$metaKey = $field->getMetaKey();

		$html           = '';
		$id             = wp_rand();
		$fieldNameLabel = ucwords(
			str_replace(
				[
					'-',
					'_',
				],
				' ',
				$customFieldToggle['name']
			)
		); // Replace with space

		if ( ! empty( $placeholder ) ) {
			$typeText = $placeholder;
		} else {
			$typeText = esc_html__( $fieldNameLabel, 'clcar' );
		}

		if ( isset( $customFieldToggle['element'] ) ) {
			if ( in_array( $customFieldToggle['element'], [ 'radio', 'select', 'checkbox' ], true ) ) {
				$options = $customFieldToggle['options'];
			} else {
				$options = [];
			}

			foreach ( $options as $option ) {
				$optionsFields[ $option['value'] ] = esc_html( $option['label'] );
			}

			$id = "rtcl_{$customFieldToggle['element']}_{$customFieldToggle['uuid']}";

			if ( $customFieldToggle ) {
				// Select, Radio, Checkbox Field
				if ( isset( $customFieldToggle['element'] ) && in_array( $customFieldToggle['element'], [ 'select', 'radio', 'checkbox' ] ) ) {
					$options = $customFieldToggle['options'];
					$items   = [];

					foreach ( $options as $option ) {
						$items[ $option['value'] ] = esc_html( $option['label'] );
					}

					$field_html .= '<div class="rtcl-form-group ws-item ws-type rtcl-flex rtcl-flex-column search-item">';
					$field_html .= '    <div class="rtcl-search-type">';
					$field_html .= '        <select class="rtcl-form-control select2" id="rtcl-search-type-' . esc_attr( $id ) . '" name="cf_' . esc_attr( $customFieldToggle['name'] ) . '">';
					$field_html .= '            <option value="">' . esc_html( $typeText ) . '</option>';

					if ( ! empty( $items ) ) {
						foreach ( $items as $key => $value ) {
							$selected    = ( isset( $_GET[ 'cf_' . $customFieldToggle['name'] ] ) && trim( $_GET[ 'cf_' . $customFieldToggle['name'] ] ) == $key ) ? ' selected' : '';
							$field_html .= '        <option value="' . esc_attr( $key ) . '" ' . $selected . '>' . esc_html( $value ) . '</option>';
						}
					}
					$field_html .= '        </select>';
					$field_html .= '    </div>';
					$field_html .= '</div>';
				}

				// Number Field
				if ( isset( $customFieldToggle['element'] ) && $customFieldToggle['element'] === 'number' ) {
					$min_value = ! empty( $customFieldToggle['validation']['min']['value'] )
						? absint( $customFieldToggle['validation']['min']['value'] )
						: '';

					$max_value = ! empty( $customFieldToggle['validation']['max']['value'] )
						? absint( $customFieldToggle['validation']['max']['value'] )
						: '';

					$field_value = isset( $_GET[ 'cf_' . $customFieldToggle['name'] ] )
						? absint( $_GET[ 'cf_' . $customFieldToggle['name'] ] )
						: '';

					$field_html .= '<div class="rtcl-form-group ws-item ws-type rtcl-flex rtcl-flex-column search-item">';
					$field_html .= '    <div class="rtcl-search-type">';
					$field_html .= ' <input type="number" 
                                    class="rtcl-form-control" 
                                    id="rtcl-search-type-' . esc_attr( $id ) . '" 
                                    name="cf_' . esc_attr( $customFieldToggle['name'] ) . '" 
                                    placeholder="' . esc_attr( $customFieldToggle['placeholder'] ) . '" 
                                    value="' . esc_attr( $field_value ) . '" '
								   . ( $min_value !== '' ? 'min="' . esc_attr( $min_value ) . '"' : '' ) . ' '
								   . ( $max_value !== '' ? 'max="' . esc_attr( $max_value ) . '"' : '' ) . '>';
					$field_html .= '    </div>';
					$field_html .= '</div>';
				}
				// Text and Textarea Field
				if ( isset( $customFieldToggle['element'] ) && in_array( $customFieldToggle['element'], [ 'text', 'textarea' ], true ) ) {
					$field_value = isset( $_GET[ 'cf_' . $customFieldToggle['name'] ] )
						? sanitize_text_field( wp_unslash( $_GET[ 'cf_' . $customFieldToggle['name'] ] ) )
						: '';

					$placeholder_text = sprintf(
						esc_html__( 'Search by %s', 'classified-listing-pro' ),
						$customFieldToggle['label']
					);

					$field_html .= '<div class="rtcl-form-group ws-item ws-type rtcl-flex rtcl-flex-column search-item">';
					$field_html .= '    <div class="rtcl-search-type">';

					if ( $customFieldToggle['element'] === 'textarea' ) {
						$field_html .= '        <textarea 
                        class="rtcl-form-control" 
                        id="rtcl-search-type-' . esc_attr( $id ) . '" 
                        name="cf_' . esc_attr( $customFieldToggle['name'] ) . '" 
                        placeholder="' . esc_attr( $placeholder_text ) . '">' . esc_textarea( $field_value ) . '</textarea>';
					} else {
						$field_html .= '        <input 
                        type="text" 
                        class="rtcl-form-control" 
                        id="rtcl-search-type-' . esc_attr( $id ) . '" 
                        name="cf_' . esc_attr( $customFieldToggle['name'] ) . '" 
                        placeholder="' . esc_attr( $placeholder_text ) . '" 
                        value="' . esc_attr( $field_value ) . '">';
					}

					$field_html .= '    </div>';
					$field_html .= '</div>';
				}
			}
		}

		return $field_html;
	}

	public static function get_favourites_link( $post_id ) {
		$has_favourites = get_option( 'rtcl_general_settings' );
		// print_r($has_favourites);
		if ( ! isset( $has_favourites['has_favourites'] ) || 'yes' !== $has_favourites['has_favourites'] ) {
			return;
		}
		if ( is_user_logged_in() ) {
			if ( $post_id == 0 ) {
				global $post;
				$post_id = $post->ID;
			}

			$favourites = (array) get_user_meta( get_current_user_id(), 'rtcl_favourites', true );

			if ( in_array( $post_id, $favourites ) ) {
				return '<a href="javascript:void(0)" class="rtcl-favourites rtcl-active" data-id="' . $post_id . '">
                            <span class="rtcl-icon rtcl-icon-heart"></span><span class="favourite-label">' . \Rtcl\Helpers\Text::remove_from_favourite() . '</span>
                       </a>';
			} else {
				return '<a href="javascript:void(0)" class="rtcl-favourites" data-id="' . $post_id . '"><i class="rtcl-icon rtcl-icon-heart-empty"></i>
                            <span class="favourite-label">' . \Rtcl\Helpers\Text::add_to_favourite() . '</span>
                        </a>';
			}
		} else {
			return '<a href="javascript:void(0)" class="rtcl-require-login"><i class="rtcl-icon rtcl-icon-heart-empty"></i>
                        <span class="favourite-label">' . \Rtcl\Helpers\Text::add_to_favourite() . '</span>
                   </a>';
		}
	}

	public function delete_listing_logo_attachment() {
		if ( $_POST['attachment_id'] && $_POST['post_id'] ) {
			delete_post_meta( $_POST['post_id'], 'listing_logo_img' );
			wp_delete_attachment( $_POST['attachment_id'] );
			echo 'success';
		} else {
			echo 'error';
		}
		wp_die();
	}

	// Extra Form Field Added
	public function listing_form_save( $listing, $type, $cat_id, $new_listing_status, $data ) {
		$files = $data['files'];

		$logoImage = $files['listing_logo_img'];

		$raw_data = $data['data'];

		if ( ! empty( $logoImage ) && empty( $_POST['logo_attachment_id'] ) ) {
			$logoID = $this->get_listing_attachment_id( $listing->get_id(), $logoImage );
		} elseif ( isset( $_POST['logo_attachment_id'] ) ) {
			$logoID = $_POST['logo_attachment_id'];
		}

		if ( $logoID ) {
			update_post_meta( $listing->get_id(), 'listing_logo_img', $logoID );
		}
	}

	public function get_listing_attachment_id( $post_id, $file ) {
		if ( $file['error'] !== UPLOAD_ERR_OK ) {
			__return_false();
		}

		if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
			get_template_part( ABSPATH . '/wp-admin' . '/includes/image.php' );
			get_template_part( ABSPATH . '/wp-admin' . '/includes/file.php' );
			get_template_part( ABSPATH . '/wp-admin' . '/includes/media.php' );
		}

		Filters::beforeUpload();
		// you can use WP's wp_handle_upload() function:
		$status = wp_handle_upload(
			$file,
			[
				'test_form' => false,
			]
		);
		Filters::afterUpload();

		if ( $status && ! isset( $status['error'] ) ) {
			// $filename should be the path to a file in the upload directory.
			$filename = $status['file'];

			// Check the type of tile. We'll use this as the 'post_mime_type'.
			$filetype = wp_check_filetype( basename( $filename ), null );

			// Get the path to the upload directory.
			$wp_upload_dir = wp_upload_dir();

			// Prepare an array of post data for the attachment.
			$attachment = [
				'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
				'post_mime_type' => $filetype['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			];
			// Insert the attachment.
			$attach_id = wp_insert_attachment( $attachment, $filename );
		}

		return isset( $attach_id ) ? $attach_id : 0;
	}

	public static function add_single_listing_custom_field() {
		global $listing;
		$listing->the_custom_fields();
	}

	/**For single store page code start*/
	public static function listing_query( $view, $rtcl_query, $rtcl_top_query = false, $map = false ) {
		if ( $view == 'grid' ) {
			?>
			<div <?php Functions::listing_loop_start_class( [ 'auto-clear' ] ); ?>>
				<?php
				$col_class = $map ? 'col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12' : 'col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12';
				$col_class = apply_filters( 'clcar_listing_grid_col_class', $col_class, $map );

				if ( Fns::is_enable_top_listings() ) {
					if ( is_object( $rtcl_top_query ) && $rtcl_top_query->have_posts() ) {
						$top_listing = true;
						while ( $rtcl_top_query->have_posts() ) :
							$rtcl_top_query->the_post();
							?>
							<div class="<?php echo esc_attr( $col_class ); ?>">
								<?php Functions::get_template( 'custom/grid', compact( 'top_listing', 'map' ) ); ?>
							</div>
							<?php
						endwhile;
						wp_reset_postdata();
					}
				}

				while ( $rtcl_query->have_posts() ) :
					$rtcl_query->the_post();
					?>
					<div class="listing-box listing-item rtcl-listing-item">
						<?php Functions::get_template( 'custom/grid', compact( 'map' ) ); ?>
					</div>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</div>
			<?php
		} else {
			$layout  = null;
			$display = [];
			if ( $map ) {
				$layout  = 'map';
				$display = [
					'excerpt' => false,
				];
			}
			if ( Fns::is_enable_top_listings() ) {
				if ( is_object( $rtcl_top_query ) && $rtcl_top_query->have_posts() ) {
					$top_listing = true;
					while ( $rtcl_top_query->have_posts() ) :
						$rtcl_top_query->the_post();
						?>
						<div class="listing-box listing-item rtcl-listing-item">
							<?php
							Functions::get_template( 'custom/list', compact( 'top_listing', 'map', 'layout', 'display' ) );
							?>
						</div>
						<?php
					endwhile;
					wp_reset_postdata();
				}
			}

			while ( $rtcl_query->have_posts() ) :
				$rtcl_query->the_post();
				?>
				<div class="listing-box listing-item rtcl-listing-item">
					<?php Functions::get_template( 'custom/list', compact( 'map', 'layout', 'display' ) ); ?>
				</div>
				<?php
			endwhile;
			wp_reset_postdata();
		}
	}

	public static function listing_post_num( $rtcl_query ) {

		$total   = $rtcl_query->found_posts;
		$current = $rtcl_query->post_count;

		if ( $current ) {
			$posts_per_page       = $rtcl_query->query_vars['posts_per_page'];
			$paged                = ! empty( $rtcl_query->query['paged'] ) ? $rtcl_query->query['paged'] : 1;
			$num_of_skipped_items = $posts_per_page * ( $paged - 1 );

			$first = $num_of_skipped_items + 1;

			$last = $num_of_skipped_items + $current;

			$result = [
				'first' => $first,
				'last'  => $last,
				'total' => $total,
			];
		} else {
			$result = false;
		}

		return $result;
	}

	public static function listing_count_text( $post_num ) {
		if ( $post_num ) {
			if ( $post_num['total'] == 1 ) {
				$post_num_text = esc_html__( 'Showing 1 result', 'clcar' );
			} else {
				$post_num_text = sprintf( esc_html__( 'Showing %1$d–%2$d of %3$d results', 'clcar' ), $post_num['first'], $post_num['last'], $post_num['total'] );
			}
		} else {
			$post_num_text = esc_html__( 'Showing 0 result', 'clcar' );
		}

		return $post_num_text;
	}

	public function store_enable_pagination() {
		if ( is_singular( 'store' ) ) {
			remove_action( 'template_redirect', 'redirect_canonical' );
		}
	}

	/**For single store page code start end*/
	// Store
	public static function store_meta() {
		global $store;
		$settings   = RtclFunctions::get_option( 'rtcl_membership_settings' );
		$visibility = isset( $settings['store_archive_visibility'] ) && is_array( $settings['store_archive_visibility'] ) ? $settings['store_archive_visibility'] : [];
		if ( ! empty( $visibility ) ) {
			$show_store_location   = in_array( 'show_store_location', $visibility ) ? true : false;
			$show_store_views      = in_array( 'show_store_views', $visibility ) ? true : false;
			$show_store_phone      = in_array( 'show_store_phone', $visibility ) ? true : false;
			$show_store_mail       = in_array( 'show_store_mail', $visibility ) ? true : false;
			$show_store_webaddress = in_array( 'show_store_webaddress', $visibility ) ? true : false;
		} else {
			$show_store_location   = RDTheme::$options['show_store_location'] ?? false;
			$show_store_views      = RDTheme::$options['show_store_views'] ?? false;
			$show_store_phone      = RDTheme::$options['show_store_phone'] ?? false;
			$show_store_mail       = RDTheme::$options['show_store_mail'] ?? false;
			$show_store_webaddress = RDTheme::$options['show_store_webaddress'] ?? false;
		}
		?>
		<?php if ( $store->get_address() || $store->get_website() || $store->get_phone() ) : ?>
			<ul class="entry-meta">
				<?php if ( $store->get_address() && $show_store_location ) : ?>
					<li><i class="icon-location2"></i><?php echo esc_html( $store->get_address() ); ?></li>
				<?php endif; ?>

				<?php if ( $stor_view_count = self::rt_get_post_view_count( $store->get_id() ) ) : ?>
					<?php if ( $show_store_views ) { ?>
						<li>
						<i class="icon-view"></i>
						<?php
						$label = $stor_view_count < 10 ? esc_html__( 'View: ', 'clcar' ) : esc_html__( 'Views: ', 'clcar' );
						printf(
							"<span class='count-label'>%s</span><span class='count-number'>%s</span>",
							esc_html( $label ),
							esc_html( Helper::rt_number_shorten( $stor_view_count, 1 ) )
						);
						?>
						</li>
					<?php } ?>
				<?php endif; ?>
				<?php if ( $store->get_phone() && $show_store_phone ) : ?>
					<li>
						<a class="item-btn"  href="tel:<?php echo esc_html( $store->get_phone() ); ?>">
							<i class="icon-call"></i>
							<span><?php echo esc_html( $store->get_phone() ); ?></span>
						</a>
					</li>
				<?php endif; ?>
				<?php if ( $store->get_email() && $show_store_mail ) : ?>
					<li>
						<a class="item-btn" href="mailto:<?php echo esc_attr( $store->get_email() ); ?>" >
							<i class="icon-envelop"></i>
							<span><?php echo esc_html( $store->get_email() ); ?></span>
						</a>
					</li>
				<?php endif; ?>
				<?php if ( $store->get_website() && $show_store_webaddress ) : ?>
					<li>
						<a class="item-btn" href="<?php echo esc_url( $store->get_website() ); ?>">
							<i class="icon-web"></i>
							<span><?php echo esc_html( $store->get_website() ); ?></span>
						</a>
					</li>
				<?php endif; ?>
			</ul>
		<?php endif; ?>
		<?php
	}

	public static function store_excerpt() {
		global $store;
		$settings           = RtclFunctions::get_option( 'rtcl_membership_settings' );
		$visibility         = isset( $settings['store_archive_visibility'] ) && is_array( $settings['store_archive_visibility'] ) ? $settings['store_archive_visibility'] : [];
		$show_store_excerpt = in_array( 'show_store_excerpt', $visibility ) ? true : false;
		?>
		<?php if ( $show_store_excerpt ) : ?>
			<div class='store-excerpt'>
				<?php $store->the_excerpt(); ?>
			</div>
		<?php endif; ?>
		<?php
	}

	public static function store_query() {
		global $post;

		$args = [
			'post_type'      => rtcl()->post_type,
			'post_status'    => 'publish',
			'posts_per_page' => Functions::get_option_item( 'rtcl_archive_listing_settings', 'listings_per_page', 20 ),
			'author'         => get_post_meta( $post->ID, 'store_owner_id', true ),
			'paged'          => Pagination::get_page_number(),
		];

		$general_settings = Functions::get_option( 'rtcl_archive_listing_settings' );

		$atts = [
			'orderby' => ! empty( $general_settings['orderby'] ) ? $general_settings['orderby'] : 'date',
			'order'   => ! empty( $general_settings['order'] ) ? $general_settings['order'] : 'DESC',
		];

		$current_order = Pagination::get_listings_current_order( $atts['orderby'] . '-' . $atts['order'] );
		switch ( $current_order ) {
			case 'title-asc':
				$args['orderby'] = 'title';
				$args['order']   = 'ASC';
				break;
			case 'title-desc':
				$args['orderby'] = 'title';
				$args['order']   = 'DESC';
				break;
			case 'date-asc':
				$args['orderby'] = 'date';
				$args['order']   = 'ASC';
				break;
			case 'date-desc':
				$args['orderby'] = 'date';
				$args['order']   = 'DESC';
				break;
			case 'price-asc':
				$args['meta_key'] = 'price';
				$args['orderby']  = 'meta_value_num';
				$args['order']    = 'ASC';
				break;
			case 'price-desc':
				$args['meta_key'] = 'price';
				$args['orderby']  = 'meta_value_num';
				$args['order']    = 'DESC';
				break;
			case 'views-asc':
				$args['meta_key'] = '_views';
				$args['orderby']  = 'meta_value_num';
				$args['order']    = 'ASC';
				break;
			case 'views-desc':
				$args['meta_key'] = '_views';
				$args['orderby']  = 'meta_value_num';
				$args['order']    = 'DESC';
				break;
			case 'rand':
				$args['orderby'] = 'rand';
				break;
		}

		return new \WP_Query( apply_filters( 'rtcl_clcar_store_query', $args ) );
	}

	public function clcar_colorpicker_field_add_new_category( $taxonomy ) {
		?>
		<div class="form-field term-colorpicker-wrap">
			<label for="term-colorpicker"><?php esc_html_e( 'Category Color', 'clcar' ); ?></label>
			<input name="rt_category_color" value="#E7F2FC" class="colorpicker" id="term-colorpicker" />
			<p><?php esc_html_e( 'This is category background color.', 'clcar' ); ?></p>
		</div>
		<?php
	}

	public function clcar_colorpicker_field_edit_category( $term ) {
		$color = get_term_meta( $term->term_id, 'rt_category_color', true );
		$color = ( ! empty( $color ) ) ? "#{$color}" : '#E7F2FC';

		?>
		<tr class="form-field term-colorpicker-wrap">
			<th scope="row"><label for="term-colorpicker"><?php esc_html_e( 'Category Color', 'clcar' ); ?></label></th>
			<td>
				<input name="rt_category_color" value="<?php echo esc_attr( $color ); ?>" class="colorpicker" id="term-colorpicker" />
				<p class="description"><?php esc_html_e( 'This is category background color.', 'clcar' ); ?></p>
			</td>
		</tr>
		<?php
	}

	public function clcar_save_termmeta( $term_id ) {
		// Save term color if possible
		if ( isset( $_POST['rt_category_color'] ) && ! empty( $_POST['rt_category_color'] ) ) {
			update_term_meta( $term_id, 'rt_category_color', sanitize_hex_color_no_hash( $_POST['rt_category_color'] ) );
		} else {
			delete_term_meta( $term_id, 'rt_category_color' );
		}

		if ( isset( $_POST['rt_category_image'] ) && ! empty( $_POST['rt_category_image'] ) ) {
			update_term_meta( $term_id, 'rt_category_image', absint( $_POST['rt_category_image'] ) );
		} else {
			delete_term_meta( $term_id, 'rt_category_image' );
		}
	}

	// Advanced search ajax filter
	public static function get_advanced_search_ajax_filter_field_html( $field_id ) {
		$field      = new RtclCFGField( $field_id );
		$field_html = null;

		if ( $field_id && $field ) {
			$id = "rtcl_{$field->getType()}_{$field->getFieldId()}";

			$attributes = [
				'data-id'   => '_field_' . $field->getFieldId(),
				'data-type' => $field->getType(),
			];
			$attributes = apply_filters( 'rtcl_cf_attributes_for_field_html', $attributes, $field );

			switch ( $field->getType() ) {
				case 'text':
					$field_html = sprintf(
						'<div class="search-item"><input type="text" class="rtcl-text form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" placeholder="%s" value="" /></div>',
						$id,
						absint( $field->getFieldId() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
				case 'textarea':
					$field_html = sprintf(
						'<textarea class="rtcl-textarea form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" rows="%d" placeholder="%s"></textarea>',
						$id,
						absint( $field->getFieldId() ),
						absint( $field->getRows() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
				case 'select':
					$options      = $field->getOptions();
					$choices      = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$options_html = '<option value="">' . esc_html( $field->getLabel() ) . '</option>';

					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$_attr = '';
							if ( isset( $_GET['filters'][ '_field_' . $field->getFieldId() ] ) && $_GET['filters'][ '_field_' . $field->getFieldId() ] == strtolower( $choice ) ) {
								$_attr .= ' selected';
							}

							$options_html .= sprintf( '<option value="%s"%s>%s</option>', $key, $_attr, $choice );
						}
					}

					$field_html = sprintf(
                        '<div class="search-item search-select rtcl-cf-wrap" %s><select name="filters[_field_%d]"  id="%s" data-placeholder="%s" class="select2">%s</select></div>',
                        self::esc_attrs( $attributes ),
                        absint( $field->getFieldId() ),
                        $id,
                        $field->getLabel(),
                        $options_html
					);
					break;
				case 'checkbox':
					$options       = $field->getOptions();
					$value         = isset( $_GET['filters'][ '_field_' . $field->getFieldId() ] ) ? $_GET['filters'][ '_field_' . $field->getFieldId() ] : [];
					$choices       = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$check_options = null;
					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$_attr = '';
							if ( in_array( $key, $value ) ) {
								$_attr .= ' checked="checked"';
							}
							$check_options .= sprintf(
								'<div class="form-check"><input class="form-check-input" id="%s" type="checkbox" name="filters[_field_%d][]" value="%s"%s><label class="form-check-label" for="%s">%s</label></div>',
								$id . $key,
								absint( $field->getFieldId() ),
								$key,
								$_attr,
								$id . $key,
								$choice
							);
						}
					}
					$field_html = sprintf( '<div class="search-item checkbox-wrapper">%s</div>', $check_options );
					break;
				case 'radio':
					$options       = $field->getOptions();
					$choices       = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$check_options = null;
					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$check_options .= sprintf(
								'<div class="form-check"><input class="form-check-input" id="%s" type="radio" name="filters[_field_%d]" value="%s"><label class="form-check-label" for="%s">%s</label></div>',
								$id . $key,
								absint( $field->getFieldId() ),
								$key,
								$id . $key,
								$choice
							);
						}
					}
					$field_html = sprintf( '<div class="search-item search-type"><div class="search-check-box">%s</div></div>', $check_options );
					break;
				case 'number':
					$hidden_field  = sprintf(
						'<input type="hidden" class="min-volumn" name="filters[_field_%d][min]" value="%s">',
						absint( $field->getFieldId() ),
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) ? absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) : ''
					);
					$hidden_field .= sprintf(
						'<input type="hidden" class="max-volumn" name="filters[_field_%d][max]" value="%s">',
						absint( $field->getFieldId() ),
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) ? absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) : ''
					);

					$field_html = sprintf(
						'<div class="search-item">
                                                <div class="price-range">
                                                    <label>%s</label>
                                                    <input type="number" class="ion-rangeslider" id="%s" data-step="%s" %s %s data-min="%d" data-max="%s" />
                                                    %s
                                                </div>
                                             </div>',
						esc_attr( $field->getLabel() ),
						$id,
						$field->getStepSize() ? esc_attr( $field->getStepSize() ) : 'any',
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) ? sprintf(
							'data-from="%s"',
							absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] )
						) : '',
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) && ! empty( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) ? sprintf(
							'data-to="%s"',
							absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] )
						) : '',
						$field->getMin() !== '' ? absint( $field->getMin() ) : '',
						! empty( $field->getMax() ) ? absint( $field->getMax() ) : absint( $field->getMin() ) + 100,
						$hidden_field
					);
					break;
				case 'url':
					$field_html = sprintf(
						'<input type="url" class="rtcl-url form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" placeholder="%s" value="" />',
						$id,
						absint( $field->getFieldId() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
			}
		}

		return $field_html;
	}

	public static function esc_attrs( $attrs ) {
		$html = '';

		// Loop over attrs and validate data types.
		foreach ( $attrs as $k => $v ) {

			// String (but don't trim value).
			if ( is_string( $v ) && ( $k !== 'value' ) ) {
				$v = trim( $v );

				// Boolean
			} elseif ( is_bool( $v ) ) {
				$v = $v ? 1 : 0;

				// Object
			} elseif ( is_array( $v ) || is_object( $v ) ) {
				$v = json_encode( $v );
			}

			// Generate HTML.
			$html .= sprintf( ' %s="%s"', esc_attr( $k ), esc_attr( $v ) );
		}

		// Return trimmed.
		return trim( $html );
	}

	public function listing_details_layout_settings( $options ) {
		$options['listing_details_extra_layout_settings'] = [
			'title' => esc_html__( 'Layout Settings', 'clcar' ),
			'type'  => 'section',
		];
		$options['listing_details_layout']                = [
			'title'   => __( 'Layout', 'clcar' ),
			'type'    => 'select',
			'default' => '1',
			'options' => [
				'1' => __( 'Layout 1', 'clcar' ),
				'2' => __( 'Layout 2', 'clcar' ),
				'3' => __( 'Layout 3', 'clcar' ),
			],
		];
		return $options;
	}

	public function listing_store_archive_visibility_settings( $options ) {
		// New Section
		$options['store_archive_settings_section'] = [
			'title' => __( 'Store Archive Settings', 'clcar' ),
			'type'  => 'section',
		];
		$options['store_layout']                   = [
			'title'       => esc_html__( 'Store Layout', 'clcar' ),
			'type'        => 'select',
			'default'     => 'grid',
			'options'     => [
				'grid' => esc_html__( 'Grid View', 'clcar' ),
				'list' => esc_html__( 'List View', 'clcar' ),
			],
			'description' => esc_html__( 'Choose the default listing layout for archive.', 'clcar' ),
		];
		$options['store_archive_visibility']       = [
			'title'       => __( 'Store Archive Visibility', 'clcar' ),
			'type'        => 'multi_checkbox',
			'orientation' => 'vertical',
			'options'     => [
				'show_ad_count'       => __( 'Show Ad Count', 'clcar' ),
				'show_store_views'    => __( 'Show Store Views', 'clcar' ),
				'show_store_ratings'  => __( 'Show Store Ratings', 'clcar' ),
				'show_store_location' => __( 'Show Store Location', 'clcar' ),
				'show_store_excerpt'  => __( 'Show Store Excerpt', 'clcar' ),
				'show_store_phone'    => __( 'Show Store Phone', 'clcar' ),
				'show_store_mail'     => __( 'Show Store Email', 'clcar' ),
				'show_store_website'  => __( 'Show Store Website Link', 'clcar' ),
			],
			'description' => __( 'Check the box what you need display in store archive page.', 'clcar' ),
		];

		return $options;
	}

	public static function add_store_info() {
		global $listing;
		if ( ! $listing ) {
			return;
		}
		$store = self::get_store_by_owner_id( $listing->get_owner_id() );
		if ( ! $store ) {
			return;
		}
		?>
		<div class="rtcl-single-store-info">
			<div class="rtcl-store-image">
				<a href="<?php $store->the_permalink(); ?>" class="store-logo">
					<?php echo wp_kses_post( $store->get_the_logo( 'full' ) ); ?>
				</a>
				<a href="<?php $store->the_permalink(); ?>" class="store-name d-none">
					<?php $store->the_title(); ?>
				</a>
			</div>
		</div>
		<?php
	}

	public static function listing_logo() {
		global $listing;
		if ( ! $listing ) {
			return;
		}
		if ( Functions::isEnableFb() ) {
			$logoId = get_post_meta( $listing->get_id(), 'listing_logo', true );
			if ( ! empty( $logoId ) ) {
				$logo_id = $logoId[0];
			} else {
				$logo_id = '';
			}
		} else {
			$logo_id = get_post_meta( $listing->get_id(), 'listing_logo_img', true );
		}
		if ( ! empty( $logo_id ) ) {
			?>
		<div class="rtcl-listing-logo-image">
			<?php echo wp_get_attachment_image( $logo_id, 'full' ); ?>
		</div>
			<?php
		}
	}

	/**
	 * Get Store object by listing owner id
	 *
	 * @param $author_id
	 *
	 * @return bool|\RtclStore\Models\Store
	 */
	public static function get_store_by_owner_id( $author_id ) {
		if ( ! class_exists( 'RtclStore' ) ) {
			return null;
		}
		$store_id = get_posts(
			[
				'post_type'      => rtclStore()->post_type,
				'posts_per_page' => 1,
				'post_status'    => 'publish',
				'fields'         => 'ids',
				'meta_query'     => [
					[
						'key'   => 'store_owner_id',
						'value' => $author_id,
					],
				],
			]
		);

		if ( ! empty( $store_id ) ) {
			return rtclStore()->factory->get_store( $store_id[0] );
		}

		return null; // or handle fallback
	}

	public static function listing_details_gallery() {
		global $listing;
		// Image Gallery
		$images              = $listing->get_images();
		$total_gallery_image = count( $images );

		$number = $total_gallery_image - 5;

		if ( $total_gallery_image < 2 ) {
			$item_count = 'items-one';
		} elseif ( $total_gallery_image < 3 ) {
			$item_count = 'items-two';
		} elseif ( $total_gallery_image < 4 ) {
			$item_count = 'items-three';
		} elseif ( $total_gallery_image < 5 ) {
			$item_count = 'items-four';
		} else {
			$item_count = 'items-five';
		}
		if ( $total_gallery_image ) {
			?>

			<div class="photo-swip-gallery-wrap <?php echo esc_attr( $item_count ); ?>">
				<?php
				$counter = 0;
				foreach ( $images as $image ) {
					++$counter;
					if ( $total_gallery_image < 4 ) {
						$img_size = 'full';
					} else {
						$img_size = 'rtcl-gallery';
					}
					?>
					<div class="listing-gallery-item photoswip-item image-size-<?php echo esc_attr( $img_size . ' item-' . $counter ); ?>">
						<?php
						$image_url = wp_get_attachment_image_url( $image->ID, 'full' );

						$response = wp_remote_get( $image_url );

						// Check for successful response
						if ( is_array( $response ) && ! is_wp_error( $response ) ) {
							$image_data = wp_remote_retrieve_body( $response );
						} else {
							echo 'Error: ' . esc_html( $response->get_error_message() );
						}

						if ( $image_data !== false ) {
							// Create a temporary file in memory from the image data
							$temp_image = tmpfile();

							// Write the image data to the temporary file
							fwrite( $temp_image, $image_data );

							// Get the temporary file's meta data (including its file path)
							$meta_data       = stream_get_meta_data( $temp_image );
							$temp_image_path = $meta_data['uri'];

							// Use getimagesize to get the image dimensions
							$image_size = getimagesize( $temp_image_path );

							if ( $image_size !== false ) {
								$width  = $image_size[0];
								$height = $image_size[1];
							} else {
								$width  = 'auto';
								$height = 'auto';
							}
							?>
							<a class="listing-popup-btn" href="<?php echo esc_url( $image_url ); ?>" data-width="<?php echo esc_attr( $width ); ?>" data-height="<?php echo esc_attr( $height ); ?>">
								<?php
								echo wp_get_attachment_image( $image->ID, $img_size );
								if ( ! empty( $number ) && $counter === 5 ) {
									echo '<span>+' . esc_html( $number ) . '</span>';
								}
								?>
							</a>
							<?php
							fclose( $temp_image );
						}
						?>
					</div>
					<?php
				}
				?>
			</div>
			<?php
		}
	}

	public static function clcar_migrate_logo( $post_id = 0 ) {
		if ( get_option( 'rtcl_clcar_logo_data_migrated' ) == 1 ) {
			return;
		}
		global $wpdb;

		$attachment_id = (int) get_post_meta( $post_id, 'listing_logo_img', true );

		if ( ! empty( $attachment_id ) && is_numeric( $attachment_id ) ) {
			// Create new meta value in array format
			$new_logo = [ $attachment_id ];
			// Save to new key
			update_post_meta( $post_id, 'listing_logo', $new_logo );
			// add meta key for attachment post
			update_post_meta( $attachment_id, '_rtcl_attachment_type', 'file' );

			// Update the attachment
			$file = get_post_meta( $attachment_id, '_wp_attached_file', true );

			if ( ! empty( $file ) ) {
				$upload_dir = wp_upload_dir();
				$new_guid   = trailingslashit( $upload_dir['baseurl'] ) . $file;

				$wpdb->update(
					$wpdb->posts,
					[
						'guid'        => $new_guid,
						'post_parent' => $post_id,
					],
					[ 'ID' => $attachment_id ],
					[ '%s', '%d' ],
					[ '%d' ]
				);
			}
		}
	}

	public function migrate_clcar_logo_data() {
		if ( get_option( 'rtcl_clcar_logo_data_migrated' ) == 1 ) {
			return;
		}
		try {
			$post_ids = get_posts(
				[
					'post_type'      => 'rtcl_listing',
					'post_status'    => 'any',
					'posts_per_page' => - 1,
					'fields'         => 'ids',
				]
			);
			foreach ( $post_ids as $post_id ) {
				self::clcar_migrate_logo( $post_id );
			}
			update_option( 'rtcl_clcar_logo_data_migrated', 1 );
		} catch ( Throwable $e ) {
			error_log( 'Listing data migration failed: ' . $e->getMessage() );
		}
	}

	public function rtcl_listing_style_option( $options ) {
		$new_option = [
			'listing_style' => [
				'title'       => esc_html__( 'Listing Style', 'clcar' ),
				'type'        => 'select',
				'default'     => '1',
				'options'     => [
					'1' => esc_html__( 'Style 1', 'clcar' ),
					'2' => esc_html__( 'Style 2', 'clcar' ),
				],
				'description' => esc_html__( 'Choose the default listing card style for archive.', 'clcar' ),
			],
		];

		// After default_view added listing_style
		$insert_after = 'default_view';
		$new_options  = [];

		foreach ( $options as $key => $value ) {
			$new_options[ $key ] = $value;

			// When got default_view then insert this new option
			if ( $key === $insert_after ) {
				$new_options = array_merge( $new_options, $new_option );
			}
		}

		return $new_options;
	}

	// Related Listings Slider Options
	public static function clcar_custom_related_slider_options( $slider_options ) {
		return [
			'rtl'                 => is_rtl(),
			'loop'                => false,
			'autoplay'            => [
				'delay'                => 4000,
				'disableOnInteraction' => false,
			],
			'speed'               => 800,
			'spaceBetween'        => 20,
			'slidesPerView'       => 1,
			'watchSlidesProgress' => true,
			'navigation'          => [
				'nextEl' => '.rtcl-related-slider .swiper-button-next',
				'prevEl' => '.rtcl-related-slider .swiper-button-prev',
			],
			'pagination'          => [
				'el'        => '.rtcl-related-slider .swiper-pagination',
				'clickable' => true,
			],
			'breakpoints'         => [
				0    => [ 'slidesPerView' => 1 ],
				576  => [ 'slidesPerView' => 2 ],
				768  => [ 'slidesPerView' => 3 ],
				1200 => [ 'slidesPerView' => 4 ],
			],
		];
	}

	/**
	 * Check if a custom field group has any displayable data for a listing.
	 *
	 * @param int $group_id The custom field group ID.
	 * @param int $listing_id The listing post ID.
	 *
	 * @return bool True if at least one field has a value.
	 */
	public static function clcar_has_cfg_group_data( $group_id, $listing_id ) {
		if ( empty( $group_id ) ) {
			return false;
		}

		$field_ids = \Rtcl\Helpers\Functions::get_cf_ids_by_cfg_id( $group_id );

		if ( empty( $field_ids ) ) {
			return false;
		}

		foreach ( $field_ids as $single_field ) {
			$field = new \Rtcl\Models\RtclCFGField( $single_field );
			$value = $field->getFormattedCustomFieldValue( $listing_id );

			if ( ! empty( $value ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param Listing $listing
	 * @param $phone
	 * @param $whatsapp_number
	 */
	public static function clcar_phone_number( $listing, $number ) {
		if ( is_a( $listing, Listing::class ) ) {
			$phone = $number;
			if ( $phone ) {
				$mobileClass   = wp_is_mobile() ? ' rtcl-mobile' : null;
				$phone_options = [];
				if ( $phone ) {
					$phone_options = [
						'safe_phone'   => mb_substr( $phone, 0, mb_strlen( $phone ) - 3 ) . apply_filters( 'rtcl_phone_number_placeholder', 'XXX' ),
						'phone_hidden' => mb_substr( $phone, - 3 ),
					];
				}

				$phone_options = apply_filters(
					'rtcl_phone_number_options',
					$phone_options,
					[
						'phone' => $phone,
					]
				)
				?>
				<div class='reveal-phone<?php echo esc_attr( $mobileClass ); ?>'
                     data-options="<?php
				     // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				     echo htmlspecialchars( wp_json_encode( $phone_options ) ); ?>"
                     data-id="<?php echo esc_attr( $listing->get_id() ); ?>">
					<div class='media'>
						<div class='media-body'>
							<div class='numbers'>
                                <?php
                                    if ( $phone ) {
                                        echo esc_html( $phone_options['safe_phone'] );
                                    }
                                ?>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
		}
	}

	/**
	 * @param Listing $listing
	 * @param $phone
	 * @param $whatsapp_number
	 */
	public static function clcar_whatsapp_number( $listing, $number ) {
		if ( is_a( $listing, Listing::class ) ) {
			$whatsapp_number = $number;
			if ( $whatsapp_number && ! Functions::is_field_disabled( 'whatsapp_number' ) ) {
				$mobileClass      = wp_is_mobile() ? ' rtcl-mobile' : null;
				$whatsapp_options = [];
				if ( $whatsapp_number && ! Functions::is_field_disabled( 'whatsapp_number' ) ) {
					$whatsapp_options['safe_whatsapp_number'] = mb_substr( $whatsapp_number, 0, mb_strlen( $whatsapp_number ) - 3 )
																. apply_filters( 'rtcl_phone_number_placeholder', 'XXX' );
					$whatsapp_options['whatsapp_hidden']      = mb_substr( $whatsapp_number, - 3 );
				}
				$whatsapp_options = apply_filters(
					'rtcl_phone_number_options',
					$whatsapp_options,
					[
						'whatsapp_number' => $whatsapp_number,
					]
				);
				?>
				<div class='reveal-phone<?php echo esc_attr( $mobileClass ); ?>'
                     data-options="<?php
				     // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				     echo htmlspecialchars( wp_json_encode( $whatsapp_options ) ); ?>"
                     data-id="<?php echo esc_attr( $listing->get_id() ); ?>">
					<div class='media'>
						<div class='media-body'>
							<div class='numbers'>
							<?php
							if ( $whatsapp_number ) {
								echo esc_html( $whatsapp_options['safe_whatsapp_number'] );
							}
							?>
								</div>
						</div>
					</div>
				</div>
				<?php
			}
		}
	}
}

Listing_Functions::instance();