<?php
/**
 * Input Validation Class
 */

class Validator {
    private $errors = [];

    public function validateEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Invalid email format';
            return false;
        }
        return true;
    }

    public function validatePassword($password, $minLength = 8) {
        if (strlen($password) < $minLength) {
            $this->errors['password'] = "Password must be at least {$minLength} characters";
            return false;
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $this->errors['password'] = 'Password must contain uppercase letter';
            return false;
        }
        if (!preg_match('/[0-9]/', $password)) {
            $this->errors['password'] = 'Password must contain number';
            return false;
        }
        return true;
    }

    public function validateRequired($value, $fieldName) {
        if (empty(trim($value))) {
            $this->errors[$fieldName] = ucfirst($fieldName) . ' is required';
            return false;
        }
        return true;
    }

    public function validateUrl($url) {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $this->errors['url'] = 'Invalid URL format';
            return false;
        }
        return true;
    }

    public function validatePhoneNumber($phone) {
        $phone = preg_replace('/[^0-9+\-\s]/', '', $phone);
        if (!preg_match('/^[+]?[0-9]{7,15}$/', $phone)) {
            $this->errors['phone'] = 'Invalid phone number';
            return false;
        }
        return true;
    }

    public function validatePrice($price) {
        if (!is_numeric($price) || $price < 0) {
            $this->errors['price'] = 'Invalid price';
            return false;
        }
        return true;
    }

    public function validateInteger($value, $fieldName) {
        if (!is_numeric($value) || intval($value) != $value) {
            $this->errors[$fieldName] = $fieldName . ' must be an integer';
            return false;
        }
        return true;
    }

    public function validateCoordinates($latitude, $longitude) {
        if (!is_numeric($latitude) || !is_numeric($longitude)) {
            $this->errors['coordinates'] = 'Invalid coordinates';
            return false;
        }
        if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
            $this->errors['coordinates'] = 'Coordinates out of range';
            return false;
        }
        return true;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function hasErrors() {
        return !empty($this->errors);
    }

    public function sanitize($input) {
        if (is_array($input)) {
            return array_map([$this, 'sanitize'], $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}
