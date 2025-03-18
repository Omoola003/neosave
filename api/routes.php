<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Neosave_Wallet_Routes {
    
    public static function register_routes() {
        add_action( 'rest_api_init', function () {
            register_rest_route( 'neosave-wallet/v1', '/balance', [
                'methods'  => 'GET',
                'callback' => [ 'Neosave_Wallet_Controller', 'get_balance' ],
                'permission_callback' => '__return_true', // Modify as needed
            ]);
            
            register_rest_route( 'neosave-wallet/v1', '/deposit', [
                'methods'  => 'POST',
                'callback' => [ 'Neosave_Wallet_Controller', 'deposit' ],
                'permission_callback' => [ 'Neosave_Wallet_Controller', 'validate_request' ],
            ]);
            
            register_rest_route( 'neosave-wallet/v1', '/withdraw', [
                'methods'  => 'POST',
                'callback' => [ 'Neosave_Wallet_Controller', 'withdraw' ],
                'permission_callback' => [ 'Neosave_Wallet_Controller', 'validate_request' ],
            ]);
            
            register_rest_route( 'neosave-wallet/v1', '/transactions', [
                'methods'  => 'GET',
                'callback' => [ 'Neosave_Wallet_Controller', 'get_transactions' ],
                'permission_callback' => [ 'Neosave_Wallet_Controller', 'validate_request' ],
            ]);
        });
    }
}

Neosave_Wallet_Routes::register_routes();
