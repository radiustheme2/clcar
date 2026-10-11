<?php
/**
 * Newsletter section
 */
namespace radiustheme\ClCar;

if (!defined('ABSPATH')) {
    exit;
}

$footer_apps_banner_building_id=RDTheme::$options['footer_apps_banner_img'];

?>

<div class="call-to-action-wrap-layout2 motion-effects-wrap">
      <div class="shape-wrap-main">
        <div class="shape-1 wow fadeInDown animated animated" data-wow-delay="0.3s" data-wow-duration="1s">
          <img class="motion-effects1" src="<?php echo esc_url( CLCAR_ASSETS_URL . 'img/apps-shape1.png' ); ?>" alt="<?php echo esc_attr('Shape', 'clcar'); ?>">
        </div>
        <div class="shape-2 wow fadeInDown animated animated" data-wow-delay="0.5s" data-wow-duration="1s">
          <img class="motion-effects3" src="<?php echo esc_url( CLCAR_ASSETS_URL . 'img/apps-shape2.png' ); ?>" alt="<?php echo esc_attr('Shape', 'clcar'); ?>">
        </div>
      </div>
      <div class="container">
        <div class="row justify-content-center g-4 row-cols-lg-2 row-cols-1">
          <div class="col">
            <div class="call-to-action-box call-to-action-style-1">
              <div class="content-holder">
                <h2 class="entry-title"><?php echo wp_kses_post(RDTheme::$options['footer_apps_banner_title']); ?></h2>
                <?php if(RDTheme::$options['footer_apps_banner_text']){ ?>
                    <p class="entry-description"><?php echo wp_kses_post(RDTheme::$options['footer_apps_banner_text']); ?></p>
                <?php } ?>
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <?php if(RDTheme::$options['footer_apps_banner_google_btn']){ ?>
                        <a class="app-box google" href="<?php echo esc_url(RDTheme::$options['footer_apps_google_url']) ?>" target="_blank">
                            <div class="app-icon">
                              <i class="icon-google-play"></i>
                            </div>
                            <div class="app-content">
                              <span class="app-sub-title"><?php esc_html_e('Get it in','clcar') ?></span>
                              <h3 class="app-title"><?php esc_html_e('Google Play','clcar'); ?></h3>
                            </div>
                        </a>
                    <?php } ?>
                    <?php if(RDTheme::$options['footer_apps_banner_ios_btn']){ ?>
                        <a class="app-box apple" href="<?php echo esc_url(RDTheme::$options['footer_apps_ios_url']) ?>" target="_blank">
                            <div class="app-icon">
                              <i class="icon-apple"></i>
                            </div>
                            <div class="app-content">
                              <span class="app-sub-title"><?php esc_html_e('Download on the','clcar'); ?></span>
                              <h3 class="app-title"><?php esc_html_e('App Store','clcar'); ?></h3>
                            </div>
                        </a>
                    <?php } ?>
                </div>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="call-to-action-box call-to-action-style-1">
              <div class="figure-holder">
                <div class="shape-wrap-inner">
                  <div class="shape-1 wow fadeInDown animated" data-wow-delay="0.3s" data-wow-duration="1s">
                    <img class="motion-effects1" src="<?php echo esc_url( CLCAR_ASSETS_URL . 'img/apps-shape3.png' ); ?>" alt="<?php echo esc_attr('Shape', 'clcar'); ?>">
                  </div>
                  <div class="shape-2 wow fadeInRight animated" data-wow-delay="0.5s" data-wow-duration="1s">
                    <img class="motion-effects4" src="<?php echo esc_url( CLCAR_ASSETS_URL . 'img/apps-shape4.png' ); ?>" alt="<?php echo esc_attr('Shape', 'clcar'); ?>">
                  </div>
                  <div class="shape-3 wow fadeInUp animated" data-wow-delay="0.7s" data-wow-duration="1s">
                    <img class="motion-effects3" src="<?php echo esc_url( CLCAR_ASSETS_URL . 'img/apps-banner5.png' ); ?>" alt="<?php echo esc_attr('Shape 5', 'clcar'); ?>">
                  </div>
                </div>
                <?php if($footer_apps_banner_building_id){
                        echo wp_get_attachment_image( $footer_apps_banner_building_id, 'full' );
                    } else {?>
                        <img class="wow fadeInUp animated" data-wow-delay="0.3s" data-wow-duration="1.5s" src="<?php echo esc_url( CLCAR_ASSETS_URL . 'img/apps-banner6.png' ); ?>" alt="<?php echo esc_attr('Shape 6', 'clcar'); ?>">
                <?php } ?>
                
              </div>
            </div>
          </div>
        </div>
      </div>
</div>