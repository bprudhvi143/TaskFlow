<?php
/**
 * Quick Status Change Modal Component
 * RTIH TaskFlow – Feature 1
 */
?>
<!-- Quick Status Modal Backdrop -->
<div id="statusModalBackdrop" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-inverse-surface/40 backdrop-blur-sm transition-all duration-200 hidden opacity-0"
     role="dialog" aria-modal="true" aria-labelledby="statusModalTitle">
    
    <div class="w-full max-w-md bg-surface-container-lowest rounded-xl shadow-2xl p-6 relative overflow-hidden transition-all transform scale-95" id="statusModalCard">
        
        <!-- Header -->
        <div class="flex items-start justify-between pb-3 border-b border-outline-variant/30">
            <div class="flex items-center gap-3">
                <img src="<?= BASE_URL ?>/public/assets/images/logo-icon.png" alt="TaskFlow" class="w-10 h-10 object-contain drop-shadow-sm">
                <div>
                    <span class="text-[11px] text-secondary font-bold uppercase tracking-wider">Fast Action</span>
                    <h3 id="statusModalTitle" class="font-heading text-lg font-bold text-on-surface">Update Task Status</h3>
                </div>
            </div>
            <button type="button" onclick="closeStatusModal()" class="text-outline hover:text-on-surface p-1 rounded-lg hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Task Title Details Preview -->
        <div class="my-4 p-3 rounded-lg bg-surface-container-low border border-outline-variant/30 flex items-start gap-2.5">
            <span class="material-symbols-outlined text-primary text-[18px] mt-0.5">assignment</span>
            <div class="flex-1 min-w-0">
                <span class="text-[11px] font-semibold text-on-surface-variant block uppercase tracking-wider">Selected Task</span>
                <p id="statusTaskTitle" class="text-xs font-bold text-on-surface truncate leading-tight mt-0.5">Task title</p>
                <span id="statusCurrentBadge" class="inline-flex items-center gap-1 text-[10px] font-semibold text-on-surface-variant mt-1">
                    Current Status: <strong id="statusCurrentVal">Pending</strong>
                </span>
            </div>
        </div>

        <!-- Single-Click Quick Selection Options -->
        <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2">Select New Status:</p>
        <form id="quickStatusForm" action="<?= BASE_URL ?>/public/actions/task-status.php" method="POST" class="flex flex-col gap-2.5">
            <?= csrf_field() ?>
            <input type="hidden" name="task_id" id="status_task_id" value="">
            <input type="hidden" name="status" id="status_target_val" value="">

            <!-- 1. Completed Option -->
            <button type="button" onclick="submitQuickStatus('Completed')"
                    class="w-full p-3 rounded-xl border border-emerald-300 hover:border-emerald-500 bg-emerald-50/50 hover:bg-emerald-100/70 text-emerald-900 flex items-center justify-between transition-all group text-left">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-emerald-200 text-emerald-800 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    </div>
                    <div>
                        <span class="text-xs font-bold block leading-tight">Mark as Completed</span>
                        <span class="text-[10px] text-emerald-700 block">Sinks to bottom with completed badge</span>
                    </div>
                </div>
                <span class="material-symbols-outlined text-emerald-700 opacity-0 group-hover:opacity-100 transition-opacity">arrow_forward</span>
            </button>

            <!-- 2. In Progress Option -->
            <button type="button" onclick="submitQuickStatus('In Progress')"
                    class="w-full p-3 rounded-xl border border-sky-300 hover:border-sky-500 bg-sky-50/50 hover:bg-sky-100/70 text-sky-900 flex items-center justify-between transition-all group text-left">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-sky-200 text-sky-800 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[18px]">pending</span>
                    </div>
                    <div>
                        <span class="text-xs font-bold block leading-tight">Mark as In Progress</span>
                        <span class="text-[10px] text-sky-700 block">Currently active work item</span>
                    </div>
                </div>
                <span class="material-symbols-outlined text-sky-700 opacity-0 group-hover:opacity-100 transition-opacity">arrow_forward</span>
            </button>

            <!-- 3. Pending Option -->
            <button type="button" onclick="submitQuickStatus('Pending')"
                    class="w-full p-3 rounded-xl border border-amber-300 hover:border-amber-500 bg-amber-50/50 hover:bg-amber-100/70 text-amber-900 flex items-center justify-between transition-all group text-left">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-amber-200 text-amber-800 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[18px]">schedule</span>
                    </div>
                    <div>
                        <span class="text-xs font-bold block leading-tight">Mark as Pending</span>
                        <span class="text-[10px] text-amber-700 block">Queued on backlog awaiting start</span>
                    </div>
                </div>
                <span class="material-symbols-outlined text-amber-700 opacity-0 group-hover:opacity-100 transition-opacity">arrow_forward</span>
            </button>
        </form>

        <div class="mt-4 pt-3 border-t border-outline-variant/30 flex justify-end">
            <button type="button" onclick="closeStatusModal()" class="px-4 py-2 rounded-lg text-xs font-semibold text-on-surface hover:bg-surface-container transition-colors">
                Cancel
            </button>
        </div>

    </div>
</div>
