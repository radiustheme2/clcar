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

?>
<section class="no-results not-found">
	<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
        <p>
			<?php echo esc_html__( 'Ready to publish your first post?', 'clcar' ) ?>
            <a href="<?php echo esc_url( admin_url( 'post-new.php' ) ) ?>"><?php echo esc_html__( 'Get started here', 'clcar' ) ?></a>
        </p>
	<?php elseif ( is_search() ) : ?>
        <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'clcar' ); ?></p>
		<?php get_search_form(); ?>
	<?php else : ?>
        <p><?php esc_html_e( "It seems we can't find what you're looking for. Perhaps searching can help.", 'clcar' ); ?></p>
		<?php get_search_form(); ?>
	<?php endif; ?>
</section>