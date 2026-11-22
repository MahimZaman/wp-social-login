<?php

namespace WPSL;

defined('ABSPATH') || exit;

class Auth
{
    public function __construct()
    {
        add_action('woocommerce_login_form_start', [$this, 'render_buttons']);
        add_action('woocommerce_register_form_start', [$this, 'render_buttons']);

        add_action('wp_ajax_nopriv_wpsl_callback', [$this, 'handle_callback']);
        add_action('wp_ajax_wpsl_callback', [$this, 'handle_callback']);
    }

    public function render_buttons()
    {
?>
        <div class="wpsl-authentication-wrap">
            <a href="<?php echo esc_url(Google::auth_url()); ?>" class="wpsl-btn wpsl-google">
                <span class="wpsl-auth-icon">
                    <!-- <i class="ri-google-fill"></i> -->
                    <img src="<?php echo WPSL_URL . 'assets/images/google-logo.png'; ?>">
                </span>
                <span class="wpsl-auth-text">
                    <?php _e('Continuar con Google', WPSL_TEXTDOMAIN); ?>
                </span>
            </a>
        </div>
        <span class="wpsl-divider">
            <span><?php _e('O', WPSL_TEXTDOMAIN); ?></span>
        </span>
<?php
    }

    public function handle_callback()
    {
        if (empty($_GET['code'])) {
            wp_die('Invalid Callback');
        }

        $provider = 'google';

        $user_data = match ($provider) {
            'google'    => Google::get_user($_GET['code']),
            default     => null,
        };

        if (empty($user_data['email'])) {
            wp_die('Email permission required');
        }

        User::login_or_create($user_data);

        wp_safe_redirect(wc_get_page_permalink('myaccount'));
        exit;
    }
}
