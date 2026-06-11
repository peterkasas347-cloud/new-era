<?php
/**
 * Helper Functions
 */

class Helper {
    /**
     * Generate unique token
     */
    public static function generateToken($length = 32) {
        return bin2hex(random_bytes($length));
    }

    /**
     * Hash password
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Verify password
     */
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * Format price
     */
    public static function formatPrice($price) {
        return CURRENCY_SYMBOL . number_format($price, 2);
    }

    /**
     * Format date
     */
    public static function formatDate($date, $format = 'M d, Y') {
        return date($format, strtotime($date));
    }

    /**
     * Time ago
     */
    public static function timeAgo($date) {
        $timestamp = strtotime($date);
        $difference = time() - $timestamp;

        if ($difference < 60) {
            return "just now";
        } elseif ($difference < 3600) {
            $minutes = floor($difference / 60);
            return $minutes . " minute" . ($minutes > 1 ? "s" : "") . " ago";
        } elseif ($difference < 86400) {
            $hours = floor($difference / 3600);
            return $hours . " hour" . ($hours > 1 ? "s" : "") . " ago";
        } elseif ($difference < 604800) {
            $days = floor($difference / 86400);
            return $days . " day" . ($days > 1 ? "s" : "") . " ago";
        } else {
            return self::formatDate($date);
        }
    }

    /**
     * Redirect to URL
     */
    public static function redirect($url) {
        header("Location: " . $url);
        exit();
    }

    /**
     * Get user avatar placeholder
     */
    public static function getAvatarUrl($email) {
        $hash = md5(strtolower(trim($email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=identicon";
    }

    /**
     * Truncate text
     */
    public static function truncate($text, $length = 100, $suffix = '...') {
        if (strlen($text) <= $length) {
            return $text;
        }
        return substr($text, 0, $length) . $suffix;
    }

    /**
     * Convert JSON string to array
     */
    public static function jsonDecode($json, $assoc = true) {
        if (is_string($json)) {
            return json_decode($json, $assoc);
        }
        return $json;
    }

    /**
     * Convert array to JSON string
     */
    public static function jsonEncode($data) {
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Upload file
     */
    public static function uploadFile($file, $directory = 'uploads/') {
        if (!isset($file['tmp_name']) || $file['size'] == 0) {
            return false;
        }

        if ($file['size'] > MAX_UPLOAD_SIZE) {
            return false;
        }

        if (!in_array($file['type'], ALLOWED_IMAGE_TYPES)) {
            return false;
        }

        $filename = uniqid() . '_' . basename($file['name']);
        $upload_path = UPLOAD_DIR . $directory;

        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        if (move_uploaded_file($file['tmp_name'], $upload_path . $filename)) {
            return $directory . $filename;
        }

        return false;
    }

    /**
     * Delete file
     */
    public static function deleteFile($filepath) {
        $full_path = UPLOAD_DIR . $filepath;
        if (file_exists($full_path)) {
            return unlink($full_path);
        }
        return false;
    }

    /**
     * Get stars rating HTML
     */
    public static function getStars($rating) {
        $rating = min(5, max(0, (int)$rating));
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            $stars .= $i <= $rating ? '★' : '☆';
        }
        return $stars;
    }
}
