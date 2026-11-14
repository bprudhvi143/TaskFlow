<?php
/**
 * Authentication View (Login & Register)
 * 1:1 match to Google Stitch screen 2c4f01daabbf4ef296d95577a388be1d
 * RTIH TaskFlow
 */
$pageTitle = 'TaskFlow – Authentication';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/toast.php';

$activeTab = $_GET['tab'] ?? 'signin';
?>
<div class="min-h-screen flex items-center justify-center p-4 sm:p-6 bg-surface">
    <main class="w-full max-w-lg mx-auto">
        <div class="flex flex-col w-full items-center justify-center py-4 sm:py-6">
            
            <!-- Brand Header -->
            <div class="flex flex-col items-center text-center mb-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center shadow-sm relative overflow-hidden">
                        <span class="material-symbols-outlined text-secondary-container text-[24px]">task_alt</span>
                    </div>
                    <span class="font-heading font-bold text-2xl tracking-tight text-primary">TaskFlow</span>
                </div>
                <p class="text-sm font-medium text-on-surface-variant">Plan your work. Track your progress.</p>
            </div>

            <!-- Segmented Tab Control -->
            <div class="w-full max-w-sm p-1 bg-surface-container rounded-full flex items-center mb-6 shadow-inner">
                <button type="button" id="tab-signin" 
                        onclick="switchTab('signin')"
                        class="flex-1 py-2 text-center rounded-full text-xs transition-all duration-200 <?= $activeTab === 'signin' ? 'bg-primary text-on-primary shadow-sm font-semibold' : 'text-on-surface-variant hover:text-on-surface font-medium' ?>">
                    Sign In
                </button>
                <button type="button" id="tab-signup" 
                        onclick="switchTab('signup')"
                        class="flex-1 py-2 text-center rounded-full text-xs transition-all duration-200 <?= $activeTab === 'signup' ? 'bg-primary text-on-primary shadow-sm font-semibold' : 'text-on-surface-variant hover:text-on-surface font-medium' ?>">
                    Create Account
                </button>
            </div>

            <!-- Primary Authentication Card -->
            <div class="w-full bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/40 p-6 sm:p-8 transition-all">
                
                <!-- Sign In Panel -->
                <div id="signin-section" class="flex flex-col <?= $activeTab === 'signin' ? '' : 'hidden' ?>">
                    <div class="mb-6">
                        <h1 class="font-heading text-xl font-bold text-on-surface mb-1">Welcome back</h1>
                        <p class="text-xs text-on-surface-variant">Enter your credentials to access your task dashboard.</p>
                    </div>

                    <form action="<?= BASE_URL ?>/public/actions/auth.php?action=login" method="POST" class="space-y-4">
                        <?= csrf_field() ?>

                        <!-- Email Field -->
                        <div>
                            <label class="block text-xs font-semibold text-on-surface uppercase tracking-wider mb-2" for="signin-email">
                                Email address
                            </label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-[20px] pointer-events-none select-none">
                                    alternate_email
                                </span>
                                <input type="email" id="signin-email" name="email" required
                                       class="w-full h-11 pl-11 pr-4 bg-surface-container-low text-on-surface rounded-lg text-sm border border-transparent focus:border-primary focus:bg-surface-container-lowest outline-none transition-colors placeholder:text-outline"
                                       placeholder="you@example.com" value="prudhviboddeda5@gmail.com">
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-xs font-semibold text-on-surface uppercase tracking-wider" for="signin-password">
                                    Password
                                </label>
                                <span class="text-xs text-on-surface-variant hover:text-primary transition-colors cursor-pointer" onclick="alert('Quick test profiles with secure passwords are provided below.')">
                                    Need help?
                                </span>
                            </div>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-[20px] pointer-events-none select-none">
                                    lock
                                </span>
                                <input type="password" id="signin-password" name="password" required
                                       class="w-full h-11 pl-11 pr-11 bg-surface-container-low text-on-surface rounded-lg text-sm border border-transparent focus:border-primary focus:bg-surface-container-lowest outline-none transition-colors placeholder:text-outline"
                                       placeholder="Enter password" value="TaskFlow#2026Secure">
                                <button type="button" onclick="togglePasswordVisibility('signin-password', 'eye-icon-signin')" 
                                        class="absolute right-3.5 text-on-surface-variant hover:text-on-surface transition-colors p-0.5 rounded">
                                    <span class="material-symbols-outlined text-[20px]" id="eye-icon-signin">visibility</span>
                                </button>
                            </div>
                        </div>

                        <!-- Quick Credentials Pills for Evaluation -->
                        <div class="pt-1 pb-1">
                            <span class="text-[11px] font-semibold text-on-surface-variant/80 uppercase tracking-wider block mb-1.5">Quick Test Profiles (Distinct Passwords):</span>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" onclick="fillCredentials('prudhviboddeda5@gmail.com', 'FalconShield#2026!Master')"
                                        class="px-2.5 py-1 rounded-md bg-surface-container hover:bg-surface-container-high text-xs text-primary font-semibold transition-colors flex items-center gap-1.5 border border-primary/20"
                                        title="Boddeda Prudhvi: FalconShield#2026!Master">
                                    <span class="w-2 h-2 rounded-full bg-primary"></span> Admin: Boddeda Prudhvi
                                </button>
                                <button type="button" onclick="fillCredentials('kalyan.varma@gmail.com', 'SapphireWave@2026#Track')"
                                        class="px-2.5 py-1 rounded-md bg-surface-container hover:bg-surface-container-high text-xs text-on-surface font-medium transition-colors flex items-center gap-1.5"
                                        title="Kalyan Varma: SapphireWave@2026#Track">
                                    <span class="w-2 h-2 rounded-full bg-secondary"></span> User: Kalyan Varma
                                </button>
                                <button type="button" onclick="fillCredentials('ananya.rao@gmail.com', 'EmeraldPeak#2026$Nova')"
                                        class="px-2.5 py-1 rounded-md bg-surface-container hover:bg-surface-container-high text-xs text-on-surface font-medium transition-colors flex items-center gap-1.5"
                                        title="Ananya Rao: EmeraldPeak#2026$Nova">
                                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span> User: Ananya Rao
                                </button>
                                <button type="button" onclick="fillCredentials('suresh.reddy@gmail.com', 'GoldenHorizon@2026!Hub')"
                                        class="px-2.5 py-1 rounded-md bg-surface-container hover:bg-surface-container-high text-xs text-on-surface font-medium transition-colors flex items-center gap-1.5"
                                        title="Suresh Reddy: GoldenHorizon@2026!Hub">
                                    <span class="w-2 h-2 rounded-full bg-amber-600"></span> User: Suresh Reddy
                                </button>
                            </div>
                        </div>

                        <!-- Primary Sign In Button -->
                        <button type="submit" 
                                class="w-full py-3 px-4 mt-2 bg-primary hover:bg-primary/90 text-on-primary font-semibold rounded-lg shadow-sm transition-all duration-150 flex items-center justify-center gap-2">
                            <span>Sign In</span>
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </button>
                    </form>
                </div>

                <!-- Registration Panel -->
                <div id="signup-section" class="flex flex-col <?= $activeTab === 'signup' ? '' : 'hidden' ?>">
                    <div class="mb-6">
                        <h1 class="font-heading text-xl font-bold text-on-surface mb-1">Create an account</h1>
                        <p class="text-xs text-on-surface-variant">Start managing your personal tasks cleanly and securely.</p>
                    </div>

                    <form action="<?= BASE_URL ?>/public/actions/auth.php?action=register" method="POST" class="space-y-4">
                        <?= csrf_field() ?>

                        <div>
                            <label class="block text-xs font-semibold text-on-surface uppercase tracking-wider mb-2" for="signup-name">
                                Full Name
                            </label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-[20px] pointer-events-none select-none">
                                    person
                                </span>
                                <input type="text" id="signup-name" name="name" required
                                       class="w-full h-11 pl-11 pr-4 bg-surface-container-low text-on-surface rounded-lg text-sm border border-transparent focus:border-primary focus:bg-surface-container-lowest outline-none transition-colors placeholder:text-outline"
                                       placeholder="Kalyan Varma" oninput="checkPasswordStrength()">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-on-surface uppercase tracking-wider mb-2" for="signup-email">
                                Email Address
                            </label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-[20px] pointer-events-none select-none">
                                    alternate_email
                                </span>
                                <input type="email" id="signup-email" name="email" required
                                       class="w-full h-11 pl-11 pr-4 bg-surface-container-low text-on-surface rounded-lg text-sm border border-transparent focus:border-primary focus:bg-surface-container-lowest outline-none transition-colors placeholder:text-outline"
                                       placeholder="kalyan.varma@gmail.com" oninput="checkPasswordStrength()">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-on-surface uppercase tracking-wider mb-2" for="signup-password">
                                Create Strong Password
                            </label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-[20px] pointer-events-none select-none">
                                    lock
                                </span>
                                <input type="password" id="signup-password" name="password" required minlength="8"
                                       oninput="checkPasswordStrength()"
                                       class="w-full h-11 pl-11 pr-11 bg-surface-container-low text-on-surface rounded-lg text-sm border border-transparent focus:border-primary focus:bg-surface-container-lowest outline-none transition-colors placeholder:text-outline"
                                       placeholder="Enter at least 8 characters">
                                <button type="button" onclick="togglePasswordVisibility('signup-password', 'eye-icon-signup')" 
                                        class="absolute right-3.5 text-on-surface-variant hover:text-on-surface transition-colors p-0.5 rounded">
                                    <span class="material-symbols-outlined text-[20px]" id="eye-icon-signup">visibility</span>
                                </button>
                            </div>

                            <!-- 5 Rules for a Strong Password Checklist (CISA & NIST guidelines) -->
                            <div class="mt-3 p-3 rounded-lg bg-surface-container-low border border-outline-variant/30 text-xs">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="font-semibold text-[11px] text-on-surface uppercase tracking-wider">Password Requirements:</span>
                                    <span id="strengthLabel" class="text-[11px] font-bold text-on-surface-variant">Too Weak</span>
                                </div>
                                
                                <!-- Strength progress bar -->
                                <div class="w-full bg-surface-container-high h-1.5 rounded-full overflow-hidden mb-2.5">
                                    <div id="strengthBar" class="h-full bg-error transition-all duration-300 w-1/12"></div>
                                </div>

                                <ul class="space-y-1 text-[11px]">
                                    <li id="rule-length" class="flex items-center gap-1.5 text-on-surface-variant">
                                        <span class="material-symbols-outlined text-[14px]">radio_button_unchecked</span>
                                        <span>At least 8 characters in length</span>
                                    </li>
                                    <li id="rule-upper" class="flex items-center gap-1.5 text-on-surface-variant">
                                        <span class="material-symbols-outlined text-[14px]">radio_button_unchecked</span>
                                        <span>Contains uppercase letter (A-Z)</span>
                                    </li>
                                    <li id="rule-lower" class="flex items-center gap-1.5 text-on-surface-variant">
                                        <span class="material-symbols-outlined text-[14px]">radio_button_unchecked</span>
                                        <span>Contains lowercase letter (a-z)</span>
                                    </li>
                                    <li id="rule-number" class="flex items-center gap-1.5 text-on-surface-variant">
                                        <span class="material-symbols-outlined text-[14px]">radio_button_unchecked</span>
                                        <span>Contains at least one number (0-9)</span>
                                    </li>
                                    <li id="rule-symbol" class="flex items-center gap-1.5 text-on-surface-variant">
                                        <span class="material-symbols-outlined text-[14px]">radio_button_unchecked</span>
                                        <span>Contains a special symbol (!@#$%^&*...)</span>
                                    </li>
                                    <li id="rule-personal" class="flex items-center gap-1.5 text-on-surface-variant">
                                        <span class="material-symbols-outlined text-[14px]">radio_button_unchecked</span>
                                        <span>Does not contain your name or email prefix</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <button type="submit" id="signupSubmitBtn"
                                class="w-full py-3 px-4 mt-2 bg-primary hover:bg-primary/90 text-on-primary font-semibold rounded-lg shadow-sm transition-all duration-150 flex items-center justify-center gap-2">
                            <span>Get Started Free</span>
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </button>
                    </form>
                </div>

                <!-- Bottom Contextual Switcher Link -->
                <div class="mt-6 pt-4 border-t border-outline-variant/30 flex items-center justify-center text-center">
                    <p id="bottom-text-signin" class="text-xs text-on-surface-variant <?= $activeTab === 'signin' ? '' : 'hidden' ?>">
                        Don't have an account? 
                        <button type="button" onclick="switchTab('signup')" class="text-primary hover:underline font-semibold ml-1">
                            Create an account
                        </button>
                    </p>
                    <p id="bottom-text-signup" class="text-xs text-on-surface-variant <?= $activeTab === 'signup' ? '' : 'hidden' ?>">
                        Already have an account? 
                        <button type="button" onclick="switchTab('signin')" class="text-primary hover:underline font-semibold ml-1">
                            Sign In
                        </button>
                    </p>
                </div>

            </div>

            <!-- Footer Meta -->
            <div class="mt-6 text-center text-xs text-on-surface-variant">
                <span>TaskFlow - Task tracking application</span>
            </div>

        </div>
    </main>
</div>

<script>
function switchTab(tab) {
    const signinSec = document.getElementById('signin-section');
    const signupSec = document.getElementById('signup-section');
    const tabSignin = document.getElementById('tab-signin');
    const tabSignup = document.getElementById('tab-signup');
    const bottomSignin = document.getElementById('bottom-text-signin');
    const bottomSignup = document.getElementById('bottom-text-signup');

    if (tab === 'signin') {
        signinSec.classList.remove('hidden');
        signupSec.classList.add('hidden');
        bottomSignin.classList.remove('hidden');
        bottomSignup.classList.add('hidden');

        tabSignin.className = 'flex-1 py-2 text-center rounded-full text-xs transition-all duration-200 bg-primary text-on-primary shadow-sm font-semibold';
        tabSignup.className = 'flex-1 py-2 text-center rounded-full text-xs transition-all duration-200 text-on-surface-variant hover:text-on-surface font-medium';
    } else {
        signinSec.classList.add('hidden');
        signupSec.classList.remove('hidden');
        bottomSignin.classList.add('hidden');
        bottomSignup.classList.remove('hidden');

        tabSignup.className = 'flex-1 py-2 text-center rounded-full text-xs transition-all duration-200 bg-primary text-on-primary shadow-sm font-semibold';
        tabSignin.className = 'flex-1 py-2 text-center rounded-full text-xs transition-all duration-200 text-on-surface-variant hover:text-on-surface font-medium';
    }
}

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

function fillCredentials(email, password) {
    document.getElementById('signin-email').value = email;
    document.getElementById('signin-password').value = password;
}

function checkPasswordStrength() {
    const password = document.getElementById('signup-password').value;
    const name = document.getElementById('signup-name').value.toLowerCase().trim();
    const email = document.getElementById('signup-email').value.toLowerCase().trim();
    const emailPrefix = email.split('@')[0];

    const hasLength = password.length >= 8;
    const hasUpper = /[A-Z]/.test(password);
    const hasLower = /[a-z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    const hasSymbol = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~`]/.test(password);

    // Personal info check (name or email handle cannot be in password)
    let hasPersonal = false;
    const nameTokens = name.split(/\s+/).filter(t => t.length >= 3);
    if (emailPrefix.length >= 3) nameTokens.push(emailPrefix);
    for (const token of nameTokens) {
        if (password.toLowerCase().includes(token)) {
            hasPersonal = true;
            break;
        }
    }
    const safeFromPersonal = !hasPersonal;

    // Update rule indicators
    updateRuleItem('rule-length', hasLength);
    updateRuleItem('rule-upper', hasUpper);
    updateRuleItem('rule-lower', hasLower);
    updateRuleItem('rule-number', hasNumber);
    updateRuleItem('rule-symbol', hasSymbol);
    updateRuleItem('rule-personal', safeFromPersonal && (name.length > 0 || email.length > 0));

    // Calculate score
    const score = [hasLength, hasUpper, hasLower, hasNumber, hasSymbol, safeFromPersonal].filter(Boolean).length;
    const bar = document.getElementById('strengthBar');
    const label = document.getElementById('strengthLabel');

    if (score <= 2) {
        bar.className = 'h-full bg-error transition-all duration-300 w-1/4';
        label.textContent = 'Too Weak';
        label.className = 'text-[11px] font-bold text-error';
    } else if (score <= 4) {
        bar.className = 'h-full bg-amber-500 transition-all duration-300 w-2/4';
        label.textContent = 'Fair';
        label.className = 'text-[11px] font-bold text-amber-600';
    } else if (score === 5) {
        bar.className = 'h-full bg-sky-500 transition-all duration-300 w-3/4';
        label.textContent = 'Good';
        label.className = 'text-[11px] font-bold text-sky-700';
    } else {
        bar.className = 'h-full bg-emerald-500 transition-all duration-300 w-full';
        label.textContent = 'Strong (Recommended)';
        label.className = 'text-[11px] font-bold text-emerald-700';
    }
}

function updateRuleItem(id, isMet) {
    const el = document.getElementById(id);
    if (!el) return;
    const icon = el.querySelector('.material-symbols-outlined');
    if (isMet) {
        el.className = 'flex items-center gap-1.5 text-emerald-700 font-medium';
        icon.textContent = 'check_circle';
        icon.className = 'material-symbols-outlined text-[14px] text-emerald-600';
    } else {
        el.className = 'flex items-center gap-1.5 text-on-surface-variant';
        icon.textContent = 'radio_button_unchecked';
        icon.className = 'material-symbols-outlined text-[14px] text-outline';
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
