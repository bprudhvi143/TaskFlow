<?php
/**
 * Add / Edit Task Modal Component
 * 1:1 match to Google Stitch screen 866c18510bb14481bf83d850a009a06a
 * RTIH TaskFlow
 */
?>
<!-- Task Modal Backdrop & Overlay -->
<div id="taskModalBackdrop" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-inverse-surface/40 backdrop-blur-sm transition-all duration-200 hidden opacity-0"
     role="dialog" aria-modal="true" aria-labelledby="taskModalTitle">
    
    <!-- Modal Card Container -->
    <div class="w-full max-w-xl bg-surface-container-lowest rounded-xl shadow-2xl flex flex-col relative overflow-hidden transition-all transform scale-95" id="taskModalCard">
        
        <!-- Top Subtle Accent Strip -->
        <div class="h-1.5 w-full bg-gradient-to-r from-primary via-primary-container to-secondary-fixed"></div>

        <!-- Modal Header -->
        <div class="px-6 pt-5 pb-3 flex items-start justify-between bg-surface-container-lowest border-b border-outline-variant/30">
            <div class="flex items-start gap-3">
                <img src="<?= BASE_URL ?>/public/assets/images/logo-icon.png" alt="TaskFlow" class="w-10 h-10 object-contain drop-shadow-sm shrink-0 mt-0.5">
                <div class="flex flex-col pr-4">
                    <div class="flex items-center gap-1.5 mb-1">
                        <span class="w-2 h-2 rounded-full bg-primary" id="taskModalBadgeDot"></span>
                        <span class="text-[11px] text-primary font-semibold uppercase tracking-wider" id="taskModalBadgeText">New Entry</span>
                    </div>
                    <h2 id="taskModalTitle" class="font-heading text-xl font-bold text-on-surface tracking-tight">Create New Task</h2>
                    <p id="taskModalSubtitle" class="text-xs text-on-surface-variant mt-0.5">Fill in the details below to add a new task to your workspace.</p>
                </div>
            </div>
            <!-- Close button -->
            <button type="button" onclick="closeTaskModal()" title="Close modal"
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-outline hover:text-on-surface hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Task Form -->
        <form id="taskForm" action="<?= BASE_URL ?>/public/actions/task-save.php" method="POST" class="flex flex-col">
            <?= csrf_field() ?>
            <input type="hidden" name="task_id" id="task_id" value="">
            <input type="hidden" name="action_type" id="action_type" value="create">

            <div class="px-6 py-4 flex flex-col gap-4 bg-surface-container-lowest max-h-[75vh] overflow-y-auto">
                
                <!-- 1. Task Title (Required, max 150) -->
                <div class="flex flex-col gap-1.5">
                    <div class="flex items-center justify-between">
                        <label for="task_title" class="text-xs font-semibold text-on-surface flex items-center gap-1 uppercase tracking-wider">
                            <span>Task Title</span>
                            <span class="text-error font-bold" title="Required">*</span>
                        </label>
                        <span id="titleCharCount" class="text-xs text-on-surface-variant font-mono">0 / 150</span>
                    </div>
                    <div class="relative flex items-center">
                        <input type="text" id="task_title" name="task_title" required maxlength="150"
                               oninput="updateTitleCount(this)"
                               class="w-full px-3.5 py-2.5 bg-surface-container-low rounded-lg text-sm text-on-surface placeholder:text-outline border border-outline-variant/30 focus:border-primary focus:bg-surface-container-lowest focus:shadow-sm focus:outline-none transition-all"
                               placeholder="Enter task title (e.g. Prepare system test report)">
                    </div>
                    <p id="titleInlineError" class="text-[11px] text-error font-medium flex items-center gap-1 hidden">
                        <span class="material-symbols-outlined text-[14px]">error</span>
                        <span>Task title is required and cannot be empty.</span>
                    </p>
                    <p class="text-[11px] text-on-surface-variant flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">info</span>
                        Maximum 150 characters. Required.
                    </p>
                </div>

                <!-- 2. Task Description (Optional) -->
                <div class="flex flex-col gap-1.5">
                    <div class="flex items-center justify-between">
                        <label for="task_description" class="text-xs font-semibold text-on-surface uppercase tracking-wider">
                            Description <span class="font-normal text-on-surface-variant lowercase">(optional)</span>
                        </label>
                    </div>
                    <textarea id="task_description" name="task_description" rows="3"
                              class="w-full p-3.5 bg-surface-container-low rounded-lg text-sm text-on-surface placeholder:text-outline border border-outline-variant/30 focus:border-primary focus:bg-surface-container-lowest focus:shadow-sm focus:outline-none resize-none transition-all"
                              placeholder="Add details, notes, or next steps..."></textarea>
                </div>

                <!-- 3. Status & Priority Selects (Two Columns) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Status -->
                    <div class="flex flex-col gap-1.5">
                        <label for="status" class="text-xs font-semibold text-on-surface uppercase tracking-wider">Status</label>
                        <div class="relative flex items-center">
                            <select id="status" name="status"
                                    class="w-full pl-3.5 pr-10 py-2.5 bg-surface-container-low rounded-lg text-sm text-on-surface border border-outline-variant/30 focus:border-primary focus:bg-surface-container-lowest appearance-none outline-none cursor-pointer transition-all">
                                <option value="Pending" selected>Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 pointer-events-none text-on-surface-variant text-[20px]">expand_more</span>
                        </div>
                    </div>

                    <!-- Priority -->
                    <div class="flex flex-col gap-1.5">
                        <label for="priority" class="text-xs font-semibold text-on-surface uppercase tracking-wider">Priority</label>
                        <div class="relative flex items-center">
                            <select id="priority" name="priority"
                                    class="w-full pl-3.5 pr-10 py-2.5 bg-surface-container-low rounded-lg text-sm text-on-surface border border-outline-variant/30 focus:border-primary focus:bg-surface-container-lowest appearance-none outline-none cursor-pointer transition-all">
                                <option value="Low">Low</option>
                                <option value="Medium" selected>Medium (Standard)</option>
                                <option value="High">High</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 pointer-events-none text-on-surface-variant text-[20px]">expand_more</span>
                        </div>
                    </div>
                </div>

                <!-- 4. Due Date (Optional: Today or Future Date Only) -->
                <div class="flex flex-col gap-1.5">
                    <div class="flex items-center justify-between">
                        <label for="due_date" class="text-xs font-semibold text-on-surface uppercase tracking-wider">
                            Due Date <span class="font-normal text-on-surface-variant lowercase">(optional)</span>
                        </label>
                        <span class="text-[11px] text-primary font-medium flex items-center gap-1">
                            <span class="material-symbols-outlined text-[13px]">event_available</span>
                            Today or later
                        </span>
                    </div>
                    <div class="relative flex items-center">
                        <input type="date" id="due_date" name="due_date" min="<?= date('Y-m-d') ?>"
                               oninput="resetDueDateError(this)"
                               class="w-full px-3.5 py-2.5 bg-surface-container-low rounded-lg text-sm text-on-surface border border-outline-variant/30 focus:border-primary focus:bg-surface-container-lowest appearance-none outline-none transition-all">
                    </div>
                    <p id="dueDateInlineError" class="text-[11px] text-error font-medium flex items-center gap-1 hidden">
                        <span class="material-symbols-outlined text-[14px]">error</span>
                        <span>Task deadline must be today or a future date.</span>
                    </p>
                    <p class="text-[11px] text-on-surface-variant flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">info</span>
                        Past dates cannot be selected as a task deadline.
                    </p>
                </div>

            </div>

            <!-- Modal Actions Footer -->
            <div class="px-6 py-4 bg-surface-container-low border-t border-outline-variant/30 flex items-center justify-end gap-3 rounded-b-xl">
                <button type="button" onclick="closeTaskModal()"
                        class="px-4 py-2.5 rounded-lg text-xs font-semibold text-on-surface hover:bg-surface-container-high transition-colors">
                    Cancel
                </button>
                <button type="submit" id="taskModalSubmitBtn"
                        class="px-5 py-2.5 rounded-lg bg-primary hover:bg-primary/90 text-on-primary text-xs font-semibold shadow-sm transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    <span id="taskModalSubmitText">Create Task</span>
                </button>
            </div>
        </form>

    </div>
</div>
