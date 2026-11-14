<?php
/**
 * Task Details Inspection Modal Component
 * 1:1 match to Google Stitch design system
 * RTIH TaskFlow
 */
?>
<!-- Task Details Modal Backdrop -->
<div id="taskDetailsBackdrop" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-inverse-surface/40 backdrop-blur-sm transition-all duration-200 hidden opacity-0"
     role="dialog" aria-modal="true" aria-labelledby="taskDetailsTitle">
    
    <!-- Modal Card Container -->
    <div class="w-full max-w-lg bg-surface-container-lowest rounded-2xl shadow-2xl flex flex-col relative overflow-hidden transition-all transform scale-95" id="taskDetailsCard">
        
        <!-- Top Accent Strip -->
        <div class="h-1.5 w-full bg-gradient-to-r from-primary via-primary-container to-secondary-fixed"></div>

        <!-- Header -->
        <div class="p-6 pb-4 border-b border-outline-variant/30 flex items-start justify-between">
            <div class="flex items-start gap-3 flex-1 pr-3">
                <img src="<?= BASE_URL ?>/public/assets/images/logo-icon.png" alt="TaskFlow" class="w-10 h-10 object-contain drop-shadow-sm shrink-0 mt-0.5">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1.5" id="taskDetailsHeaderMeta">
                        <span id="taskDetailsStatusBadge" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-surface-container text-on-surface-variant">
                            <span class="w-1.5 h-1.5 rounded-full bg-outline"></span>
                            <span id="taskDetailsStatusText">Pending</span>
                        </span>
                        <span id="taskDetailsPriorityBadge" class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-surface-container text-on-surface-variant">
                            Medium
                        </span>
                    </div>
                    <h2 id="taskDetailsTitle" class="font-heading text-xl sm:text-2xl font-bold text-on-surface tracking-tight leading-snug">
                        Task Title
                    </h2>
                </div>
            </div>
            
            <button type="button" onclick="closeTaskDetailsModal()" title="Close details"
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-outline hover:text-on-surface hover:bg-surface-container transition-colors shrink-0">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 flex flex-col gap-4 overflow-y-auto max-h-[65vh]">
            
            <!-- Detailed Description Section -->
            <div class="flex flex-col gap-1.5">
                <span class="text-[11px] font-semibold text-on-surface-variant uppercase tracking-wider">
                    Detailed Description
                </span>
                <div id="taskDetailsDesc" class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/30 text-xs sm:text-sm text-on-surface leading-relaxed whitespace-pre-wrap min-h-[70px]">
                    No description provided for this task.
                </div>
            </div>

            <!-- Task Metadata Grid -->
            <div class="grid grid-cols-2 gap-3 pt-1">
                
                <!-- Due Date -->
                <div class="p-3 rounded-xl bg-surface-container-low/60 border border-outline-variant/20 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-primary shrink-0">
                        <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-on-surface-variant uppercase tracking-wider block font-semibold">Due Date</span>
                        <span id="taskDetailsDueDate" class="text-xs font-semibold text-on-surface block mt-0.5">—</span>
                    </div>
                </div>

                <!-- Created Date -->
                <div class="p-3 rounded-xl bg-surface-container-low/60 border border-outline-variant/20 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-primary shrink-0">
                        <span class="material-symbols-outlined text-[18px]">schedule</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-on-surface-variant uppercase tracking-wider block font-semibold">Created On</span>
                        <span id="taskDetailsCreatedDate" class="text-xs font-semibold text-on-surface block mt-0.5">—</span>
                    </div>
                </div>

            </div>

            <!-- Owner Info (Optional: for Admin view) -->
            <div id="taskDetailsOwnerRow" class="p-3 rounded-xl bg-surface-container-low/60 border border-outline-variant/20 flex items-center gap-3 hidden">
                <div class="w-8 h-8 rounded-full bg-primary/10 text-primary font-bold text-xs flex items-center justify-center shrink-0" id="taskDetailsOwnerInitial">
                    U
                </div>
                <div class="truncate">
                    <span class="text-[10px] text-on-surface-variant uppercase tracking-wider block font-semibold">Assigned Owner</span>
                    <span id="taskDetailsOwnerName" class="text-xs font-semibold text-on-surface block truncate">Owner Name</span>
                    <span id="taskDetailsOwnerEmail" class="text-[10px] text-on-surface-variant block truncate">owner@email.com</span>
                </div>
            </div>

        </div>

        <!-- Footer Actions -->
        <div class="p-4 px-6 bg-surface-container-lowest border-t border-outline-variant/30 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <button type="button" id="taskDetailsQuickStatusBtn"
                        class="px-3 py-1.5 rounded-lg border border-outline-variant/40 hover:bg-surface-container text-on-surface text-xs font-semibold inline-flex items-center gap-1.5 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">swap_horiz</span>
                    <span>Change Status</span>
                </button>
                <button type="button" id="taskDetailsEditBtn"
                        class="px-3 py-1.5 rounded-lg border border-outline-variant/40 hover:bg-surface-container text-on-surface text-xs font-semibold inline-flex items-center gap-1.5 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">edit</span>
                    <span>Edit Task</span>
                </button>
            </div>

            <button type="button" onclick="closeTaskDetailsModal()"
                    class="px-4 py-1.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface text-xs font-semibold transition-colors">
                Close
            </button>
        </div>

    </div>
</div>
