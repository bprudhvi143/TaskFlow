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
                                       placeholder="you@example.com" value="alex@rtih.org">
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-xs font-semibold text-on-surface uppercase tracking-wider" for="signin-password">
                                    Password
                                </label>
                                <span class="text-xs text-on-surface-variant hover:text-primary transition-colors cursor-pointer" onclick="alert('For this internship demonstration, test accounts are provided below.')">
                                    Need help?
                                </span>
                            </div>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-[20px] pointer-events-none select-none">
                                    lock
                                </span>
                                <input type="password" id="signin-password" name="password" required
                                       class="w-full h-11 pl-11 pr-11 bg-surface-container-low text-on-surface rounded-lg text-sm border border-transparent focus:border-primary focus:bg-surface-container-lowest outline-none transition-colors placeholder:text-outline"
                                       placeholder="Enter password" value="Password@123">
                                <button type="button" onclick="togglePasswordVisibility('signin-password', 'eye-icon-signin')" 
                                        class="absolute right-3.5 text-on-surface-variant hover:text-on-surface transition-colors p-0.5 rounded">
                                    <span class="material-symbols-outlined text-[20px]" id="eye-icon-signin">visibility</span>
                                </button>
                            </div>
                        </div>

                        <!-- Quick Credentials Pills for Evaluation -->
                        <div class="pt-1 pb-1">
                            <span class="text-[11px] font-semibold text-on-surface-variant/80 uppercase tracking-wider block mb-1.5">Quick Test Profiles:</span>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" onclick="fillCredentials('alex@rtih.org', 'Password@123')"
                                        class="px-2.5 py-1 rounded-md bg-surface-container hover:bg-surface-container-high text-xs text-on-surface font-medium transition-colors flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-secondary"></span> User: alex@rtih.org
                                </button>
                                <button type="button" onclick="fillCredentials('admin@rtih.org', 'Password@123')"
                                        class="px-2.5 py-1 rounded-md bg-surface-container hover:bg-surface-container-high text-xs text-primary font-semibold transition-colors flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-primary"></span> Admin: admin@rtih.org
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
                                       placeholder="Alex Morgan">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-on-surface uppercase tracking-wider mb-2" for="signup-email">
                                Work Email
                            </label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-[20px] pointer-events-none select-none">
                                    alternate_email
                                </span>
                                <input type="email" id="signup-email" name="email" required
                                       class="w-full h-11 pl-11 pr-4 bg-surface-container-low text-on-surface rounded-lg text-sm border border-transparent focus:border-primary focus:bg-surface-container-lowest outline-none transition-colors placeholder:text-outline"
                                       placeholder="alex@company.com">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-on-surface uppercase tracking-wider mb-2" for="signup-password">
                                Create Password
                            </label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-[20px] pointer-events-none select-none">
                                    lock
                                </span>
                                <input type="password" id="signup-password" name="password" required minlength="8"
                                       class="w-full h-11 pl-11 pr-11 bg-surface-container-low text-on-surface rounded-lg text-sm border border-transparent focus:border-primary focus:bg-surface-container-lowest outline-none transition-colors placeholder:text-outline"
                                       placeholder="Minimum 8 characters">
                                <button type="button" onclick="togglePasswordVisibility('signup-password', 'eye-icon-signup')" 
                                        class="absolute right-3.5 text-on-surface-variant hover:text-on-surface transition-colors p-0.5 rounded">
                                    <span class="material-symbols-outlined text-[20px]" id="eye-icon-signup">visibility</span>
                                </button>
                            </div>
                        </div>

                        <button type="submit" 
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
                <span>RTIH Intern – IT Technical Assignment &bull; TaskFlow</span>
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
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
