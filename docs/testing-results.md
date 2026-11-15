# RTIH TaskFlow – Testing Matrix & Verification Results

**Project:** RTIH Task Tracker (TaskFlow)  
**Evaluation Scope:** Levels 1, 2, and 3 Functional, Security, and Edge Cases  
**Date of Testing:** November 14, 2026  

---

## 1. Test Execution Summary

| Suite | Category | Planned Tests | Passed | Failed | Status |
|---|---|---|---|---|---|
| **Suite A** | Level 1 – Basic Task Management | 6 | 6 | 0 | **PASSED** |
| **Suite B** | Level 2 – Status, Search & Validation | 8 | 8 | 0 | **PASSED** |
| **Suite C** | Level 3 – Authentication, Security & RBAC | 11 | 11 | 0 | **PASSED** |
| **Suite D** | Security & Tampering Resistance | 5 | 5 | 0 | **PASSED** |
| **Suite E** | Date Restrictions & Advanced UI Controls | 3 | 3 | 0 | **PASSED** |
| **Total** | | **33** | **33** | **0** | **100% Pass** |

---

## 2. Detailed Test Cases & Results

### Suite A: Level 1 – Basic Task Management

| Test ID | Test Scenario | Steps Taken | Expected Outcome | Actual Result | Status |
|---|---|---|---|---|---|
| **T-01** | Create a task with valid title and description | Click `Add Task`, enter title "Complete assignment documentation", description, submit. | Task is created in database; success toast appears; task appears at top of dashboard. | Row inserted with ID; success toast displayed; rendered on dashboard. | PASS |
| **T-02** | Display all saved tasks in list/table | Log in as Kalyan Varma with 5 seeded everyday tasks. | Dashboard displays all 5 tasks with details, priority badges, and status chips. | Table renders 5 tasks with correct titles, priorities, and dates. | PASS |
| **T-03** | Edit task title and description | Click Edit icon on task, update title to "Updated assignment title", submit. | Modal opens with pre-filled data; upon submission, record updates in MySQL; toast confirms update. | Changes saved to MySQL; updated title displayed in table; toast confirmed. | PASS |
| **T-04** | Delete task with confirmation | Click Delete icon on a task, inspect confirmation modal, click "Delete Task". | Confirmation modal displays task title; on confirm, task is removed from DB. | Modal displayed task title; row removed from DB; table updated without error. | PASS |
| **T-05** | Cancel task deletion | Click Delete icon, then click "Cancel" or press Escape key. | Modal closes; task remains in database. | Modal closed; record untouched in MySQL. | PASS |

---

### Suite B: Level 2 – Status, Search & Validation

| Test ID | Test Scenario | Steps Taken | Expected Outcome | Actual Result | Status |
|---|---|---|---|---|---|
| **T-06** | Task lifecycle status transitions | Edit a Pending task to In Progress; then click the Check icon to Mark as Completed. | Task status transitions correctly: `Pending` -> `In Progress` -> `Completed`. | Status updated and verified in database; badge color updated. | PASS |
| **T-07** | Search tasks by title keyword | Enter "schema" in search input and submit. | Only tasks containing "schema" in title or description are displayed. | Returned 1 matching task: "Design and review database schema". | PASS |
| **T-08** | Filter tasks by status dropdown | Select "In Progress" in status dropdown. | Only tasks with `In Progress` status are returned. | Filtered to show only In Progress tasks; counter matches count. | PASS |
| **T-09** | Filter tasks by priority dropdown | Select "High" in priority dropdown. | Only tasks with `High` priority are returned. | Filtered list displayed only High priority items. | PASS |
| **T-10** | Reject empty task title (Client & Server) | In Add Task modal, leave title empty and submit. | Form blocks submission client-side; server validation redirects with error if bypassed. | Form rejected empty submission; error alert displayed. | PASS |
| **T-11** | Enforce 150-character maximum title | Enter title with 160 characters. | Title counter turns red (`160 / 150`); submission rejected by server. | Server returned error: "Task title exceeds maximum allowed length of 150 characters". | PASS |
| **T-25** | Quick Status Modal Transition | Click "Change status" / "Mark as Completed" popup button beside task details. | Modal appears with 3 status options; selecting "Completed" updates status immediately without opening full edit modal. | Status transitions to Completed; task updates in MySQL; toast confirms. | PASS |
| **T-26** | Completed Tasks Sinking & Highlighting | Mark a task as Completed on Kalyan Varma's dashboard. | Completed task moves to bottom of list; displays emerald highlight and checkmark; no strikethrough. | Task sank to bottom; styled with emerald tint and checkmark icon. | PASS |
| **T-27** | Clickable Metric Filter Cards | Click on "In Progress" summary card on dashboard. | Dashboard filters to show only In Progress tasks. | Filter applied (`status=In+Progress`); URL and list updated accurately. | PASS |

---

### Suite C: Level 3 – Authentication, Authorization & RBAC

| Test ID | Test Scenario | Steps Taken | Expected Outcome | Actual Result | Status |
|---|---|---|---|---|---|
| **T-12** | New user registration | Go to Create Account tab, enter Telugu user name, unique email, strong password meeting 5 criteria, submit. | New user created with Bcrypt hash; role forced to `user`; auto-logged into clean dashboard. | User created in `users` table with role='user'; redirected to dashboard. | PASS |
| **T-13** | Duplicate email registration | Attempt to register with existing email `kalyan.varma@gmail.com`. | Registration rejected with descriptive error message. | "An account with this email address already exists" error displayed. | PASS |
| **T-14** | Invalid login credentials | Attempt sign-in with wrong password. | Access denied; friendly error message displayed. | "Invalid email address or password" displayed; passwords not leaked. | PASS |
| **T-15** | Unauthenticated dashboard access | In a clean browser tab, attempt to load `/public/index.php?page=dashboard`. | Access blocked; redirected to `/public/index.php?page=login` with notification. | Redirected to login with flash notice: "Please sign in to access your task dashboard." | PASS |
| **T-16** | Cross-user task isolation | User Kalyan Varma views their dashboard. | Tasks belonging to User Suresh Reddy or Ananya Rao are NOT visible. | Only Kalyan's tasks are listed; other users' tasks are completely excluded. | PASS |
| **T-17** | Cross-user edit/delete tampering | User Kalyan attempts to send POST request to `task-save.php` with `task_id = 10` (owned by Ananya Rao). | Server rejects with HTTP 403 Forbidden; Ananya's task remains untouched. | Request blocked; HTTP 403 returned; database record unmodified. | PASS |
| **T-18** | Standard user admin route access | Logged-in standard user navigates to `/public/index.php?page=admin-users`. | Access blocked; redirected to `/dashboard` with authorization warning. | Redirected with flash: "Unauthorized: Administrative access required." | PASS |
| **T-23** | 5 Strong Password Rules Validation | Attempt to register with password missing uppercase, number, or special symbol (`secretpass`). | Form blocks submission and server returns error listing missing criteria. | Rejected with: "Password must include at least one uppercase letter, one lowercase letter, one number, and one special character." | PASS |
| **T-24** | Personal Info Restriction in Password | Attempt to register as "Kalyan Varma" (`kalyan@gmail.com`) with password `Kalyan@2026!`. | Server validation detects user's name in password and rejects. | Rejected with: "Password cannot contain parts of your name or email address." | PASS |
| **T-28** | Admin User Task Inspection Modal | In Admin User Directory, click on user Kalyan Varma (or "Tasks" button). | Modal opens displaying Kalyan Varma's 5 tasks with titles, status chips, priorities, and due dates. | User task details modal rendered cleanly with workload counters; matches DB. | PASS |
| **T-29** | Task Details Inspection Popup | Click on any task in the tasks list on User Dashboard or Admin All Tasks. | Popup modal opens showing full task name, full description, priority, status badge, due date, and created timestamp. | Task details popup renders cleanly; allows changing status or opening edit form. | PASS |
| **T-32** | Role Hierarchy & Master Admin Permanent Protection | Promoted administrators change standard users to admin and admin to user, and attempt to change Master Admin (`Boddeda Prudhvi`). | All administrators can promote standard users to administrators and demote administrators to standard users. Master Administrator (`Boddeda Prudhvi`) is permanently protected from role changes (UI renders `Master Protected`; backend aborts with *"Action prohibited: The Master Administrator (Boddeda Prudhvi) role is permanent and cannot be modified by any administrator"*). Self-demotion of active administrator accounts is blocked. | Standard admins successfully promoted users and demoted admins; Master Admin role modification blocked; self-demotion blocked. | PASS |

---

### Suite D: Security, CSRF & SQL Injection

| Test ID | Test Scenario | Steps Taken | Expected Outcome | Actual Result | Status |
|---|---|---|---|---|---|
| **T-19** | CSRF token validation | Submit task creation POST request with empty or invalid `csrf_token`. | Request rejected with security token error; no data inserted. | Blocked; flash: "Invalid security token (CSRF). Submission rejected." | PASS |
| **T-20** | SQL Injection attempt in search | Search with payload `' OR '1'='1' -- `. | Safely treated as literal search string via PDO prepared statement. | Query executed cleanly; returned 0 matching records without SQL syntax errors. | PASS |
| **T-21** | SQL Injection attempt in login | Login email: `prudhviboddeda5@gmail.com' OR 1=1 --`. | Safely bound via PDO; no bypass occurs. | Login failed; treated as non-existent email literal. | PASS |
| **T-22** | XSS payload rendering | Create task with title `<script>alert('XSS')</script>`. | Script tags are escaped with `htmlspecialchars()` during HTML rendering. | Rendered as text `&lt;script&gt;alert('XSS')&lt;/script&gt;`; no JavaScript execution. | PASS |

---

### Suite E: Date Restrictions & Advanced UI Controls

| Test ID | Test Scenario | Steps Taken | Expected Outcome | Actual Result | Status |
|---|---|---|---|---|---|
| **T-30** | Task Deadline Restriction to Today or Future Date | Attempt to submit task with a past due date (`2026-11-13`). | Client-side picker restricts past dates with `min="YYYY-MM-DD"`. If bypassed via direct HTTP POST, server-side validation rejects with error message: *"Task deadline must be today or a future date. Past dates cannot be set as a deadline."* | Server rejected past date with friendly flash message; valid tasks with today or future deadlines created successfully. | PASS |
| **T-31** | Action Column UI Cleanup | Inspect task rows on User Dashboard and Admin System Tasks. | Action column contains only the essential Edit (pencil) and Delete (trash) icons. Redundant static checkmark beside delete is removed; status changes handled cleanly via the status chip. | Redundant checkmark icon completely removed from Action column. | PASS |
| **T-33** | Live Search with Parallely Displayed Dropdown | Type keyword in search bar on Dashboard, Admin Tasks, or Admin Users. | Real-time dropdown appears directly underneath displaying matched records with keyword highlighting, status/role badges, priorities, and deadlines, while visible table rows filter in parallel. | Instant matched results rendered down in sleek dropdown and table filtered in real-time. | PASS |

---

## 3. TestSprite MCP Testing Suite Integration

| Phase | Tool Name | Scope & Execution | Output Artifact | Status |
|---|---|---|---|---|
| **Phase 1** | `testsprite_bootstrap` | Initialized TestSprite workspace and verified server endpoint `http://localhost:80/task-tracker/public/index.php`. | `testsprite_tests/tmp/config.json` | **COMPLETED** |
| **Phase 2** | `testsprite_generate_code_summary` | Extracted full tech stack (Core PHP 8.3, MySQL, Tailwind, Vanilla JS) and routes. | `testsprite_tests/tmp/code_summary.yaml` | **COMPLETED** |
| **Phase 3** | `testsprite_generate_standardized_prd` | Generated structured PRD requirements for user roles, task lifecycle, and security controls. | `testsprite_tests/standard_prd.json` | **COMPLETED** |
| **Phase 4** | `testsprite_generate_frontend_test_plan` | Generated comprehensive end-to-end frontend test plans covering user login, task CRUD, status change, and validation flows. | `testsprite_tests/testsprite_frontend_test_plan.json` | **COMPLETED** |
| **Phase 5** | `testsprite_generate_code_and_execute` | Executed targeted test scenarios against local Apache/XAMPP server. | `testsprite_tests/tmp/mcp.log` | **COMPLETED** |

---

## 4. Summary of Bugs Found and Fixed

During the end-to-end development, testing, and hardening phases, several bugs, UI anomalies, and edge cases were discovered and resolved:

| Bug # | Severity | Defect Description | Root Cause | Fix & Resolution |
|---|---|---|---|---|
| **BUG-01** | **Critical (Security)** | Admin could demote their own account, causing administrative lockout. | `AdminController::updateUserRole()` did not check if the target `user_id` matched `$_SESSION['user']['id']`. | Added guard: `if ($targetUserId === (int) $currentUser['id']) { set_flash('error', 'You cannot change your own administrative role.'); }`. |
| **BUG-02** | **High (Data Integrity)** | Task deadlines could be set to arbitrary past dates. | `TaskController::create()` and `update()` only checked date regex format without verifying `$dueDate >= date('Y-m-d')`. | Added server-side validation: if `$dueDate < date('Y-m-d')`, rejected with *"Task deadline must be today or a future date. Past dates cannot be set as a deadline."* Added client-side HTML5 `min="<?= date('Y-m-d') ?>"` and JS submit listener validation. |
| **BUG-03** | **Medium (UI / UX)** | Completed tasks were struck through, hindering readability. | CSS class `line-through opacity-60` was applied to title and description when `status === 'Completed'`. | Replaced strikethrough with a clean emerald checkmark badge, soft background accent, and repositioned completed tasks to the bottom of the active list. |
| **BUG-04** | **Medium (UX)** | Changing task status required opening the full task edit form. | No lightweight status transition mechanism existed in the table view. | Added a Quick Status Change modal triggered by clicking the status chip next to the title, enabling instant status updates without editing task details. |
| **BUG-05** | **Low (Visual Polish)** | Redundant tick mark beside delete button in Actions column. | An extra static check icon was present in the Actions column template alongside the edit and delete action buttons. | Removed the redundant checkmark icon from both User Dashboard and Admin All Tasks views. |
| **BUG-06** | **High (Security)** | Weak passwords (e.g. `password123`) permitted during registration. | Regex validation only checked `strlen >= 6` without character complexity rules. | Implemented 5 strict password rules: minimum 8 characters, upper + lower case letters, numbers, special characters, and exclusion of user name/email tokens. |
| **BUG-07** | **High (Security)** | Cross-site Request Forgery (CSRF) vulnerability on unauthenticated forms. | Forms lacked anti-forgery token verification. | Implemented cryptographically secure CSRF tokens (`bin2hex(random_bytes(32))`) generated per session and validated on all POST action handlers. |
| **BUG-08** | **High (Authorization)** | Insecure Direct Object Reference (IDOR) on task update and deletion. | Backend queries did not scope `WHERE id = :id AND user_id = :user_id`. | Hardened all SQL queries in `Task::update()` and `Task::delete()` to enforce `user_id` ownership on the server side regardless of manipulated client payloads. |
| **BUG-09** | **Medium (Navigation)** | Clicking dashboard status metric cards did not filter tasks. | Metric cards were non-interactive static `<div>` containers. | Converted metric cards into clickable navigation links appending `?page=dashboard&status={Status}` to seamlessly filter tasks. |
| **BUG-10** | **Low (Display)** | Clicking a user row in Admin Directory showed no task details. | Admin user list had no detailed workload drill-down modal. | Added `modal-user-tasks.php` with dynamic JS loader displaying the selected user's active tasks, due dates, and priorities. |

