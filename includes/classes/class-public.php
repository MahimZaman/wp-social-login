<?php

namespace WPSL;

defined('ABSPATH') || exit;

class Frontend
{
    public function register()
    {
        add_action('wp_enqueue_scripts', [$this, 'public_scripts']);
    }

    public function public_scripts()
    {
        wp_enqueue_style('wpsl-remixicon', 'https://cdn.jsdelivr.net/npm/remixicon@4.7.0/fonts/remixicon.css', [], WPSL_VER);
        wp_enqueue_style('wpsl-style', WPSL_URL . 'assets/public/main.css', [], WPSL_VER);
        wp_enqueue_script('wpsl-script', WPSL_URL . 'assets/public/main.js', [], WPSL_VER, true);
    }
}
