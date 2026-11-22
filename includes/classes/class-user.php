<?php

namespace WPSL;

defined('ABSPATH') || exit;

class User
{
    public static function login_or_create($data)
    {
        $user = get_user_by('email', $data['email']);

        if (!$user) {
            $user_id = wp_create_user(
                $data['email'],
                wp_generate_password(),
                $data['email']
            );

            update_user_meta($user_id, 'wpsl_provider', $data);
            $user = get_user_by('id', $user_id);
        }

        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID);
    }
}
