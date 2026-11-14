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
            if (typeof closeRoleModal === 'function') {
                closeRoleModal();
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
