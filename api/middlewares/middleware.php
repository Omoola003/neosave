<?php

namespace NeosaveWallet\API;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Middleware {
    /**
     * Verify API authentication token.
     *
     * @param WP_REST_Request $request
     * @return WP_Error|true
     */
    public static function verify_authentication($request) {
        $headers = $request->get_headers();
        
        if (!isset($headers['authorization'])) {
            return new \WP_Error('unauthorized', __('Missing authorization token.', 'neosave-wallet'), ['status' => 401]);
        }

        $token = sanitize_text_field($headers['authorization'][0]);
        
        if (!self::is_valid_token($token)) {
            return new \WP_Error('forbidden', __('Invalid token.', 'neosave-wallet'), ['status' => 403]);
        }

        return true;
    }

    /**
     * Validate token (dummy function - replace with actual validation logic).
     *
     * @param string $token
     * @return bool
     */
    private static function is_valid_token($token) {
        // Token validation logic (e.g., check in the database or JWT decoding)
        return $token === 'valid_api_token'; // Replace with real validation
    }
}
