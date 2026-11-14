<?php
/**
 * Delete Confirmation Modal Component
 * 1:1 match to Google Stitch screen 631278972444447f83eb7996b801634b
 * RTIH TaskFlow
 */
?>
<!-- Delete Confirmation Modal Backdrop -->
<div id="deleteModalBackdrop" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-inverse-surface/40 backdrop-blur-sm transition-all duration-200 hidden opacity-0"
     role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
    
    <div class="w-full max-w-md bg-surface-container-lowest rounded-xl shadow-2xl p-6 relative overflow-hidden transition-all transform scale-95" id="deleteModalCard">
        
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-error/15 text-error flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[22px]">delete</span>
            </div>
            <div class="flex-1">
                <h3 id="deleteModalTitle" class="font-heading text-lg font-bold text-on-surface">Delete task?</h3>
                <p class="text-xs text-on-surface-variant mt-1.5 leading-relaxed">
                    Are you sure you want to delete <strong id="deleteTaskTitle" class="text-on-surface font-semibold">this task</strong>? This action cannot be undone.
                </p>
            </div>
        </div>

        <form id="deleteTaskForm" action="<?= BASE_URL ?>/public/actions/task-delete.php" method="POST" class="mt-6 flex items-center justify-end gap-3">
            <?= csrf_field() ?>
            <input type="hidden" name="task_id" id="delete_task_id" value="">
            
            <button type="button" onclick="closeDeleteModal()"
                    class="px-4 py-2.5 rounded-lg text-xs font-semibold text-on-surface hover:bg-surface-container transition-colors">
                Cancel
            </button>
            <button type="submit"
                    class="px-5 py-2.5 rounded-lg bg-error hover:bg-error/90 text-on-error text-xs font-semibold shadow-sm transition-all flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">delete</span>
                <span>Delete Task</span>
            </button>
        </form>

    </div>
</div>
