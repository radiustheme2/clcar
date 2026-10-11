<?php
/**
 * @var array[] $images
 * @var array[] $videos
 * @var string  $video_url
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Functions;

global $listing;



$thumbSize ='full';

$total_gallery_image  = count( $images );
$total_gallery_videos = count( $videos );
$total_gallery_item   = $total_gallery_image + $total_gallery_videos;
$isSliderEnable       = Functions::is_gallery_slider_enabled();
if ( $total_gallery_item ) :

?>


<?php if ($isSliderEnable){ ?>
    <div id="rtcl-slider-wrapper" class="rtcl-slider-wrapper mb-4" data-options="">
        <!-- Slider -->
        <div class="rtcl-slider<?php echo esc_attr( $isSliderEnable ? '' : ' off' ) ?>">
            <div class="swiper-wrapper">
				<?php
				if ( $total_gallery_videos ) {
					foreach ( $videos as $index => $video_url ) { ?>
                        <div class="swiper-slide rtcl-slider-item rtcl-slider-video-item">
                            <iframe class="rtcl-lightbox-iframe"
                                    src="<?php echo esc_url(Functions::get_sanitized_embed_url( $video_url )) ?>"
                                    style="width: 100%; height: 400px; margin: 0;padding: 0; background-color: #000"
                                    frameborder="0" webkitAllowFullScreen
                                    mozallowfullscreen allowFullScreen></iframe>
                        </div>
						<?php
					}
				}
				if ( $total_gallery_image ) {
					foreach ( $images as $index => $image ) :
						$image_attributes = wp_get_attachment_image_src( $image->ID, 'rtcl-gallery' );
						$image_full = wp_get_attachment_image_src( $image->ID, 'full' ); ?>
                        <div class="swiper-slide rtcl-slider-item">
                            <img src="<?php echo esc_html( $image_attributes[0] ); ?>"
                                 data-src="<?php echo esc_attr( $image_full[0] ) ?>"
                                 data-large_image="<?php echo esc_attr( $image_full[0] ) ?>"
                                 data-large_image_width="<?php echo esc_attr( $image_full[1] ) ?>"
                                 data-large_image_height="<?php echo esc_attr( $image_full[2] ) ?>"
                                 alt="<?php echo esc_attr( $listing->get_the_title() ); ?>"
                                 data-caption="<?php echo esc_attr( wp_get_attachment_caption( $image->ID ) ); ?>"
                                 class="rtcl-responsive-img"/>
                        </div>
					<?php endforeach;
				} ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
		<?php if ( $isSliderEnable && $total_gallery_item > 1 ): ?>
            <!-- Slider nav -->
            <div class="rtcl-slider-nav">
                <div class="swiper-wrapper">
					<?php
					if ( $total_gallery_videos ) {
						foreach ( $videos as $index => $video_url ) { ?>
                            <div class="swiper-slide rtcl-slider-thumb-item rtcl-slider-video-thumb">
                                <img src="<?php echo esc_url(Functions::get_embed_video_thumbnail_url( $video_url )) ?>"
                                     class="rtcl-gallery-thumbnail"
                                     alt="<?php echo esc_attr__( 'Preview Thumbnail', 'classified-listing' ); ?>"/>
                            </div>
							<?php
						}
					}
					if ( $total_gallery_image ) {
						foreach ( $images as $index => $image ) : ?>
                            <div class="swiper-slide rtcl-slider-thumb-item">
								<?php echo wp_get_attachment_image( $image->ID, 'rtcl-gallery-thumbnail', false, [ 'alt' => esc_attr( $listing->get_the_title() ) ] ); ?>
                            </div>
						<?php endforeach;
					} ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
		<?php endif; ?>
    </div>
<?php } else {

    ?>
    <div id="rtcl-slider-wrapper" class="rtcl-slider-wrapper">
        <div class="listing-gallery">
            <?php
                $number = $total_gallery_image - 4;
                $counter = 0;
                if ( $total_gallery_image ) {
                    foreach ( $images as $index => $image ) :
	                    ++$counter;
	                    if ($counter < 2) {
		                    $item_count = 'one';
	                    } elseif ($counter < 3 ) {
		                    $item_count = 'two';
	                    } elseif ($counter < 4) {
		                    $item_count = 'three';
	                    } elseif ($counter < 5) {
		                    $item_count = 'four';
	                    } else {
		                    $item_count = 'more';
	                    }
                        $image_attributes = wp_get_attachment_image_src( $image->ID, $thumbSize );
                        $image_full = wp_get_attachment_image_src( $image->ID, 'large' );
                        ?>
                        <div class="rtcl-img-item blocks-gallery-item item-<?php echo esc_attr( $item_count ); ?>">
                            <a href="<?php echo esc_url( $image_full[0] ); ?>">
                                <img src="<?php echo esc_url( $image_attributes[0] ); ?>"
                                        alt="<?php esc_attr( $listing->the_title() ); ?>"
                                        data-caption="<?php echo esc_attr( wp_get_attachment_caption( $image->ID ) ); ?>"
                                        class="rtcl-responsive-img"/>
                                    <?php
                                        if( ! empty( $number ) && $counter === 4 ) {
                                            echo '<span>+'.esc_html( $number ).'</span>';
                                        }
                                    ?>
                            </a>
                        </div>
                    <?php endforeach;
                }
            ?>
        </div>
    </div>
<?php }

endif;