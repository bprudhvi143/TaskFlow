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

/**
 * -------------------------------------------------------------
 * Real-time Instant Search System (Tasks & Users)
 * Parallel matched dropdown display & live table row filtering
 * -------------------------------------------------------------
 */

let liveSearchDebounceTimer = null;

function handleLiveSearch(query, type = 'tasks', scope = 'personal') {
    const trimmed = query.trim();
    const dropdown = document.getElementById('liveSearchDropdown');
    const clearBtn = document.getElementById('clearSearchBtn');

    if (clearBtn) {
        if (trimmed.length > 0) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }
    }

    // 1. Instant local client-side filter of visible table rows
    filterVisibleTableRows(trimmed, type);

    // 2. Hide dropdown if empty query
    if (trimmed.length === 0) {
        if (dropdown) {
            dropdown.classList.add('hidden');
            dropdown.innerHTML = '';
        }
        return;
    }

    // 3. Show dropdown with loading state
    if (dropdown) {
        dropdown.classList.remove('hidden');
        dropdown.innerHTML = `
            <div class="p-3 text-xs text-on-surface-variant flex items-center gap-2">
                <span class="material-symbols-outlined text-[16px] animate-spin text-primary">progress_activity</span>
                <span>Searching matched ${type}...</span>
            </div>
        `;
    }

    // 4. Debounce network fetch
    clearTimeout(liveSearchDebounceTimer);
    liveSearchDebounceTimer = setTimeout(() => {
        const baseUrl = window.BASE_URL || '';
        const endpoint = `${baseUrl}/public/actions/live-search.php?type=${encodeURIComponent(type)}&q=${encodeURIComponent(trimmed)}&scope=${encodeURIComponent(scope)}`;

        fetch(endpoint)
            .then(res => {
                if (!res.ok) throw new Error('Search failed');
                return res.json();
            })
            .then(data => {
                if (type === 'users') {
                    renderUserSearchResults(data.results || [], trimmed, dropdown);
                } else {
                    renderTaskSearchResults(data.results || [], trimmed, dropdown);
                }
            })
            .catch(err => {
                console.error('Live search error:', err);
                if (dropdown) {
                    dropdown.innerHTML = `
                        <div class="p-3 text-xs text-on-surface-variant">
                            Could not load search suggestions. Press Enter to search table.
                        </div>
                    `;
                }
            });
    }, 150);
}

/**
 * Filter visible table rows in real-time as user types
 */
function filterVisibleTableRows(query, type) {
    const q = query.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    if (!rows.length) return;

    rows.forEach(row => {
        if (!q) {
            row.style.display = '';
            return;
        }
        const text = row.textContent.toLowerCase();
        if (text.includes(q)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

/**
 * Render Matched Task Results Down in Dropdown
 */
function renderTaskSearchResults(tasks, query, dropdown) {
    if (!dropdown) return;

    if (!tasks || tasks.length === 0) {
        dropdown.innerHTML = `
            <div class="p-4 text-center">
                <p class="text-xs font-semibold text-on-surface">No matching tasks found</p>
                <p class="text-[11px] text-on-surface-variant mt-0.5">No tasks match "${escapeHtml(query)}". Try another keyword.</p>
            </div>
        `;
        return;
    }

    let html = `
        <div class="p-2.5 bg-surface-container/70 border-b border-outline-variant/30 flex items-center justify-between text-[11px]">
            <span class="font-bold text-primary uppercase tracking-wider flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">checklist</span>
                Matching Tasks (${tasks.length})
            </span>
            <span class="text-on-surface-variant text-[10px]">Click any task to inspect details</span>
        </div>
        <div class="divide-y divide-outline-variant/20 max-h-72 overflow-y-auto">
    `;

    tasks.forEach(task => {
        const highlightedTitle = highlightSearchKeyword(task.title, query);
        let statusClass = 'bg-amber-50 text-amber-800';
        if (task.status === 'Completed') statusClass = 'bg-emerald-50 text-emerald-800';
        else if (task.status === 'In Progress') statusClass = 'bg-sky-50 text-sky-800';

        let priorityClass = 'text-amber-800';
        if (task.priority === 'High') priorityClass = 'text-red-700 font-semibold';
        else if (task.priority === 'Low') priorityClass = 'text-outline';

        const safeTaskJson = JSON.stringify(task).replace(/"/g, '&quot;');

        html += `
            <div class="p-3 hover:bg-surface-container-low transition-colors cursor-pointer flex items-center justify-between gap-3 group"
                 onclick="selectLiveTaskResult(${safeTaskJson})">
                <div class="truncate flex-1">
                    <p class="text-xs font-semibold text-on-surface group-hover:text-primary transition-colors truncate">
                        ${highlightedTitle}
                    </p>
                    ${task.description ? `<p class="text-[11px] text-on-surface-variant truncate mt-0.5">${escapeHtml(task.description)}</p>` : ''}
                    ${task.owner_name ? `<p class="text-[10px] text-primary/80 mt-0.5 font-medium">Assignee: ${escapeHtml(task.owner_name)}</p>` : ''}
                </div>
                <div class="flex items-center gap-2 shrink-0 text-right">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold ${statusClass}">
                        ${task.status}
                    </span>
                    <span class="text-[10px] ${priorityClass}">
                        ${task.priority}
                    </span>
                    ${task.due_date ? `
                    <span class="text-[10px] text-on-surface-variant flex items-center gap-0.5">
                        <span class="material-symbols-outlined text-[12px]">calendar_today</span>
                        ${task.due_date}
                    </span>` : ''}
                </div>
            </div>
        `;
    });

    html += `
        </div>
        <div class="p-2 bg-surface-container-low text-center border-t border-outline-variant/30">
            <span class="text-[10px] text-on-surface-variant">Press Enter or click Filter to apply search to main table</span>
        </div>
    `;

    dropdown.innerHTML = html;
}

/**
 * Render Matched User Results Down in Dropdown
 */
function renderUserSearchResults(users, query, dropdown) {
    if (!dropdown) return;

    if (!users || users.length === 0) {
        dropdown.innerHTML = `
            <div class="p-4 text-center">
                <p class="text-xs font-semibold text-on-surface">No matching users found</p>
                <p class="text-[11px] text-on-surface-variant mt-0.5">No user found matching "${escapeHtml(query)}".</p>
            </div>
        `;
        return;
    }

    let html = `
        <div class="p-2.5 bg-surface-container/70 border-b border-outline-variant/30 flex items-center justify-between text-[11px]">
            <span class="font-bold text-primary uppercase tracking-wider flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">group</span>
                Matching Users (${users.length})
            </span>
            <span class="text-on-surface-variant text-[10px]">Click user to inspect workload</span>
        </div>
        <div class="divide-y divide-outline-variant/20 max-h-72 overflow-y-auto">
    `;

    users.forEach(user => {
        const highlightedName = highlightSearchKeyword(user.name, query);
        const highlightedEmail = highlightSearchKeyword(user.email, query);
        const initial = user.name ? user.name.charAt(0).toUpperCase() : 'U';

        let roleBadge = `<span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-surface-container text-on-surface-variant">User</span>`;
        if (user.email && user.email.toLowerCase() === 'prudhviboddeda5@gmail.com') {
            roleBadge = `<span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gradient-to-r from-emerald-800 to-teal-900 text-white">Master Admin</span>`;
        } else if (user.role === 'admin') {
            roleBadge = `<span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-primary text-on-primary">Admin</span>`;
        }

        const safeUserJson = JSON.stringify(user).replace(/"/g, '&quot;');

        html += `
            <div class="p-3 hover:bg-surface-container-low transition-colors cursor-pointer flex items-center justify-between gap-3 group"
                 onclick="selectLiveUserResult(${safeUserJson})">
                <div class="flex items-center gap-2.5 truncate flex-1">
                    <div class="w-7 h-7 rounded-lg bg-surface-container text-primary font-bold text-xs flex items-center justify-center shrink-0">
                        ${initial}
                    </div>
                    <div class="truncate">
                        <p class="text-xs font-semibold text-on-surface group-hover:text-primary transition-colors truncate">
                            ${highlightedName}
                        </p>
                        <p class="text-[11px] text-on-surface-variant truncate">${highlightedEmail}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <span class="text-[10px] font-medium text-on-surface-variant bg-surface-container px-2 py-0.5 rounded">
                        ${user.total_tasks || 0} tasks
                    </span>
                    ${roleBadge}
                </div>
            </div>
        `;
    });

    html += `
        </div>
        <div class="p-2 bg-surface-container-low text-center border-t border-outline-variant/30">
            <span class="text-[10px] text-on-surface-variant">Click any user above to open their task inspection modal</span>
        </div>
    `;

    dropdown.innerHTML = html;
}

/**
 * Handle selecting task from live search
 */
function selectLiveTaskResult(task) {
    const input = document.getElementById('searchInput');
    if (input) input.value = task.title;
    const dropdown = document.getElementById('liveSearchDropdown');
    if (dropdown) dropdown.classList.add('hidden');

    if (typeof openTaskDetailsModal === 'function') {
        openTaskDetailsModal(task);
    }
}

/**
 * Handle selecting user from live search
 */
function selectLiveUserResult(user) {
    const input = document.getElementById('searchInput');
    if (input) input.value = user.name;
    const dropdown = document.getElementById('liveSearchDropdown');
    if (dropdown) dropdown.classList.add('hidden');

    if (typeof openUserTasksModal === 'function') {
        openUserTasksModal(user);
    }
}

/**
 * Clear Live Search
 */
function clearLiveSearch(type = 'tasks') {
    const input = document.getElementById('searchInput');
    if (input) {
        input.value = '';
        input.focus();
    }
    const clearBtn = document.getElementById('clearSearchBtn');
    if (clearBtn) clearBtn.classList.add('hidden');

    const dropdown = document.getElementById('liveSearchDropdown');
    if (dropdown) {
        dropdown.classList.add('hidden');
        dropdown.innerHTML = '';
    }

    filterVisibleTableRows('', type);
}

/**
 * Highlight matching substring in text
 */
function highlightSearchKeyword(text, keyword) {
    if (!text || !keyword) return escapeHtml(text || '');
    const escapedText = escapeHtml(text);
    const escapedKw = escapeRegex(keyword);
    const regex = new RegExp(`(${escapedKw})`, 'gi');
    return escapedText.replace(regex, '<mark class="bg-amber-200 text-on-surface rounded px-0.5 font-bold">$1</mark>');
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function escapeRegex(str) {
    return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

// Global click listener to close search dropdown when clicking outside
document.addEventListener('click', (e) => {
    const container = document.getElementById('searchContainer');
    const dropdown = document.getElementById('liveSearchDropdown');
    if (container && dropdown && !container.contains(e.target)) {
        dropdown.classList.add('hidden');
    }
});
