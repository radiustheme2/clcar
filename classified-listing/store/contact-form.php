<?php
/**
 * Store contact form
 *
 * @author     RadiusTheme
 * @package    classified-listing-store/templates
 * @version    1.2.31
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<div id="store-email-area">
	<?php do_action( 'rtcl_store_before_contact_form' ); ?>
    <form class="form" id="store-email-form">
        <div class="form-group">
            <span class="icon"><i class="icon-user3"></i></span>
            <input type="text" name="name" id="sc-name"
                   placeholder="<?php esc_attr_e( "Full name", "clcar" ); ?>"
                   class="form-control"
                   required>
            <div class="help-block"></div>
        </div>
        <div class="form-group">
            <span class="icon"><i class="icon-envelop"></i></span>
            <input type="email" name="email" id="sc-email"
                   placeholder="<?php esc_attr_e( "Email address", "clcar" ); ?>"
                   class="form-control" required>
            <div class="help-block"></div>
        </div>
        <div class="form-group">
            <span class="icon"><i class="icon-call"></i></span>
            <input type="text" name="phone"
                   placeholder="<?php esc_attr_e( "Phone number", "clcar" ); ?>"
                   id="sc-phone"
                   class="form-control">
            <div class="help-block"></div>
        </div>
        <div class="form-group">
                <span class="icon"><i class="icon-message"></i></span>
                <textarea rows="5" name="message" id="sc-message"
                            placeholder="<?php esc_attr_e( "Message", "clcar" ); ?>"
                            class="form-control" required></textarea>
            <div class="help-block"></div>
        </div>
        <div class="form-group">
            <div id="rtcl-store-contact-g-recaptcha" class="rtcl-g-recaptcha-wrap"></div>
        </div>
		<?php do_action( 'rtcl_store_contact_form' ); ?>
        <button class="btn btn-primary sc-submit">
			<?php esc_html_e( "Send Message", "clcar" ); ?>
        </button>
        <div class="rtcl-response"></div>
    </form>
	<?php do_action( 'rtcl_store_after_contact_form' ); ?>
</div>