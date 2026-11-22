<?php

namespace WPSL;

defined('ABSPATH') || exit;

class Google
{
    protected static function client_id()
    {
        return get_option('wpsl_google_client_id');
    }

    protected static function client_secret()
    {
        return get_option('wpsl_google_client_secret');
    }

    public static function auth_url()
    {
        return add_query_arg([
            'client_id'     => self::client_id(),
            'redirect_uri'  => self::redirect_url(),
            'response_type' => 'code',
            'scope'         => 'email profile',
            'prompt'        => 'select_account',
            'state'         => wp_create_nonce('google_login'),
        ], 'https://accounts.google.com/o/oauth2/v2/auth');
    }

    public static function redirect_url()
    {
        return admin_url('admin-ajax.php?action=wpsl_callback');
    }

    public static function get_user($code)
    {
        $token = wp_remote_post('https://oauth2.googleapis.com/token', [
            'body'   => [
                'client_id'     => self::client_id(),
                'client_secret' => self::client_secret(),
                'code'          => $code,
                'grant_type'    => 'authorization_code',
                'redirect_uri'  => self::redirect_url()
            ]
        ]);

        $token = json_decode(wp_remote_retrieve_body($token), true);

        $profile = wp_remote_get(
            'https://www.googleapis.com/oauth2/v2/userinfo',
            ['headers' => [
                'Authorization' => 'Bearer ' . $token['access_token']
            ]]
        );

        return json_decode(wp_remote_retrieve_body($profile), true);
    }
}

