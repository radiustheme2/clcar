<?php
/**
 * @author    RadiusTheme
 * @version       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
		</div>  <!-- Swiper Wrapper end  -->

		</div> <!-- swiper-container end  -->
		<?php if ( $settings['slider_nav'] ) { ?>
			<!-- If we need navigation buttons -->
            <div class="swiper-navigation">
                <span class="rtcl-slider-btn button-left icon-left"></span>
                <span class="rtcl-slider-btn button-right icon-right"></span>
            </div>
		<?php } ?>
		<?php if ( $settings['slider_dots'] ) { ?>
			<!-- If we need pagination -->
			<div class="rtcl-slider-pagination"></div>
		<?php } ?>

</div>

