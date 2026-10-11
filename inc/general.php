<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\ClCar;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Breadcrumb;
use Rtcl\Helpers\Functions;
use radiustheme\ClCar\Listing_Functions;
use Rtcl\Models\RtclCFGField;
use RtclPro\Controllers\Hooks\TemplateHooks as NewTemplateHooks;
use RtclPro\Helpers\Fns;

class General_Setup {

	protected static $instance = null;

	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'theme_setup' ] );
		add_filter( 'max_srcset_image_width', [ $this, 'disable_wp_responsive_images' ] );
		add_action( 'widgets_init', [ $this, 'register_sidebars' ] );
		add_action( 'clcar_breadcrumb', [ $this, 'breadcrumb' ] );
		add_filter( 'body_class', [ $this, 'body_classes' ] );
		add_action( 'wp_head', [ $this, 'noscript_hide_preloader' ], 1 );
		add_action( 'wp_head', [ $this, 'pingback' ] );
		add_action( 'wp_body_open', [ $this, 'preloader' ] );
		add_action( 'wp_footer', [ $this, 'scroll_to_top_html' ], 1 );
		add_filter( 'get_search_form', [ $this, 'search_form' ] );
		//add_filter( 'comment_form_fields', [ $this, 'move_textarea_to_bottom' ] );
		add_filter( 'post_class', [ $this, 'hentry_config' ] );
		add_filter( 'excerpt_more', [ $this, 'excerpt_more' ] );
		add_filter( 'wp_list_categories', [ $this, 'add_span_cat_count' ] );
		add_filter( 'get_archives_link', [ $this, 'add_span_archive_count' ] );
		add_filter( 'widget_text', 'do_shortcode' );

		//Add user 
		add_action( 'personal_options_update', [ $this, 'rt_update_user_profile_fields' ] );
		add_action( 'edit_user_profile_update', [ $this, 'rt_update_user_profile_fields' ] );

		//Disable Gutenberg widget block
		add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );// Disables the block editor from managing widgets in the Gutenberg plugin.
		add_filter( 'use_widgets_block_editor', '__return_false' ); // Disables the block editor from managing widgets.

		
		//remove admin bar


		//agent comments on
		add_filter('rtcl_agent_comment_status', function(){
			$text = esc_html__('open','clcar');
			return $text;
		});

		add_action('widgets_init', [$this, 'clcar_theme_unregister_widgets'], 11);
		
		
	}

	
	//Plugin Widget Unregister
	public function clcar_theme_unregister_widgets() {
			unregister_sidebar( 'rtcl-archive-sidebar' );
			unregister_sidebar( 'rtcl-single-sidebar' );
	}


	//disable wp responsive images
	function disable_wp_responsive_images() {
		return 1;
	}

	// Update user profile info
	function rt_update_user_profile_fields( $user_id ) {
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		if ( ! empty( $_POST['user_store'] ) && intval( $_POST['user_store'] ) >= 1900 ) {
			update_user_meta( $user_id, 'user_store', intval( $_POST['user_store'] ) );
		}
	}


	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function theme_setup() {
		// Theme supports
		add_theme_support( 'title-tag' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'html5', [ 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ] );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'editor-styles' );

		// Image sizes
		$sizes = [
			'rdtheme-size1'  => [ 1200, 650, true ], // When Full width
			'rdtheme-size2'  => [ 370, 276, true ], // blog grid and blog list image
			'rdtheme-size3'  => [ 570, 497, true ], // listing slider addon thumbnail
		];

		$sizes = apply_filters( 'clcar_image_size', $sizes );

		foreach ( $sizes as $size => $value ) {
			add_image_size( $size, $value[0], $value[1], $value[2] );
		}

		// Register menus
		register_nav_menus(
			[
				'primary'   => esc_html__( 'Primary', 'clcar' ),
			]
		);
	}

	public function register_sidebars() {
		register_sidebar(
			[
				'name'          => esc_html__( 'Sidebar', 'clcar' ),
				'id'            => 'sidebar',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-heading">',
				'after_title'   => '</h3>',
			]
		);

		$footer_widget_titles = [
			'1' => esc_html__( 'Footer 1', 'clcar' ),
			'2' => esc_html__( 'Footer 2', 'clcar' ),
			'3' => esc_html__( 'Footer 3', 'clcar' ),
			'4' => esc_html__( 'Footer 4', 'clcar' ),
		];

		foreach ( $footer_widget_titles as $id => $name ) {
			register_sidebar(
				[
					'name'          => $name,
					'id'            => 'footer-' . $id,
					'before_widget' => '<div id="%1$s" class="footer-box %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h3 class="footer-title">',
					'after_title'   => '</h3>',
				]
			);
		}
		register_sidebar(
			[
				'name'          => esc_html__( 'Single Listing Sidebar', 'clcar' ),
				'id'            => 'single-listing-sidebar',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-heading">',
				'after_title'   => '</h3>',
			]
		);

		register_sidebar(
			[
				'name'          => esc_html__( 'Store Sidebar', 'clcar' ),
				'id'            => 'store-sidebar',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-heading">',
				'after_title'   => '</h3>',
			]
		);

		register_sidebar(
			[
				'name'          => esc_html__( 'Listing Archive Sidebar', 'clcar' ),
				'id'            => 'listing-archive-sidebar',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-heading">',
				'after_title'   => '</h3>',
			]
		);

	}

	public function body_classes( $classes ) {
		//Theme Version

		$clcar_theme = wp_get_theme();
		$classes[] = $clcar_theme->Name.'-version-'.$clcar_theme->Version;
		$classes[] = 'theme-clcar';
		$classes[] = 'header-style-' . RDTheme::$header_style;
		$classes[] = 'header-width-' . RDTheme::$header_width;
		$sticky    = RDTheme::$options['sticky_header'] ? 1 : 0;

		if ( $sticky && RDTheme::$options['single_listing_style'] !== '2' ) {
			$classes[] = 'sticky-header';
		}

		if ( RDTheme::$has_tr_header ) {
			$classes[] = 'trheader';
		} else {
			$classes[] = 'no-trheader';
		}

		if ( is_front_page() && ! is_home() ) {
			$classes[] = 'front-page';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			$classes[] = 'single-listing-style';
		}

		if ( class_exists( 'ClCar_Core' ) ) {
			$classes[] = 'clcar-core-installed';
		}

		if ( ! class_exists( 'ClCar_Core' ) ) {
			$classes[] = 'need-clcar-core';
		}

		if ( Helper::has_full_width() ) {
			$classes[] = 'is-full-width';
		}

		// WooCommerce
		if ( isset( $_COOKIE["shopview"] ) && $_COOKIE["shopview"] == 'list' ) {
			$classes[] = 'product-list-view';
		} else {
			$classes[] = 'product-grid-view';
		}

		global $post;
		if ( isset( $post ) ) {
			$classes[] = $post->post_type . '-' . $post->post_name;
		}

		if ( isset( $_GET ) && ! empty( $_GET ) ) {
			$classes[] = 'clcar-have-request';
		}

		return $classes;
	}

	public function is_blog() {
		return ( is_archive() || is_author() || is_category() || is_home() || is_single() || is_tag() ) && 'post' == get_post_type();
	}

	public function noscript_hide_preloader() {
		// Hide preloader if js is disabled
		echo '<noscript><style>#preloader{display:none;}</style></noscript>';
	}

	public function pingback() {
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
		}
	}

	public function preloader() {
		// Preloader
		if ( RDTheme::$options['preloader'] ) {
			if ( ! empty( wp_get_attachment_image_url( RDTheme::$options['preloader_image'], 'full' ) ) ) {
				$preloader_img = wp_get_attachment_image_url( RDTheme::$options['preloader_image'], 'full' );
			} else {
				$preloader_img = Helper::get_img( 'preloader.gif' );
			}
			echo '<div id="preloader" style="background-image:url(' . esc_url( $preloader_img ) . ');"></div>';
		}
	}

	public function wp_body_open() {
		do_action( 'wp_body_open' );
	}

	public function scroll_to_top_html() {
		// Back-to-top link
		if ( RDTheme::$options['back_to_top'] ) {
			echo '<a href="#" class="scrollup"><i class="fas fa-angle-double-up"></i>' . esc_html__( 'TOP', 'clcar' ) . '</a>';
		}
	}

	public function search_form() {
		$output = '
		<form method="get" class="custom-search-form" action="' . esc_url( home_url( '/' ) ) . '">
            <div class="search-box">
                <div class="row gutters-10">
                    <div class="col-12 form-group mb-0">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="' . esc_attr__( 'What are you looking for?', 'clcar' ) . '" value="' . get_search_query() . '" name="s" />
                            <span class="input-group-append">
                                <button class="item-btn" type="submit">
                                    <i class="icon-search2"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
		</form>
		';

		return $output;
	}

	public function move_textarea_to_bottom( $fields ) {
		$temp = $fields['comment'];
		unset( $fields['comment'] );
		$fields['comment'] = $temp;

		return $fields;
	}

	public function hentry_config( $classes ) {
		if ( is_search() || is_page() ) {
			$classes = array_diff( $classes, [ 'hentry' ] );
		}

		return $classes;
	}

	public function excerpt_more() {
		if ( is_search() ) {
			$readmore = '<a href="' . get_the_permalink() . '"> [' . esc_html__( 'read more ...', 'clcar' ) . ']</a>';

			return $readmore;
		}

		return ' ...';
	}

	public function add_span_cat_count( $links ) {
		$links = str_replace( '</a> (', '<span>(', $links );
		$links = str_replace( ')', ')</span></a>', $links );

		return $links;
	}

	public function add_span_archive_count( $links ) {
		$links = str_replace( '</a>&nbsp;(', '<span>(', $links );
		$links = str_replace( ')', ')</span></a>', $links );

		return $links;
	}

	public function breadcrumb() {
		$filters = isset( $_GET['filters'] ) ? (array)$_GET['filters'] : [];
        $category_text = isset($_GET['rtcl_category']) ? sanitize_text_field($_GET['rtcl_category']):'';
		$cf = array_filter( $filters );

        $name = [];
		$search_class = '';
        if (!empty($cf)){
	        foreach ( $cf as $key => $values ) {
                if (! is_array($values)){
                    $name[] = $values;
                }
            }
        }
        if (!empty($name)){
            $search_text = $name[0];
            $search_class = 'has-custom-filter';
        }
        if (empty($search_text) && !empty($category_text)){
	        $search_text = strtoupper(sanitize_text_field($_GET['rtcl_category']));
        }
		if ( is_404() ) {
			$clcar_title = esc_html__( 'Error Page', 'clcar' );
		}
		if( is_search() && !empty($search_text)){
			$clcar_title = esc_html__( 'Search Results for : ', 'clcar' ) . strtoupper($search_text);
		}
		else if ( is_search() ) {
			$clcar_title = esc_html__( 'Search Results for : ', 'clcar' ) . get_search_query();
		} 
		else if ( is_home() ) {
			if ( get_option( 'page_for_posts' ) ) {
				$clcar_title = get_the_title( get_option( 'page_for_posts' ) );
			}
			else {
				$clcar_title = apply_filters( 'theme_blog_title', esc_html__( 'All Posts', 'clcar' ) );
			}
		}
		else if ( is_archive() ) {
			$clcar_title = get_the_archive_title();
		} 
		else if ( is_page() ) {
			$clcar_title = get_the_title();
		} 
		else if ( is_single() ) {
			$clcar_title ='';
		}

		$args = [
			'delimiter'   => '&nbsp;<i class="icon-arrow-right-thin2"></i>&nbsp;',
			
			'wrap_before' => '<nav class="rtcl-breadcrumb">',
			'wrap_after'  => '</nav>',
			'before'      => '',
			'after'       => '',
			'home'        => _x( 'Home', 'breadcrumb', 'clcar' ),
		];

		$breadcrumbs = new Breadcrumb();
       

		if ( ! empty( $args['home'] ) ) {
			$breadcrumbs->add_crumb( $args['home'], home_url() );
		}

		$args['breadcrumb'] = $breadcrumbs->generate();
        


		if ( ! empty( $args['breadcrumb'] ) ) {

			?>
            <section class="breadcrumbs-banner <?php echo esc_attr($search_class); ?>">
			
                <div class="container">
					<div class="rt-breadcrumbs-content">
						<?php if($clcar_title && !is_author()){ ?>
							<h2><?php echo wp_kses_post($clcar_title); ?></h2>
						<?php } ?>
						<?php
						echo wp_kses_post( $args['wrap_before'] );
						foreach ( $args['breadcrumb'] as $key => $crumb ) {
							echo wp_kses_post( $args['before'] );
							if ( ! empty( $crumb[1] ) && sizeof( $args['breadcrumb'] ) !== $key + 1 ) {
								echo '<a href="' . esc_url( $crumb[1] ) . '">' . esc_html( $crumb[0] ) . '</a>';
							} else {
								echo '<span>' . esc_html( $crumb[0] ) . '</span>';
							}
							echo wp_kses_post( $args['after'] );
							if ( sizeof( $args['breadcrumb'] ) !== $key + 1 ) {
								echo wp_kses_post( $args['delimiter'] );
							}
						}
						echo wp_kses_post( $args['wrap_after'] );
						?>
						<div class="breadcrumb-shape">
							<img src="<?php echo esc_url(CLCAR_ASSETS_URL.'img/breadcrumb-shape.png'); ?>" alt="<?php echo esc_attr( 'shape','clcar' ); ?>">
						</div>
					</div>
					
                </div>
            </section>
			<?php
		}
	}




}

General_Setup::instance();