<?php

/**
 * SessionHelper - Centralized session management for TravelMate
 * 
 * Provides consistent session access, authentication checks, and CSRF
 * protection across ALL controllers. Replaces direct $_SESSION access
 * to prevent format mismatches between controllers.
 * 
 * Session format (set by AuthController):
 *   $_SESSION['user'] = [
 *       'id' => int,
 *       'email' => string,
 *       'first_name' => string,
 *       'last_name' => string,
 *       'role' => string,      // 'traveller', 'accommodation', 'transport', 'admin'
 *       'logged_in' => bool,
 *       'login_time' => int
 *   ];
 */
class SessionHelper
{
    /**
     * Ensure session is started
     */
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ─── Authentication Checks ───────────────────────────────────────

    /**
     * Check if any user is logged in
     */
    public static function isLoggedIn(): bool
    {
        self::start();
        return isset($_SESSION['user']) 
            && is_array($_SESSION['user']) 
            && !empty($_SESSION['user']['id'])
            && !empty($_SESSION['user']['role']);
    }

    /**
     * Check if current user is an admin
     */
    public static function isAdmin(): bool
    {
        return self::isLoggedIn() && $_SESSION['user']['role'] === 'admin';
    }

    /**
     * Require admin authentication for page routes (redirects to login)
     */
    public static function requireAdmin()
    {
        if (!self::isAdmin()) {
            header('Location: ' . (defined('ROOT') ? ROOT : '') . '/login');
            exit;
        }
    }

    /**
     * Require admin authentication for API routes (returns JSON 401)
     * @return bool True if admin, false if not (response already sent)
     */
    public static function requireAdminApi(): bool
    {
        if (!self::isAdmin()) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
            return false;
        }
        return true;
    }

    /**
     * Require any authenticated user for page routes
     */
    public static function requireAuth()
    {
        if (!self::isLoggedIn()) {
            header('Location: ' . (defined('ROOT') ? ROOT : '') . '/login');
            exit;
        }
    }

    /**
     * Require any authenticated user for API routes
     * @return bool True if logged in, false if not (response already sent)
     */
    public static function requireAuthApi(): bool
    {
        if (!self::isLoggedIn()) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'Unauthorized - Please login']);
            return false;
        }
        return true;
    }

    // ─── User Data Accessors ─────────────────────────────────────────

    /**
     * Get current user's ID
     * @return int|null
     */
    public static function getUserId(): ?int
    {
        if (!self::isLoggedIn()) return null;
        return (int)$_SESSION['user']['id'];
    }

    /**
     * Get current user's role
     * @return string|null
     */
    public static function getUserRole(): ?string
    {
        if (!self::isLoggedIn()) return null;
        return $_SESSION['user']['role'];
    }

    /**
     * Get current user's full name
     * @return string
     */
    public static function getUserName(): string
    {
        if (!self::isLoggedIn()) return 'Guest';
        $first = $_SESSION['user']['first_name'] ?? '';
        $last = $_SESSION['user']['last_name'] ?? '';
        return trim($first . ' ' . $last) ?: 'User';
    }

    /**
     * Get current user's email
     * @return string|null
     */
    public static function getUserEmail(): ?string
    {
        if (!self::isLoggedIn()) return null;
        return $_SESSION['user']['email'] ?? null;
    }

    /**
     * Get full user session array
     * @return array|null
     */
    public static function getUser(): ?array
    {
        if (!self::isLoggedIn()) return null;
        return $_SESSION['user'];
    }

    // ─── CSRF Protection ─────────────────────────────────────────────

    /**
     * Generate or retrieve CSRF token
     * @return string
     */
    public static function getCsrfToken(): string
    {
        self::start();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Validate a CSRF token
     * @param string $token Token from client request
     * @return bool
     */
    public static function validateCsrfToken(string $token): bool
    {
        self::start();
        return !empty($_SESSION['csrf_token']) 
            && !empty($token) 
            && hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Validate CSRF for API endpoints — sends JSON error and returns false if invalid
     * @param array|null $input The decoded JSON input containing 'csrf_token'
     * @return bool True if valid, false if not (response already sent)
     */
    public static function requireCsrfApi(?array $input): bool
    {
        $token = $input['csrf_token'] ?? '';
        if (!self::validateCsrfToken($token)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Invalid security token. Please refresh the page.']);
            return false;
        }
        return true;
    }

    // ─── Flash Messages ──────────────────────────────────────────────

    /**
     * Set a flash message (shown once on next page load)
     * @param string $type 'success', 'error', 'warning', 'info'
     * @param string $message The message text
     */
    public static function flash(string $type, string $message)
    {
        self::start();
        $_SESSION['flash_messages'][] = [
            'type' => $type,
            'message' => $message
        ];
    }

    /**
     * Get and clear all flash messages
     * @return array
     */
    public static function getFlashMessages(): array
    {
        self::start();
        $messages = $_SESSION['flash_messages'] ?? [];
        unset($_SESSION['flash_messages']);
        return $messages;
    }

    /**
     * Check if there are any flash messages
     * @return bool
     */
    public static function hasFlashMessages(): bool
    {
        self::start();
        return !empty($_SESSION['flash_messages']);
    }
}
