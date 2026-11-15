<?php
/**
 * Password Reset Model
 * RTIH TaskFlow
 */

require_once __DIR__ . '/../config/database.php';

class PasswordReset {
    /**
     * Generate and store a secure password reset token for the given email
     * Returns raw plaintext token to be embedded in reset link
     */
    public static function createToken(string $email): string {
        $email = strtolower(trim($email));
        $pdo = db();

        // 1. Delete previous reset tokens for this email
        $del = $pdo->prepare("DELETE FROM password_resets WHERE email = :email");
        $del->execute(['email' => $email]);

        // 2. Generate cryptographically strong random token
        $rawToken = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $rawToken);

        // 3. Set expiry to 30 minutes from now
        $stmt = $pdo->prepare("INSERT INTO password_resets (email, token_hash, expires_at) VALUES (:email, :token_hash, DATE_ADD(NOW(), INTERVAL 30 MINUTE))");
        $stmt->execute([
            'email'      => $email,
            'token_hash' => $tokenHash
        ]);

        return $rawToken;
    }

    /**
     * Verify if a reset token is valid and not expired
     * Returns the reset record array on success or null on failure
     */
    public static function verifyToken(string $rawToken): ?array {
        if (empty($rawToken)) {
            return null;
        }

        $tokenHash = hash('sha256', $rawToken);
        $pdo = db();

        $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token_hash = :token_hash AND expires_at > NOW() ORDER BY id DESC LIMIT 1");
        $stmt->execute(['token_hash' => $tokenHash]);
        $record = $stmt->fetch();

        return $record ?: null;
    }

    /**
     * Invalidate/Delete a reset token after use or upon request
     */
    public static function deleteToken(string $rawToken): void {
        $tokenHash = hash('sha256', $rawToken);
        $pdo = db();
        $stmt = $pdo->prepare("DELETE FROM password_resets WHERE token_hash = :token_hash");
        $stmt->execute(['token_hash' => $tokenHash]);
    }

    /**
     * Delete expired reset tokens older than NOW()
     */
    public static function cleanupExpired(): void {
        db()->exec("DELETE FROM password_resets WHERE expires_at < NOW()");
    }
}
