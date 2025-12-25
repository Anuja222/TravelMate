<?php
namespace App\Validation;

class Validator {
    public function validateRequiredFields($data, $fields) {
        $errors = [];
        foreach ($fields as $field) {
            if (empty($data[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }
        return $errors;
    }

    public function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function validatePassword($password) {
        return strlen($password) >= 6;
    }
}