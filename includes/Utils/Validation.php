<?php
namespace VigyapanamAffiliate\Utils;

class Validation {
    public function validate_profile_data($data) {
        $errors = [];

        // Validate name
        if (empty($data['name']) || strlen($data['name']) < 3) {
            $errors['name'] = __('Name must be at least 3 characters long', 'vigyapanam-affiliate');
        }

        // Validate email
        if (empty($data['email']) || !is_email($data['email'])) {
            $errors['email'] = __('Please enter a valid email address', 'vigyapanam-affiliate');
        }

        // Validate mobile number (Indian format)
        if (empty($data['mobile']) || !preg_match('/^[6-9]\d{9}$/', $data['mobile'])) {
            $errors['mobile'] = __('Please enter a valid Indian mobile number', 'vigyapanam-affiliate');
        }

        // Validate followers count
        if (empty($data['followers']) || !is_numeric($data['followers']) || $data['followers'] < 0) {
            $errors['followers'] = __('Please enter a valid number of followers', 'vigyapanam-affiliate');
        }

        // Validate social media URLs
        $social_fields = ['linkedin', 'instagram', 'facebook', 'youtube'];
        foreach ($social_fields as $field) {
            if (!empty($data[$field]) && !filter_var($data[$field], FILTER_VALIDATE_URL)) {
                $errors[$field] = sprintf(
                    __('Please enter a valid %s URL', 'vigyapanam-affiliate'),
                    ucfirst($field)
                );
            }
        }

        // Validate UPI ID
        if (empty($data['upi']) || !preg_match('/^[\w\.\-]+@[\w\-]+$/', $data['upi'])) {
            $errors['upi'] = __('Please enter a valid UPI ID', 'vigyapanam-affiliate');
        }

        return empty($errors) ? true : $errors;
    }

    public function validate_withdrawal_request($data) {
        $errors = [];

        // Validate amount
        if (empty($data['amount']) || !is_numeric($data['amount']) || $data['amount'] <= 0) {
            $errors['amount'] = __('Please enter a valid withdrawal amount', 'vigyapanam-affiliate');
        }

        // Validate payment method
        $valid_methods = ['upi', 'bank'];
        if (empty($data['payment_method']) || !in_array($data['payment_method'], $valid_methods)) {
            $errors['payment_method'] = __('Please select a valid payment method', 'vigyapanam-affiliate');
        }

        return empty($errors) ? true : $errors;
    }

    public function validate_client_data($data) {
        $errors = [];

        // Validate client name
        if (empty($data['client_name']) || strlen($data['client_name']) < 3) {
            $errors['client_name'] = __('Client name must be at least 3 characters long', 'vigyapanam-affiliate');
        }

        // Validate revenue model
        if (empty($data['revenue_model'])) {
            $errors['revenue_model'] = __('Please specify a revenue model', 'vigyapanam-affiliate');
        }

        // Validate website URL
        if (empty($data['website']) || !filter_var($data['website'], FILTER_VALIDATE_URL)) {
            $errors['website'] = __('Please enter a valid website URL', 'vigyapanam-affiliate');
        }

        // Validate about section
        if (empty($data['about']) || strlen($data['about']) < 50) {
            $errors['about'] = __('About section must be at least 50 characters long', 'vigyapanam-affiliate');
        }

        // Validate contact person
        if (empty($data['contact_person'])) {
            $errors['contact_person'] = __('Please specify a contact person', 'vigyapanam-affiliate');
        }

        // Validate payment terms
        if (empty($data['payment_terms'])) {
            $errors['payment_terms'] = __('Please specify payment terms', 'vigyapanam-affiliate');
        }

        return empty($errors) ? true : $errors;
    }
}