<?php
/**
 * Toast and Flash Notifications Layout
 * RTIH TaskFlow
 */
$flashSuccess = get_flash('success');
$flashError   = get_flash('error');
$flashWarning = get_flash('warning');
?>
<div id="toastContainer" class="fixed top-5 right-6 z-[70] flex flex-col gap-3 pointer-events-none max-w-md w-full">
    <?php if ($flashSuccess): ?>
    <div id="toast-success" class="pointer-events-auto flex items-start gap-3 bg-surface-container-lowest text-on-surface p-4 rounded-xl shadow-[0_16px_36px_-4px_rgba(25,58,54,0.12),0_6px_12px_-3px_rgba(25,58,54,0.04)] border border-outline-variant/40 transition-all duration-300 transform translate-y-0 opacity-100">
        <div class="w-8 h-8 rounded-full bg-secondary/15 text-secondary flex items-center justify-center shrink-0 mt-0.5">
            <span class="material-symbols-outlined text-[20px]">check_circle</span>
        </div>
        <div class="flex-1">
            <p class="text-sm font-semibold text-on-surface leading-tight">Success</p>
            <p class="text-xs text-on-surface-variant mt-0.5"><?= e($flashSuccess) ?></p>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-on-surface-variant hover:text-on-surface p-1 rounded-md transition-colors">
            <span class="material-symbols-outlined text-[18px]">close</span>
        </button>
    </div>
    <?php endif; ?>

    <?php if ($flashError): ?>
    <div id="toast-error" class="pointer-events-auto flex items-start gap-3 bg-surface-container-lowest text-on-surface p-4 rounded-xl shadow-[0_16px_36px_-4px_rgba(25,58,54,0.12),0_6px_12px_-3px_rgba(25,58,54,0.04)] border border-error/30 transition-all duration-300 transform translate-y-0 opacity-100">
        <div class="w-8 h-8 rounded-full bg-error/15 text-error flex items-center justify-center shrink-0 mt-0.5">
            <span class="material-symbols-outlined text-[20px]">error</span>
        </div>
        <div class="flex-1">
            <p class="text-sm font-semibold text-error leading-tight">Notice</p>
            <p class="text-xs text-on-surface-variant mt-0.5"><?= e($flashError) ?></p>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-on-surface-variant hover:text-on-surface p-1 rounded-md transition-colors">
            <span class="material-symbols-outlined text-[18px]">close</span>
        </button>
    </div>
    <?php endif; ?>

    <?php if ($flashWarning): ?>
    <div id="toast-warning" class="pointer-events-auto flex items-start gap-3 bg-surface-container-lowest text-on-surface p-4 rounded-xl shadow-[0_16px_36px_-4px_rgba(25,58,54,0.12),0_6px_12px_-3px_rgba(25,58,54,0.04)] border border-secondary/30 transition-all duration-300 transform translate-y-0 opacity-100">
        <div class="w-8 h-8 rounded-full bg-secondary/15 text-secondary flex items-center justify-center shrink-0 mt-0.5">
            <span class="material-symbols-outlined text-[20px]">warning</span>
        </div>
        <div class="flex-1">
            <p class="text-sm font-semibold text-secondary leading-tight">Attention</p>
            <p class="text-xs text-on-surface-variant mt-0.5"><?= e($flashWarning) ?></p>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-on-surface-variant hover:text-on-surface p-1 rounded-md transition-colors">
            <span class="material-symbols-outlined text-[18px]">close</span>
        </button>
    </div>
    <?php endif; ?>
</div>

<script>
// Auto dismiss toasts after 5 seconds
setTimeout(() => {
    ['toast-success', 'toast-error', 'toast-warning'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.style.opacity = '0';
            el.style.transform = 'translateY(-8px)';
            setTimeout(() => el.remove(), 300);
        }
    });
}, 5000);
</script>
