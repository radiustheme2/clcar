<?php
/**
 * new listing email notification to owner
 * This template can be overridden by copying it to yourtheme/classified-listing/emails/new-post-notification-user.php
 *
 * @var RtclEmail $email
 * @var Listing   $listing
 * @var array     $data
 * @author        RadiusTheme
 * @package       ClassifiedListing/Templates/Emails
 * @version       1.3.0
 *
 */

use Rtcl\Models\Listing;
use Rtcl\Models\RtclEmail;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @hooked RtclEmails::email_header() Output the email header
 */
do_action( 'rtcl_email_header', $email ); ?>
    <p><?php printf( esc_html__( 'Hi %s,', 'clcar' ), esc_html( $listing->get_owner_name() ) ); ?></p>
    <p><?php echo wp_kses_post( sprintf( __( 'You have received a reply from your listing at <a href="%s">%s</a>', 'clcar' ),
			esc_url( $listing->get_the_permalink() ),
			esc_html( $listing->get_the_title() ) ) ); ?></p>
    <p><?php echo wp_kses_post( sprintf( __( '<strong>Name:</strong> %s', 'clcar' ), esc_html( $data['name'] ) ) ); ?></p>
    <p><?php echo wp_kses_post( sprintf( __( '<strong>Email:</strong> %s', 'clcar' ), esc_html( $data['email'] ) ) ); ?></p>
    <p><?php echo wp_kses_post( sprintf( __( '<strong>Phone:</strong> %s', 'clcar' ), esc_html( $data['phone'] ) ) ); ?></p>
    <p><?php echo wp_kses_post( sprintf( __( '<strong>Message:</strong> %s', 'clcar' ), esc_html( $data['message'] ) ) ); ?></p>
<?php

/**
 * @hooked RtclEmails::email_footer() Output the email footer
 */
do_action( 'rtcl_email_footer', $email );
