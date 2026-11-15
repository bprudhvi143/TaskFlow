<?php
/**
 * Forgot Password View
 * RTIH TaskFlow
 */
$pageTitle = 'TaskFlow – Forgot Password';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/toast.php';

$resetSuccess = get_flash('reset_success');
$resetEmail   = get_flash('reset_email');
$resetLink    = get_flash('reset_link');
?>
<div class="min-h-screen flex items-center justify-center p-4 sm:p-6 bg-surface">
    <main class="w-full max-w-lg mx-auto">
        <div class="flex flex-col w-full items-center justify-center py-4 sm:py-6">
            
            <!-- Brand Header with Official Logo -->
            <div class="flex flex-col items-center text-center mb-6">
                <div class="flex items-center gap-3 mb-2">
                    <img src="<?= BASE_URL ?>/public/assets/images/logo.png" alt="TaskFlow" class="w-12 h-12 object-contain drop-shadow-md">
                    <span class="font-heading font-bold text-3xl tracking-tight text-primary">TaskFlow</span>
                </div>
                <p class="text-sm font-medium text-on-surface-variant">Plan your work. Track your progress.</p>
            </div>

            <!-- Primary Reset Request Card -->
            <div class="w-full bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/40 p-6 sm:p-8 transition-all">
                
                <div class="mb-6">
                    <div class="w-11 h-11 rounded-xl bg-primary/10 text-primary flex items-center justify-center mb-3">
                        <span class="material-symbols-outlined text-[24px]">lock_reset</span>
                    </div>
                    <h1 class="font-heading text-2xl font-bold tracking-tight text-on-surface">Forgot your password?</h1>
                    <p class="text-xs sm:text-sm text-on-surface-variant mt-1">
                        No worries! Enter your registered email address and we'll generate a secure one-time link to reset it.
                    </p>
                </div>

                <?php if (!empty($resetLink)): ?>
                <!-- Local Evaluation Reset Link Card -->
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex flex-col gap-3 shadow-xs">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-emerald-700 text-[22px] shrink-0 mt-0.5">mark_email_read</span>
                        <div>
                            <h4 class="text-xs font-bold text-emerald-900 uppercase tracking-wider">Reset Link Ready (Local / Evaluation Mode)</h4>
                            <p class="text-xs text-emerald-800 mt-1 leading-relaxed">
                                A secure, time-limited reset token has been generated for <strong><?= e($resetEmail) ?></strong> (valid for 30 minutes).
                            </p>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-emerald-200/60 flex flex-col sm:flex-row items-center gap-2">
                        <a href="<?= $resetLink ?>" 
                           class="w-full sm:w-auto px-4 py-2.5 rounded-lg bg-emerald-800 hover:bg-emerald-900 text-white text-xs font-bold transition-all shadow-sm flex items-center justify-center gap-1.5 text-center">
                            <span>Proceed to Reset Password</span>
                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                        <button type="button" onclick="navigator.clipboard.writeText('<?= $resetLink ?>'); alert('Reset link copied to clipboard!');" 
                                class="w-full sm:w-auto px-3 py-2.5 rounded-lg bg-white border border-emerald-300 text-emerald-900 text-xs font-semibold hover:bg-emerald-100 transition-colors flex items-center justify-center gap-1">
                            <span class="material-symbols-outlined text-[15px]">content_copy</span>
                            <span>Copy Link</span>
                        </button>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Forgot Password Form -->
                <form action="<?= BASE_URL ?>/public/actions/auth.php?action=forgot-password" method="POST" class="flex flex-col gap-4">
                    <?= csrf_field() ?>

                    <!-- Email Field -->
                    <div>
                        <label class="text-xs font-semibold text-on-surface uppercase tracking-wider block mb-2" for="reset-email">
                            Account Email Address
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-[20px] pointer-events-none select-none">
                                alternate_email
                            </span>
                            <input type="email" id="reset-email" name="email" required autofocus
                                   class="w-full h-11 pl-11 pr-4 bg-surface-container-low text-on-surface rounded-lg text-sm border border-transparent focus:border-primary focus:bg-surface-container-lowest outline-none transition-colors placeholder:text-outline"
                                   placeholder="Enter your registered email (e.g. kalyan.varma@gmail.com)"
                                   value="<?= e($resetEmail ?? '') ?>">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full h-11 mt-2 bg-primary hover:bg-primary/90 text-on-primary font-semibold text-sm rounded-lg transition-all shadow-sm flex items-center justify-center gap-2">
                        <span>Send Reset Link</span>
                        <span class="material-symbols-outlined text-[18px]">send</span>
                    </button>

                    <!-- Back to Login -->
                    <div class="text-center pt-2">
                        <a href="<?= BASE_URL ?>/public/index.php?page=login" 
                           class="inline-flex items-center gap-1.5 text-xs font-semibold text-on-surface-variant hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                            <span>Back to Sign In</span>
                        </a>
                    </div>
                </form>

            </div>

            <!-- Footer Attribution -->
            <footer class="mt-8 text-center text-xs text-on-surface-variant">
                <p>TaskFlow &bull; Task tracking application</p>
            </footer>

        </div>
    </main>
</div>
</body>
</html>
