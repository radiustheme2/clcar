<?php
/**
 * new listing email notification to owner
 * This template can be overridden by copying it to yourtheme/classified-listing/emails/new-post-notification-user.php
 *
 * @var RtclEmail $email
 * @var array     $data
 * @var Listing   $listing
 * @author        RadiusTheme
 * @package       ClassifiedListing/Templates/Emails
 * @version       1.3.0
 *
 */

use Rtcl\Models\Listing;
use Rtcl\Models\RtclEmail;
use Rtcl\Helpers\Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @hooked RtclEmails::email_header() Output the email header
 */
do_action( 'rtcl_email_header', $email ); ?>
    <p><?php esc_html_e( 'Hi Administrator,', 'clcar' ); ?></p>
    <p><?php echo wp_kses_post( sprintf( __( 'A listing on your website %s received a message.', 'clcar' ), esc_html( Functions::get_blogname() ) ) ); ?></p>
    <p><?php echo wp_kses_post( sprintf( __( '<strong>Listing :</strong> <a href="%s">%s</a>', 'clcar' ), esc_url( $listing->get_the_permalink() ), esc_html( $listing->get_the_title() ) ) ); ?></p>
    <p><?php echo wp_kses_post( sprintf( __( '<strong>Sender name:</strong> %s', 'clcar' ), esc_html( $data['name'] ) ) ); ?></p>
    <p><?php echo wp_kses_post( sprintf( __( '<strong>Sender email:</strong> %s', 'clcar' ), esc_html( $data['email'] ) ) ); ?></p>
    <p><?php echo wp_kses_post( sprintf( __( '<strong>Sender phone:</strong> %s', 'clcar' ), esc_html( $data['phone'] ) ) ); ?></p>
    <p><?php echo wp_kses_post( sprintf( __( '<strong>Sender message:</strong> %s', 'clcar' ), esc_html( $data['message'] ) ) ); ?></p>
<?php

/**
 * @hooked RtclEmails::email_footer() Output the email footer
 */
do_action( 'rtcl_email_footer', $email );
