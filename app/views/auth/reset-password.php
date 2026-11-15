<?php
/**
 * Reset Password View
 * RTIH TaskFlow
 */
$pageTitle = 'TaskFlow – Set New Password';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/toast.php';
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

            <!-- Primary Reset Password Card -->
            <div class="w-full bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/40 p-6 sm:p-8 transition-all">
                
                <div class="mb-6">
                    <div class="w-11 h-11 rounded-xl bg-primary/10 text-primary flex items-center justify-center mb-3">
                        <span class="material-symbols-outlined text-[24px]">key</span>
                    </div>
                    <h1 class="font-heading text-2xl font-bold tracking-tight text-on-surface">Set New Password</h1>
                    <p class="text-xs sm:text-sm text-on-surface-variant mt-1">
                        Create a new strong password for your account: <strong class="text-on-surface"><?= e($email) ?></strong>
                    </p>
                </div>

                <!-- Reset Password Form -->
                <form action="<?= BASE_URL ?>/public/actions/auth.php?action=reset-password" method="POST" class="flex flex-col gap-4">
                    <?= csrf_field() ?>
                    <input type="hidden" name="token" value="<?= e($token) ?>">

                    <!-- New Password Field -->
                    <div>
                        <label class="text-xs font-semibold text-on-surface uppercase tracking-wider block mb-2" for="reset-password">
                            New Password
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-[20px] pointer-events-none select-none">
                                lock
                            </span>
                            <input type="password" id="reset-password" name="password" required autofocus
                                   oninput="checkResetPasswordStrength()"
                                   class="w-full h-11 pl-11 pr-11 bg-surface-container-low text-on-surface rounded-lg text-sm border border-transparent focus:border-primary focus:bg-surface-container-lowest outline-none transition-colors placeholder:text-outline"
                                   placeholder="Enter new strong password">
                            <button type="button" onclick="togglePasswordVisibility('reset-password', 'eye-reset-1')" 
                                    class="absolute right-3.5 text-on-surface-variant hover:text-on-surface transition-colors p-0.5 rounded">
                                <span class="material-symbols-outlined text-[20px]" id="eye-reset-1">visibility</span>
                            </button>
                        </div>

                        <!-- Real-time Password Strength Meter -->
                        <div class="mt-2.5">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] font-semibold text-on-surface-variant">Password Strength:</span>
                                <span id="resetStrengthLabel" class="text-[11px] font-bold text-on-surface-variant">Enter password</span>
                            </div>
                            <div class="h-1.5 w-full bg-surface-container rounded-full overflow-hidden">
                                <div id="resetStrengthBar" class="h-full bg-outline-variant/40 transition-all duration-300 w-0"></div>
                            </div>
                        </div>

                        <!-- 5 Mandatory Password Rules Checklist -->
                        <div class="mt-3 p-3 bg-surface-container-low rounded-lg border border-outline-variant/30 text-[11px] space-y-1">
                            <p class="font-bold text-on-surface text-[11px] mb-1.5">5 Rules for a Strong Password:</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-1 text-on-surface-variant">
                                <div id="rule-len" class="flex items-center gap-1.5 transition-colors">
                                    <span class="material-symbols-outlined text-[15px] text-outline">radio_button_unchecked</span>
                                    <span>At least 8 characters</span>
                                </div>
                                <div id="rule-up" class="flex items-center gap-1.5 transition-colors">
                                    <span class="material-symbols-outlined text-[15px] text-outline">radio_button_unchecked</span>
                                    <span>Uppercase letter (A-Z)</span>
                                </div>
                                <div id="rule-low" class="flex items-center gap-1.5 transition-colors">
                                    <span class="material-symbols-outlined text-[15px] text-outline">radio_button_unchecked</span>
                                    <span>Lowercase letter (a-z)</span>
                                </div>
                                <div id="rule-num" class="flex items-center gap-1.5 transition-colors">
                                    <span class="material-symbols-outlined text-[15px] text-outline">radio_button_unchecked</span>
                                    <span>Numeric digit (0-9)</span>
                                </div>
                                <div id="rule-sym" class="flex items-center gap-1.5 transition-colors sm:col-span-2">
                                    <span class="material-symbols-outlined text-[15px] text-outline">radio_button_unchecked</span>
                                    <span>Special symbol (!@#$%^&*...)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label class="text-xs font-semibold text-on-surface uppercase tracking-wider block mb-2" for="reset-password-confirm">
                            Confirm New Password
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-[20px] pointer-events-none select-none">
                                lock_clock
                            </span>
                            <input type="password" id="reset-password-confirm" name="password_confirm" required
                                   oninput="checkPasswordMatch()"
                                   class="w-full h-11 pl-11 pr-11 bg-surface-container-low text-on-surface rounded-lg text-sm border border-transparent focus:border-primary focus:bg-surface-container-lowest outline-none transition-colors placeholder:text-outline"
                                   placeholder="Re-enter your new password">
                            <button type="button" onclick="togglePasswordVisibility('reset-password-confirm', 'eye-reset-2')" 
                                    class="absolute right-3.5 text-on-surface-variant hover:text-on-surface transition-colors p-0.5 rounded">
                                <span class="material-symbols-outlined text-[20px]" id="eye-reset-2">visibility</span>
                            </button>
                        </div>
                        <p id="matchError" class="text-[11px] text-error font-medium mt-1.5 hidden flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span>
                            <span>Passwords do not match.</span>
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full h-11 mt-3 bg-primary hover:bg-primary/90 text-on-primary font-semibold text-sm rounded-lg transition-all shadow-sm flex items-center justify-center gap-2">
                        <span>Update Password & Sign In</span>
                        <span class="material-symbols-outlined text-[18px]">verified</span>
                    </button>

                    <!-- Cancel Link -->
                    <div class="text-center pt-2">
                        <a href="<?= BASE_URL ?>/public/index.php?page=login" 
                           class="inline-flex items-center gap-1.5 text-xs font-semibold text-on-surface-variant hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[16px]">close</span>
                            <span>Cancel and Return to Sign In</span>
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

<script>
function togglePasswordVisibility(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        input.type = 'password';
        icon.textContent = 'visibility';
    }
}

function updateRule(elementId, passed) {
    const el = document.getElementById(elementId);
    if (!el) return;
    const icon = el.querySelector('span:first-child');
    if (passed) {
        el.className = 'flex items-center gap-1.5 text-emerald-800 font-semibold';
        if (icon) {
            icon.textContent = 'check_circle';
            icon.className = 'material-symbols-outlined text-[15px] text-emerald-600';
        }
    } else {
        el.className = 'flex items-center gap-1.5 text-on-surface-variant';
        if (icon) {
            icon.textContent = 'radio_button_unchecked';
            icon.className = 'material-symbols-outlined text-[15px] text-outline';
        }
    }
}

function checkResetPasswordStrength() {
    const password = document.getElementById('reset-password').value;

    const hasLen = password.length >= 8;
    const hasUp = /[A-Z]/.test(password);
    const hasLow = /[a-z]/.test(password);
    const hasNum = /[0-9]/.test(password);
    const hasSym = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~`]/.test(password);

    updateRule('rule-len', hasLen);
    updateRule('rule-up', hasUp);
    updateRule('rule-low', hasLow);
    updateRule('rule-num', hasNum);
    updateRule('rule-sym', hasSym);

    const score = [hasLen, hasUp, hasLow, hasNum, hasSym].filter(Boolean).length;
    const bar = document.getElementById('resetStrengthBar');
    const label = document.getElementById('resetStrengthLabel');

    if (password.length === 0) {
        bar.className = 'h-full bg-outline-variant/40 transition-all duration-300 w-0';
        label.textContent = 'Enter password';
        label.className = 'text-[11px] font-bold text-on-surface-variant';
    } else if (score <= 2) {
        bar.className = 'h-full bg-error transition-all duration-300 w-1/4';
        label.textContent = 'Too Weak';
        label.className = 'text-[11px] font-bold text-error';
    } else if (score <= 4) {
        bar.className = 'h-full bg-amber-500 transition-all duration-300 w-2/4';
        label.textContent = 'Fair';
        label.className = 'text-[11px] font-bold text-amber-600';
    } else {
        bar.className = 'h-full bg-emerald-500 transition-all duration-300 w-full';
        label.textContent = 'Strong (Meets all 5 rules)';
        label.className = 'text-[11px] font-bold text-emerald-700';
    }

    checkPasswordMatch();
}

function checkPasswordMatch() {
    const p1 = document.getElementById('reset-password').value;
    const p2 = document.getElementById('reset-password-confirm').value;
    const err = document.getElementById('matchError');
    if (p2.length > 0 && p1 !== p2) {
        err.classList.remove('hidden');
    } else {
        err.classList.add('hidden');
    }
}
</script>
</body>
</html>
