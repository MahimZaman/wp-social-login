<?php

namespace WPSL;

defined('ABSPATH') || exit;

class Admin
{
    public function register()
    {
        add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function admin_scripts()
    {
        wp_enqueue_style('wpsl-remixicon', 'https://cdn.jsdelivr.net/npm/remixicon@4.7.0/fonts/remixicon.css', [], WPSL_VER);
        wp_enqueue_style('wpsl-style', WPSL_URL . 'assets/admin/admin.css', [], WPSL_VER);
        wp_enqueue_script('wpsl-script', WPSL_URL . 'assets/admin/admin.js', [], WPSL_VER, true);
    }

    public function add_menu()
    {
        add_menu_page(
            'WP Social Login',
            'WP Social Login',
            'manage_options',
            'wpsl-settings',
            [$this, 'render_page'],
            'dashicons-shield-alt',
            58
        );
    }

    public function register_settings()
    {

        register_setting('wpsl_settings', 'wpsl_google_client_id');
        register_setting('wpsl_settings', 'wpsl_google_client_secret');

        register_setting('wpsl_settings', 'wpsl_github_client_id');
        register_setting('wpsl_settings', 'wpsl_github_client_secret');
    }

    public function enqueue_assets($hook)
    {
        if ($hook !== 'toplevel_page_wpsl-settings') {
            return;
        }

        wp_enqueue_style(
            'wpsl-admin',
            plugin_dir_url(__FILE__) . 'assets/admin.css',
            [],
            '1.0.0'
        );
    }

    public function render_page()
    {
?>
        <div class="wpsl-admin-wrap">
            <h1><?php echo __('WP Social Login', WPSL_TEXTDOMAIN) ?></h1>
            <p class="wpsl-description">
                <?php _e('Configure Google OAuth credentials for user login.', WPSL_TEXTDOMAIN); ?>
            </p>

            <form method="post" action="options.php">
                <?php settings_fields('wpsl_settings'); ?>

                <div class="wpsl-card">
                    <h2><?php _e('Google OAuth Credentials', WPSL_TEXTDOMAIN); ?></h2>

                    <div class="wpsl-field">
                        <label for="wpsl_google_client_id"><?php _e('Client ID', WPSL_TEXTDOMAIN); ?></label>
                        <input
                            type="text"
                            id="wpsl_google_client_id"
                            name="wpsl_google_client_id"
                            value="<?php echo esc_attr(get_option('wpsl_google_client_id')); ?>"
                            placeholder="1234567890-xxxx.apps.googleusercontent.com" />
                    </div>

                    <div class="wpsl-field">
                        <label for="wpsl_google_client_secret"><?php _e('Client Secret', WPSL_TEXTDOMAIN); ?></label>
                        <input
                            type="password"
                            id="wpsl_google_client_secret"
                            name="wpsl_google_client_secret"
                            value="<?php echo esc_attr(get_option('wpsl_google_client_secret')); ?>"
                            placeholder="GOCSPX-xxxxxxxxxxxxxxxx" />
                    </div>

                    <div class="wpsl-help">
                        <strong><?php _e('Redirect URI:', WPSL_TEXTDOMAIN); ?></strong>
                        <code><?php echo esc_html(admin_url('admin-ajax.php?action=wpsl_callback')); ?></code>
                    </div>
                </div>

                <div class="wpsl-actions">
                    <button type="submit" class="wpsl-btn-primary">
                        <?php _e('Save Settings', WPSL_TEXTDOMAIN); ?>
                    </button>
                </div>
            </form>
        </div>
<?php
    }
}
