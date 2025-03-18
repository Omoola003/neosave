<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class NeoSave_Wallet_API_Helpers {
    
    /**
     * Send JSON Response
     * @param array $data
     * @param int $status
     */
    public static function send_json_response($data, $status = 200) {
        wp_send_json($data, $status);
    }
    
    /**
     * Validate Required Fields in Request
     * @param array $required_fields
     * @param array $request_data
     * @return array
     */
    public static function validate_required_fields($required_fields, $request_data) {
        $missing_fields = [];
        
        foreach ($required_fields as $field) {
            if (empty($request_data[$field])) {
                $missing_fields[] = $field;
            }
        }
        
        if (!empty($missing_fields)) {
            return [
                'success' => false,
                'message' => 'Missing required fields: ' . implode(', ', $missing_fields)
            ];
        }
        
        return ['success' => true];
    }
    
    /**
     * Generate Secure Token
     * @param int $length
     * @return string
     */
    public static function generate_token($length = 32) {
        return bin2hex(random_bytes($length));
    }
    
    /**
     * Format Currency
     * @param float $amount
     * @param string $currency
     * @return string
     */
    public static function format_currency($amount, $currency = 'USD') {
        return sprintf('%s %.2f', strtoupper($currency), $amount);
    }
}

?>
