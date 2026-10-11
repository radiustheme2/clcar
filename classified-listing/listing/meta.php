<?php
/**
 * Listing meta
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $listing;

if ( ! $listing->can_show_date() && ! $listing->can_show_user() && ! $listing->can_show_location() && ! $listing->can_show_views() ) {
	return;
}
?>
<ul class="entry-meta">
	<?php if ( $listing->can_show_user() ): ?>
        <li class="author">
            <i class="fas fa-user"></i><?php esc_html_e( 'by ', 'clcar' ); ?>
			<?php if ( $listing->can_add_user_link() && ! is_author() ) : ?>
				<a href="<?php echo esc_url( $listing->get_the_author_url() ); ?>"><?php $listing->the_author(); ?></a>
			<?php else : ?>
				<?php $listing->the_author(); ?>
			<?php endif; ?>
        </li>
	<?php endif; ?>
	<?php if ( $listing->has_location() && $listing->can_show_location() ): ?>
        <li><i class="icon-location"></i><?php $listing->the_locations(); ?></li>
	<?php endif; ?>
	<?php if ( $listing->can_show_date() ): ?>
        <li class="updated"><i class="far fa-clock"></i><?php $listing->the_time(); ?></li>
	<?php endif; ?>
	<?php if ( $listing->can_show_views() ): ?>
        <li class="rt-views">
            <i class="far fa-eye"></i>
            <?php echo esc_html( sprintf( _n( "%s view", "%s views", $listing->get_view_counts(), 'clcar' ),
				number_format_i18n( $listing->get_view_counts() ) ) ); ?>
        </li>
	<?php endif; ?>
</ul>
