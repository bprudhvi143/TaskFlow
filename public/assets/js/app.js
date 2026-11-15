/**
 * RTIH TaskFlow – Client Application JavaScript
 * Modals, Dynamic Validation & UI Feedback
 */

document.addEventListener('DOMContentLoaded', () => {
    // Escape key closes open modals
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeTaskModal();
            closeDeleteModal();
            closeStatusModal();
            closeTaskDetailsModal();
            if (typeof closeRoleModal === 'function') {
                closeRoleModal();
            }
            if (typeof closeUserTasksModal === 'function') {
                closeUserTasksModal();
            }
        }
    });

    // Close on backdrop click
    const taskBackdrop = document.getElementById('taskModalBackdrop');
    if (taskBackdrop) {
        taskBackdrop.addEventListener('click', (e) => {
            if (e.target === taskBackdrop) {
                closeTaskModal();
            }
        });
    }

    const deleteBackdrop = document.getElementById('deleteModalBackdrop');
    if (deleteBackdrop) {
        deleteBackdrop.addEventListener('click', (e) => {
            if (e.target === deleteBackdrop) {
                closeDeleteModal();
            }
        });
    }

    const statusBackdrop = document.getElementById('statusModalBackdrop');
    if (statusBackdrop) {
        statusBackdrop.addEventListener('click', (e) => {
            if (e.target === statusBackdrop) {
                closeStatusModal();
            }
        });
    }

    const taskDetailsBackdrop = document.getElementById('taskDetailsBackdrop');
    if (taskDetailsBackdrop) {
        taskDetailsBackdrop.addEventListener('click', (e) => {
            if (e.target === taskDetailsBackdrop) {
                closeTaskDetailsModal();
            }
        });
    }

    // Interactive Frontend Validation for Task Creation & Editing
    const taskForm = document.getElementById('taskForm');
    if (taskForm) {
        taskForm.addEventListener('submit', (e) => {
            const titleInput = document.getElementById('task_title');
            const inlineError = document.getElementById('titleInlineError');
            if (titleInput && titleInput.value.trim() === '') {
                e.preventDefault();
                titleInput.classList.add('!border-error', 'bg-error/5');
                if (inlineError) {
                    inlineError.classList.remove('hidden');
                }
                titleInput.focus();
                return false;
            }

            // Deadline validation: must be today or future date
            const dueDateInput = document.getElementById('due_date');
            const dueDateInlineError = document.getElementById('dueDateInlineError');
            if (dueDateInput && dueDateInput.value) {
                const todayStr = new Date().toLocaleDateString('en-CA'); // YYYY-MM-DD
                if (dueDateInput.value < todayStr) {
                    e.preventDefault();
                    dueDateInput.classList.add('!border-error', 'bg-error/5');
                    if (dueDateInlineError) {
                        dueDateInlineError.classList.remove('hidden');
                    }
                    dueDateInput.focus();
                    return false;
                }
            }
        });
    }
});

/**
 * Open Modal in Create Mode
 */
function openCreateModal() {
    const form = document.getElementById('taskForm');
    if (form) {
        form.reset();
    }

    document.getElementById('task_id').value = '';
    document.getElementById('action_type').value = 'create';
    document.getElementById('taskModalTitle').textContent = 'Create New Task';
    document.getElementById('taskModalSubtitle').textContent = 'Fill in the details below to add a new task to your workspace.';
    document.getElementById('taskModalBadgeText').textContent = 'New Entry';
    document.getElementById('taskModalSubmitText').textContent = 'Create Task';
    document.getElementById('titleCharCount').textContent = '0 / 150';
    const inlineError = document.getElementById('titleInlineError');
    if (inlineError) inlineError.classList.add('hidden');
    const titleInput = document.getElementById('task_title');
    if (titleInput) titleInput.classList.remove('!border-error', 'bg-error/5');

    // Dynamic deadline constraint (today or later)
    const todayStr = new Date().toLocaleDateString('en-CA');
    const dueDateInput = document.getElementById('due_date');
    if (dueDateInput) {
        dueDateInput.min = todayStr;
        dueDateInput.classList.remove('!border-error', 'bg-error/5');
    }
    const dueDateInlineError = document.getElementById('dueDateInlineError');
    if (dueDateInlineError) dueDateInlineError.classList.add('hidden');

    const backdrop = document.getElementById('taskModalBackdrop');
    const card = document.getElementById('taskModalCard');

    backdrop.classList.remove('hidden');
    setTimeout(() => {
        backdrop.classList.remove('opacity-0');
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
        document.getElementById('task_title').focus();
    }, 10);
}

/**
 * Open Modal in Edit Mode pre-populated with task data
 */
function openEditModal(task) {
    document.getElementById('task_id').value = task.id;
    document.getElementById('action_type').value = 'update';
    document.getElementById('taskModalTitle').textContent = 'Edit Task';
    document.getElementById('taskModalSubtitle').textContent = 'Update the details and lifecycle status for this task.';
    document.getElementById('taskModalBadgeText').textContent = 'Modify Entry';
    document.getElementById('taskModalSubmitText').textContent = 'Save Changes';

    document.getElementById('task_title').value = task.title || '';
    document.getElementById('task_description').value = task.description || '';
    document.getElementById('status').value = task.status || 'Pending';
    document.getElementById('priority').value = task.priority || 'Medium';
    document.getElementById('due_date').value = task.due_date || '';

    const inlineError = document.getElementById('titleInlineError');
    if (inlineError) inlineError.classList.add('hidden');
    const titleInput = document.getElementById('task_title');
    if (titleInput) titleInput.classList.remove('!border-error', 'bg-error/5');

    // Dynamic deadline constraint (today or later)
    const todayStr = new Date().toLocaleDateString('en-CA');
    const dueDateInput = document.getElementById('due_date');
    if (dueDateInput) {
        dueDateInput.min = todayStr;
        dueDateInput.classList.remove('!border-error', 'bg-error/5');
    }
    const dueDateInlineError = document.getElementById('dueDateInlineError');
    if (dueDateInlineError) dueDateInlineError.classList.add('hidden');

    updateTitleCount(document.getElementById('task_title'));

    const backdrop = document.getElementById('taskModalBackdrop');
    const card = document.getElementById('taskModalCard');

    backdrop.classList.remove('hidden');
    setTimeout(() => {
        backdrop.classList.remove('opacity-0');
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
        document.getElementById('task_title').focus();
    }, 10);
}

/**
 * Close Task Modal
 */
function closeTaskModal() {
    const backdrop = document.getElementById('taskModalBackdrop');
    const card = document.getElementById('taskModalCard');
    if (!backdrop || backdrop.classList.contains('hidden')) return;

    backdrop.classList.add('opacity-0');
    card.classList.remove('scale-100');
    card.classList.add('scale-95');
    setTimeout(() => {
        backdrop.classList.add('hidden');
    }, 200);
}

/**
 * Open Delete Confirmation Modal
 */
function openDeleteModal(taskId, title) {
    document.getElementById('delete_task_id').value = taskId;
    document.getElementById('deleteTaskTitle').textContent = `"${title}"`;

    const backdrop = document.getElementById('deleteModalBackdrop');
    const card = document.getElementById('deleteModalCard');

    backdrop.classList.remove('hidden');
    setTimeout(() => {
        backdrop.classList.remove('opacity-0');
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
    }, 10);
}

/**
 * Close Delete Modal
 */
function closeDeleteModal() {
    const backdrop = document.getElementById('deleteModalBackdrop');
    const card = document.getElementById('deleteModalCard');
    if (!backdrop || backdrop.classList.contains('hidden')) return;

    backdrop.classList.add('opacity-0');
    card.classList.remove('scale-100');
    card.classList.add('scale-95');
    setTimeout(() => {
        backdrop.classList.add('hidden');
    }, 200);
}

/**
 * Dynamic Title Character Counter & Validation Reset
 */
function updateTitleCount(input) {
    const counter = document.getElementById('titleCharCount');
    const inlineError = document.getElementById('titleInlineError');
    if (input) {
        if (input.value.trim() !== '') {
            input.classList.remove('!border-error', 'bg-error/5');
            if (inlineError) {
                inlineError.classList.add('hidden');
            }
        }
    }

    if (counter) {
        const len = input.value.length;
        counter.textContent = `${len} / 150`;
        if (len > 150) {
            counter.classList.add('text-error', 'font-bold');
            counter.classList.remove('text-on-surface-variant');
        } else {
            counter.classList.remove('text-error', 'font-bold');
            counter.classList.add('text-on-surface-variant');
        }
    }
}

/**
 * Reset Due Date Error State on Input
 */
function resetDueDateError(input) {
    const inlineError = document.getElementById('dueDateInlineError');
    if (input) {
        const todayStr = new Date().toLocaleDateString('en-CA');
        if (!input.value || input.value >= todayStr) {
            input.classList.remove('!border-error', 'bg-error/5');
            if (inlineError) {
                inlineError.classList.add('hidden');
            }
        }
    }
}

/**
 * Open Quick Status Change Modal (Feature 1)
 */
function openStatusChangeModal(taskId, title, currentStatus) {
    document.getElementById('status_task_id').value = taskId;
    document.getElementById('statusTaskTitle').textContent = `"${title}"`;
    document.getElementById('statusCurrentVal').textContent = currentStatus;

    const backdrop = document.getElementById('statusModalBackdrop');
    const card = document.getElementById('statusModalCard');

    backdrop.classList.remove('hidden');
    setTimeout(() => {
        backdrop.classList.remove('opacity-0');
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
    }, 10);
}

/**
 * Close Quick Status Change Modal
 */
function closeStatusModal() {
    const backdrop = document.getElementById('statusModalBackdrop');
    const card = document.getElementById('statusModalCard');
    if (!backdrop || backdrop.classList.contains('hidden')) return;

    backdrop.classList.add('opacity-0');
    card.classList.remove('scale-100');
    card.classList.add('scale-95');
    setTimeout(() => {
        backdrop.classList.add('hidden');
    }, 200);
}

/**
 * Submit Quick Status Change
 */
function submitQuickStatus(targetStatus) {
    document.getElementById('status_target_val').value = targetStatus;
    document.getElementById('quickStatusForm').submit();
}

/**
 * Open Task Details Inspection Modal
 */
function openTaskDetailsModal(task) {
    if (!task) return;

    const titleEl = document.getElementById('taskDetailsTitle');
    if (titleEl) titleEl.textContent = task.title || 'Untitled Task';
    
    // Detailed Description
    const descEl = document.getElementById('taskDetailsDesc');
    if (descEl) {
        if (task.description && task.description.trim() !== '') {
            descEl.textContent = task.description;
            descEl.classList.remove('italic', 'text-on-surface-variant');
            descEl.classList.add('text-on-surface');
        } else {
            descEl.textContent = 'No detailed description provided for this task.';
            descEl.classList.add('italic', 'text-on-surface-variant');
            descEl.classList.remove('text-on-surface');
        }
    }

    // Status Badge
    const statusBadge = document.getElementById('taskDetailsStatusBadge');
    const statusText = document.getElementById('taskDetailsStatusText');
    if (statusBadge && statusText) {
        statusText.textContent = task.status || 'Pending';
        const dot = statusBadge.querySelector('span:first-child');
        if (task.status === 'Completed') {
            statusBadge.className = 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#dcfce7] text-[#166534]';
            if (dot) dot.className = 'w-1.5 h-1.5 rounded-full bg-[#16a34a]';
        } else if (task.status === 'In Progress') {
            statusBadge.className = 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#e0f2fe] text-[#075985]';
            if (dot) dot.className = 'w-1.5 h-1.5 rounded-full bg-[#0284c7]';
        } else {
            statusBadge.className = 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#fef3c7] text-[#92400e]';
            if (dot) dot.className = 'w-1.5 h-1.5 rounded-full bg-[#f59e0b]';
        }
    }

    // Priority Badge
    const priorityBadge = document.getElementById('taskDetailsPriorityBadge');
    if (priorityBadge) {
        priorityBadge.textContent = task.priority || 'Medium';
        if (task.priority === 'High') {
            priorityBadge.className = 'inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-red-100 text-red-800';
        } else if (task.priority === 'Medium') {
            priorityBadge.className = 'inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-orange-100 text-orange-800';
        } else {
            priorityBadge.className = 'inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-800';
        }
    }

    // Due Date
    const dueDateEl = document.getElementById('taskDetailsDueDate');
    if (dueDateEl) {
        if (task.due_date) {
            dueDateEl.textContent = task.due_date;
            dueDateEl.classList.remove('text-outline');
        } else {
            dueDateEl.textContent = 'No deadline';
            dueDateEl.classList.add('text-outline');
        }
    }

    // Created Date
    const createdEl = document.getElementById('taskDetailsCreatedDate');
    if (createdEl) {
        createdEl.textContent = task.created_at ? task.created_at.substring(0, 16) : '—';
    }

    // Owner (if present for admin view)
    const ownerRow = document.getElementById('taskDetailsOwnerRow');
    if (ownerRow) {
        if (task.owner_name) {
            ownerRow.classList.remove('hidden');
            const ownerNameEl = document.getElementById('taskDetailsOwnerName');
            const ownerEmailEl = document.getElementById('taskDetailsOwnerEmail');
            const ownerInitEl = document.getElementById('taskDetailsOwnerInitial');
            if (ownerNameEl) ownerNameEl.textContent = task.owner_name;
            if (ownerEmailEl) ownerEmailEl.textContent = task.owner_email || '';
            if (ownerInitEl) ownerInitEl.textContent = task.owner_name.charAt(0).toUpperCase();
        } else {
            ownerRow.classList.add('hidden');
        }
    }

    // Setup action buttons
    const editBtn = document.getElementById('taskDetailsEditBtn');
    if (editBtn) {
        if (typeof openEditModal === 'function' && document.getElementById('taskModalBackdrop')) {
            editBtn.classList.remove('hidden');
            editBtn.onclick = () => {
                closeTaskDetailsModal();
                setTimeout(() => openEditModal(task), 200);
            };
        } else {
            editBtn.classList.add('hidden');
        }
    }

    const statusBtn = document.getElementById('taskDetailsQuickStatusBtn');
    if (statusBtn) {
        if (typeof openStatusChangeModal === 'function' && document.getElementById('statusModalBackdrop')) {
            statusBtn.classList.remove('hidden');
            statusBtn.onclick = () => {
                closeTaskDetailsModal();
                setTimeout(() => openStatusChangeModal(task.id, task.title, task.status), 200);
            };
        } else {
            statusBtn.classList.add('hidden');
        }
    }

    // Show modal
    const backdrop = document.getElementById('taskDetailsBackdrop');
    const card = document.getElementById('taskDetailsCard');
    if (backdrop && card) {
        backdrop.classList.remove('hidden');
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
        }, 10);
    }
}

/**
 * Close Task Details Inspection Modal
 */
function closeTaskDetailsModal() {
    const backdrop = document.getElementById('taskDetailsBackdrop');
    const card = document.getElementById('taskDetailsCard');
    if (!backdrop || backdrop.classList.contains('hidden')) return;

    backdrop.classList.add('opacity-0');
    if (card) {
        card.classList.remove('scale-100');
        card.classList.add('scale-95');
    }
    setTimeout(() => {
        backdrop.classList.add('hidden');
    }, 200);
}
