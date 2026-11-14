# RTIH TaskFlow – Testing Matrix & Verification Results

**Project:** RTIH Task Tracker (TaskFlow)  
**Evaluation Scope:** Levels 1, 2, and 3 Functional, Security, and Edge Cases  
**Date of Testing:** November 14, 2026  

---

## 1. Test Execution Summary

| Suite | Category | Planned Tests | Passed | Failed | Status |
|---|---|---|---|---|---|
| **Suite A** | Level 1 – Basic Task Management | 5 | 5 | 0 | **PASSED** |
| **Suite B** | Level 2 – Status, Search & Validation | 6 | 6 | 0 | **PASSED** |
| **Suite C** | Level 3 – Authentication, Security & RBAC | 7 | 7 | 0 | **PASSED** |
| **Suite D** | Security & Tampering Resistance | 4 | 4 | 0 | **PASSED** |
| **Total** | | **22** | **22** | **0** | **100% Pass** |

---

## 2. Detailed Test Cases & Results

### Suite A: Level 1 – Basic Task Management

| Test ID | Test Scenario | Steps Taken | Expected Outcome | Actual Result | Status |
|---|---|---|---|---|---|
| **T-01** | Create a task with valid title and description | Click `Add Task`, enter title "Complete assignment documentation", description, submit. | Task is created in database; success toast appears; task appears at top of dashboard. | Row inserted with ID; success toast displayed; rendered on dashboard. | PASS |
| **T-02** | Display all saved tasks in list/table | Log in as Alex Morgan with 5 seeded tasks. | Dashboard displays all 5 tasks with details, priority badges, and status chips. | Table renders 5 tasks with correct titles, priorities, and dates. | PASS |
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

---

### Suite C: Level 3 – Authentication, Authorization & RBAC

| Test ID | Test Scenario | Steps Taken | Expected Outcome | Actual Result | Status |
|---|---|---|---|---|---|
| **T-12** | New user registration | Go to Create Account tab, enter name, unique email, password >= 8 chars, submit. | New user created with hashed password; auto-logged into dashboard with empty task state. | User created in `users` table; Bcrypt hash verified; redirected to clean dashboard. | PASS |
| **T-13** | Duplicate email registration | Attempt to register with existing email `alex@rtih.org`. | Registration rejected with descriptive error message. | "An account with this email address already exists" error displayed. | PASS |
| **T-14** | Invalid login credentials | Attempt sign-in with wrong password. | Access denied; friendly error message displayed. | "Invalid email address or password" displayed; passwords not leaked. | PASS |
| **T-15** | Unauthenticated dashboard access | In a clean browser tab, attempt to load `/public/index.php?page=dashboard`. | Access blocked; redirected to `/public/index.php?page=login` with notification. | Redirected to login with flash notice: "Please sign in to access your task dashboard." | PASS |
| **T-16** | Cross-user task isolation | User Alex (user_id=2) views their dashboard. | Tasks belonging to User Sarah (user_id=3) are NOT visible. | Only Alex's tasks are listed; Sarah's tasks are completely excluded. | PASS |
| **T-17** | Cross-user edit/delete tampering | User Alex attempts to send POST request to `task-save.php` with `task_id = 6` (owned by Sarah). | Server rejects with HTTP 403 Forbidden; Sarah's task remains untouched. | Request blocked; HTTP 403 returned; database record unmodified. | PASS |
| **T-18** | Standard user admin route access | Logged-in standard user navigates to `/public/index.php?page=admin-users`. | Access blocked; redirected to `/dashboard` with authorization warning. | Redirected with flash: "Unauthorized: Administrative access required." | PASS |

---

### Suite D: Security, CSRF & SQL Injection

| Test ID | Test Scenario | Steps Taken | Expected Outcome | Actual Result | Status |
|---|---|---|---|---|---|
| **T-19** | CSRF token validation | Submit task creation POST request with empty or invalid `csrf_token`. | Request rejected with security token error; no data inserted. | Blocked; flash: "Invalid security token (CSRF). Submission rejected." | PASS |
| **T-20** | SQL Injection attempt in search | Search with payload `' OR '1'='1' -- `. | Safely treated as literal search string via PDO prepared statement. | Query executed cleanly; returned 0 matching records without SQL syntax errors. | PASS |
| **T-21** | SQL Injection attempt in login | Login email: `admin@rtih.org' OR 1=1 --`. | Safely bound via PDO; no bypass occurs. | Login failed; treated as non-existent email literal. | PASS |
| **T-22** | XSS payload rendering | Create task with title `<script>alert('XSS')</script>`. | Script tags are escaped with `htmlspecialchars()` during HTML rendering. | Rendered as text `&lt;script&gt;alert('XSS')&lt;/script&gt;`; no JavaScript execution. | PASS |
