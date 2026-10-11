<?php
/**
 * Login Form
 *
 * @package classified-listing/Templates
 * @version 1.0.0
 */

use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Link;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

Functions::print_notices();
$separate_class=Functions::is_registration_page_separate() ? 'separate':'no-separate';
?>
<?php do_action( 'rtcl_before_user_login_form' ); ?>
<div class="row <?php echo esc_attr($separate_class); ?>" id="rtcl-user-login-wrapper">
    <div class="col-md-<?php echo ( Functions::is_registration_enabled() && ! Functions::is_registration_page_separate() ) ? "6" : '12'; ?> rtcl-login-form-wrap">
        <h2><?php esc_html_e( 'Login', 'clcar' ); ?></h2>
        <form id="rtcl-login-form" class="form-horizontal" method="post">
			<?php do_action( 'rtcl_login_form_start' ); ?>
            <div class="form-group">
                <label for="rtcl-user-login" class="control-label">
					<?php esc_html_e( 'Username or E-mail', 'clcar' ); ?>
                    <strong class="rtcl-required">*</strong>
                </label>
                <input type="text" name="username" autocomplete="username"
                       value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"
                       id="rtcl-user-login" class="form-control" required/>
            </div>

            <div class="form-group">
                <label for="rtcl-user-pass" class="control-label">
					<?php esc_html_e( 'Password', 'clcar' ); ?>
                    <strong class="rtcl-required">*</strong>
                </label>
                <input type="password" name="password" id="rtcl-user-pass" autocomplete="current-password"
                       class="form-control" required/>
            </div>

			<?php do_action( 'rtcl_login_form' ); ?>

            <div class="form-group">
                <div id="rtcl-login-g-recaptcha" class="mb-2"></div>
                <div id="rtcl-login-g-recaptcha-message"></div>
            </div>

            <div class="form-group d-flex align-items-center">

                <button type="submit" name="rtcl-login" class="btn btn-primary" value="login">
					<?php esc_html_e( 'Login', 'clcar' ); ?>
                </button>
                <div class="form-check">
                    <input type="checkbox" name="rememberme" id="rtcl-rememberme" value="forever">
                    <label class="form-check-label" for="rtcl-rememberme">
						<?php esc_html_e( 'Remember Me', 'clcar' ); ?>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <p class="rtcl-forgot-password">
					<?php if ( Functions::is_registration_enabled() && Functions::is_registration_page_separate() ): ?>
                        <a href="<?php echo esc_url( Link::get_registration_page_link() ); ?>"><?php esc_html_e( 'Register',
								'clcar' ); ?></a><span>|</span>
					<?php endif; ?>
                    <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgot your password?',
							'clcar' ); ?></a>
                </p>
            </div>
			<?php do_action( 'rtcl_login_form_end' ); ?>
        </form>
    </div>
	<?php if ( Functions::is_registration_enabled() && ! Functions::is_registration_page_separate() ): ?>
        <div class="col-md-6 rtcl-registration-form-wrap">

            <h2><?php esc_html_e( 'Register', 'clcar' ); ?></h2>

            <form id="rtcl-register-form" class="form-horizontal" method="post">

				<?php do_action( 'rtcl_register_form_start' ); ?>

                <div class="form-group">
                    <label for="rtcl-reg-username" class="control-label">
						<?php esc_html_e( 'Username', 'clcar' ); ?>
                        <strong class="rtcl-required">*</strong>
                    </label>
                    <input type="text" name="username"
                           value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"
                           autocomplete="username" id="rtcl-reg-username" class="form-control" required/>
                    <span class="help-block"><?php esc_html_e( 'Username cannot be changed.', 'clcar' ); ?></span>
                </div>

                <div class="form-group">
                    <label for="rtcl-reg-email" class="control-label">
						<?php esc_html_e( 'Email address', 'clcar' ); ?>
                        <strong class="rtcl-required">*</strong>
                    </label>
                    <input type="email" name="email"
                           value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>"
                           autocomplete="email" id="rtcl-reg-email" class="form-control" required/>
                </div>

                <div class="form-group">
                    <label for="rtcl-reg-password" class="control-label">
						<?php esc_html_e( 'Password', 'clcar' ); ?>
                        <strong class="rtcl-required">*</strong>
                    </label>
                    <input type="password" name="password" id="rtcl-reg-password" autocomplete="new-password"
                           class="form-control rtcl-password" required/>
                </div>

				<?php do_action( 'rtcl_register_form' ); ?>

                <div class="form-group">
                    <div id="rtcl-registration-g-recaptcha" class="mb-2"></div>
                    <div id="rtcl-registration-g-recaptcha-message"></div>
                    <input type="submit" name="rtcl-register" class="btn btn-primary"
                           value="<?php esc_html_e( 'Register', 'clcar' ); ?>"/>
                </div>
				<?php do_action( 'rtcl_register_form_end' ); ?>
            </form>
        </div>
	<?php endif; ?>
</div>
<?php do_action( 'rtcl_after_user_login_form' ); ?>
