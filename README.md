# TaskFlow – Web Application

**Assignment:** Technical Assignment – Intern IT  
**Organization:** Ratan Tata Innovation Hub (RTIH)  
**Author:** Boddeda Prudhvi  
**Repository:** [https://github.com/bprudhvi143/TaskFlow.git](https://github.com/bprudhvi143/TaskFlow.git)  

---

## 1. Project Overview

**TaskFlow** is a modern, responsive task management web application designed and built for the RTIH Technical Assignment (Intern – IT). The system allows individual users and administrators to create, track, organize, search, and manage tasks through a structured workflow while strictly enforcing server-side validation, secure authentication, and role-based access control (RBAC).

The frontend design is a 1:1 realization of the finalized Google Stitch UI (`TaskFlow Direct`), styled with modern aesthetics, clean typography (Inter / Plus Jakarta Sans), subtle elevation, and responsive breakpoints.

---

## 2. Technology Stack

| Layer | Technology | Details |
|---|---|---|
| **Backend** | Core PHP (8.3+) | Clean procedural/modular MVC architecture. No external frameworks (no Laravel, no Symfony). |
| **Database** | MySQL (8.0+) / MariaDB | Relational InnoDB storage with foreign keys, cascades, and composite indexes. |
| **Database Access** | PDO (PHP Data Objects) | Parameterized prepared statements (`ATTR_EMULATE_PREPARES => false`) preventing SQL injection. |
| **Frontend** | HTML5, CSS3, Vanilla JS | Modern, accessible markup matching approved Google Stitch designs; Tailwind CSS via CDN. |
| **Server** | Apache (XAMPP) | Local development environment. |
| **Version Control** | Git | Managed via Git repository. |

---

## 3. Implemented Levels & Features

### Level 1 – Basic Task Management
- [x] MySQL database schema (`task_flow`) and relational tables (`users`, `tasks`).
- [x] Create a task with title, description, status, priority, and optional due date.
- [x] Display all user-owned tasks in a structured list/table.
- [x] Edit task details via floating modal dialog pre-filled with existing data.
- [x] Delete a task after modal confirmation dialog.
- [x] Real-time visual feedback toasts on create, update, and delete actions.

### Level 2 – Status, Search & Validation
- [x] Three lifecycle states: `Pending`, `In Progress`, and `Completed`.
- [x] Quick status popup modal beside task details allowing immediate status transition without opening the full edit form.
- [x] Sinking completed tasks: Completed tasks automatically sink to the bottom of the task list, preserving focus on active tasks.
- [x] Completed task highlighting: No strikethrough (`line-through`); completed tasks feature soft emerald background tints, checkmarks, and green accents.
- [x] Clickable metric filter cards: Total Tasks, In Progress, Completed, and Pending summary cards are interactive links that filter the task view.
- [x] Parallel Live Search: Debounced real-time dropdown displays matched results underneath the search bar while simultaneously filtering table rows in real-time.
- [x] Task Details Inspection Popup: Clicking any task title opens a rich modal dialog displaying the full task title, complete multi-line description, priority badge, status chip, created date, and due date.
- [x] Filter tasks by status dropdown (`Pending`, `In Progress`, `Completed`).
- [x] Filter tasks by priority dropdown (`High`, `Medium`, `Low`).
- [x] Dual-layer validation (client-side character counter and required checks + server-side validation).
- [x] Deadline Date Protection: Due date is restricted to today or future dates on both client-side (`min="YYYY-MM-DD"`) and server-side; past dates cannot be set as deadlines.
- [x] Friendly error messaging matching Stitch validation states (empty title rejection, 150-char max enforcement, past deadline rejection).
- [x] Action Column Polish: Redundant static checkmark beside delete icon removed for a clean, distraction-free UI.

### Level 3 – Authentication, Authorization & Security
- [x] Unified login and user registration flows (registration strictly restricted to `user` role).
- [x] 5 Industry-Standard Strong Password Rules:
  1. Minimum length of 8 characters (12+ recommended).
  2. Uppercase letter required (`A-Z`).
  3. Lowercase letter required (`a-z`).
  4. Number / digit required (`0-9`).
  5. Special symbol required (`!@#$%^&*()_+-=[]{}|;:,.<>?`).
  6. Personal information restriction: Password cannot contain any part of the user's name or email address prefix.
- [x] Unique strong passwords seeded for all default test accounts matching the 5 criteria.
- [x] Interactive real-time password strength meter and checklist on the registration screen.
- [x] Secure password storage using `password_hash()` (Bcrypt).
- [x] Password verification using `password_verify()`.
- [x] Session-based authentication protecting all dashboard routes.
- [x] Session fixation prevention via `session_regenerate_id(true)` upon login.
- [x] Server-side ownership boundary: Queries strictly enforce `WHERE user_id = :session_user_id`. Users cannot view, modify, or delete another user's tasks by tampering with IDs or URL parameters.
- [x] 3-Tier Role-Based Access Control (RBAC):
  - **Standard User:** Restricted strictly to their own personal workspace and tasks.
  - **Administrator:** Elevated access to `/admin/tasks` (global system task oversight with owner details), `/admin/users` (user directory with task activity and role modification), and ability to promote standard users or demote administrators, alongside personal task management.
  - **Master Administrator (`Boddeda Prudhvi`):** Root authority with permanent, immutable role protection (`Master Protected`). Cannot be modified or demoted by any user or administrator.
- [x] Self-demotion lockout check: Administrators are prevented from demoting their own accounts to `user`.
- [x] Admin User Workload Inspection Modal: Clicking on any user in the User Directory displays their assigned tasks, status breakdown, and workload counters.
- [x] Official TaskFlow / RTIH Branding: Official logo integrated across sidebar navigation, login/registration screen, and browser favicon.
- [x] CSRF Protection: Cryptographically secure tokens (`$_SESSION['csrf_token']`) verified with `hash_equals()` on all state-changing POST requests.
- [x] XSS Prevention: Strict output escaping using `htmlspecialchars($string, ENT_QUOTES, 'UTF-8')` and client-side `escapeHtml()` sanitization.

### Optional Bonus Features
- [x] Clickable summary metric counter cards (*Total Tasks*, *In Progress*, *Completed*, *Pending*).
- [x] Priority levels (*High*, *Medium*, *Low*) with distinct color-coded badges.
- [x] Calendar due dates.
- [x] Server-side pagination with dynamic page calculations.
- [x] Unified, consistent application footer: `TaskFlow - Task tracking application`.

---

## 4. Pre-configured Test Accounts

The database comes pre-seeded with realistic, everyday test profiles adhering to the strong password security policy with unique passwords per account:

| Role | Name | Email | Password | Sample Tasks / Scope |
|---|---|---|---|---|
| **Master Administrator** | Boddeda Prudhvi | `prudhviboddeda5@gmail.com` | `FalconShield#2026!Master` | Master authority: 4 personal tasks, full administrative oversight (`/admin/tasks`, `/admin/users`), permanently immutable role. |
| **Standard User** | Kalyan Varma | `kalyan.varma@gmail.com` | `SapphireWave@2026#Track` | 5 everyday tasks (groceries, electricity bill, doctor visit, car service, book renewal). |
| **Standard User** | Ananya Rao | `ananya.rao@gmail.com` | `EmeraldPeak#2026$Nova` | 3 everyday tasks (insurance policy, AC servicing, water filter maintenance). |
| **Standard User** | Suresh Reddy | `suresh.reddy@gmail.com` | `GoldenHorizon@2026!Hub` | 3 everyday tasks (Wi-Fi recharge, medicine restock, property tax payment). Demonstrates cross-user isolation. |

*Quick-fill profile buttons are provided directly on the sign-in screen for one-click testing.*

---

## 5. Installation & Setup Guide (XAMPP)

### Prerequisites
1. [XAMPP](https://www.apachefriends.org/) (with Apache and MySQL / MariaDB).
2. Web browser (Chrome, Edge, Firefox).

### Step 1: Clone / Place the Project
Place or clone the project inside your XAMPP `htdocs` directory:
```bash
cd C:\xampp\htdocs
git clone https://github.com/bprudhvi143/TaskFlow.git task-tracker
```
Or ensure the workspace is located at `C:\xampp\htdocs\task-tracker`.

### Step 2: Configure Environment Variables
Copy `.env.example` to `.env`:
```bash
cp .env.example .env
```
Open `.env` and verify your MySQL settings (default XAMPP credentials):
```ini
APP_NAME=TaskFlow
APP_ENV=development
APP_URL=http://localhost/task-tracker

DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=task_flow
DB_USER=root
DB_PASS=
```
*(If your MySQL root user has a password, enter it in `DB_PASS`)*.

### Step 3: Import the Database Schema & Seed Data
You can import the database using either **phpMyAdmin** or the **MySQL Command Line**:

#### Option A: Using phpMyAdmin (Browser)
1. Start **Apache** and **MySQL** in the XAMPP Control Panel.
2. Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin) in your browser.
3. Click the **Import** tab at the top.
4. Click **Choose File** and select `database/database.sql` from this project.
5. Click **Import** at the bottom.

#### Option B: Using MySQL CLI
```bash
mysql -u root -p < database/database.sql
```

### Step 4: Run the Application
Open your browser and navigate to:
```
http://localhost/task-tracker/
```
You will be directed to the authentication screen. Click one of the quick test buttons or log in with:
- **Email:** `kalyan.varma@gmail.com`
- **Password:** `SapphireWave@2026#Track`
Or for Administrator oversight:
- **Email:** `prudhviboddeda5@gmail.com`
- **Password:** `FalconShield#2026!Master`

---

## 6. Directory Structure

```
task-tracker/
│
├── app/
│   ├── config/
│   │   ├── app.php               # Session security, CSRF helpers, output escaping, .env loader
│   │   └── database.php          # PDO connection factory singleton
│   ├── controllers/
│   │   ├── AuthController.php    # Sign in, registration, session teardown
│   │   ├── TaskController.php    # CRUD operations, status management, search/filter
│   │   └── AdminController.php   # System oversight, user role management
│   ├── models/
│   │   ├── User.php              # User lookup, creation, role updates
│   │   └── Task.php              # Ownership-scoped task queries & metrics
│   └── views/
│       ├── layouts/
│       │   ├── header.php        # Head, Tailwind CSS config, Google Fonts
│       │   ├── sidebar.php       # Dynamic navigation sidebar (Admin vs User)
│       │   ├── toast.php         # Floating success/error notification toasts
│       │   └── footer.php        # Shared footer and script includes
│       ├── auth/
│       │   └── login-register.php# Tabbed authentication interface
│       ├── tasks/
│       │   ├── index.php         # Main user dashboard with metrics and task list
│       │   └── components/
│       │       ├── modal-task.php        # Add & Edit Task modal dialog
│       │       ├── modal-task-details.php# Read-only task details popup dialog
│       │       ├── modal-status.php      # Quick task lifecycle status dialog
│       │       └── modal-delete.php      # Delete confirmation modal dialog
│       └── admin/
│           ├── tasks.php         # Admin all-tasks view with owner details
│           └── users.php         # Admin user directory, task breakdown modal & role modal
│
├── public/
│   ├── index.php                 # Application Front Controller & router
│   ├── actions/
│   │   ├── auth.php              # Action handler: login, register, logout
│   │   ├── live-search.php       # Action handler: live parallel search dropdown
│   │   ├── task-save.php         # Action handler: create/update task
│   │   ├── task-delete.php       # Action handler: delete task
│   │   ├── task-status.php       # Action handler: quick status toggle
│   │   └── admin-role.php        # Action handler: admin role updates
│   └── assets/
│       ├── css/
│       │   └── custom.css        # Subtle scrollbars and utility fallbacks
│       ├── images/
│       │   └── logo.png          # Official TaskFlow / RTIH branding logo
│       └── js/
│           └── app.js            # Modal controls, live search, character counter, toast triggers
│
├── database/
│   ├── schema.sql                # Table definitions with constraints and indexes
│   ├── seed.sql                  # Initial seed data for users and tasks
│   └── database.sql              # Combined 1-click importable SQL file
│
├── docs/
│   ├── testing-results.md        # Detailed test matrix and execution results
│   └── backup-restore-guide.md   # Backup and restore documentation
│
├── tests/
│   └── test_role_permissions.php # Automated CLI test suite for 3-tier RBAC permissions
│
├── testsprite_tests/             # TestSprite automated E2E test plans and PRD artifacts
│
├── .env.example                  # Environment template
├── .gitignore                    # Git ignore file
├── PROJECT_CONTEXT.md            # Comprehensive project blueprint and audit notes
└── README.md                     # Documentation and setup instructions
```

---

## 7. Roles & Permissions (Level 3 RBAC)

The application implements a 3-tier Role-Based Access Control hierarchy:

| Capability / Action | Standard User | Administrator | Master Administrator (`Boddeda Prudhvi`) |
|---|:---:|:---:|:---:|
| Register new account | Yes | Yes | Yes |
| Sign in & view personal dashboard | Yes | Yes | Yes |
| Create, edit, delete personal tasks | Yes | Yes | Yes |
| Update task status (Pending / In Progress / Completed) | Yes | Yes | Yes |
| View tasks belonging to other users | **No** (Enforced server-side) | Yes (via `/admin/tasks`) | Yes (via `/admin/tasks`) |
| Inspect another user's task workload & details | **No** (Unauthorized) | Yes (via User Directory modal) | Yes (via User Directory modal) |
| Force delete any system task in oversight | **No** (Unauthorized) | Yes | Yes |
| Access Admin All Tasks console | **No** (Redirected to dashboard) | Yes | Yes |
| Access Admin User Directory & Access Control | **No** (Redirected to dashboard) | Yes | Yes |
| Promote standard user to Administrator (`user -> admin`) | **No** (Unauthorized) | Yes | Yes |
| Demote Administrator to Standard User (`admin -> user`) | **No** (Unauthorized) | Yes (other admins) | Yes (other admins) |
| Modify Master Administrator role | **No** (Unauthorized) | **No** (Permanently Protected) | **No** (Permanently Protected) |
| Demote own administrator account (Self-lockout) | N/A | **No** (Blocked server-side) | **No** (Blocked server-side) |

---

## 8. Database Backup & Restore Guide

### Creating a Backup (Dump)
From the terminal or XAMPP Shell:
```bash
mysqldump -u root -p task_flow > task_flow_backup.sql
```

### Restoring from Backup
```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS task_flow;"
mysql -u root -p task_flow < task_flow_backup.sql
```

Detailed instructions for automated cron backups and disaster recovery are provided in `docs/backup-restore-guide.md`.

---

## 9. AI Coding Assistant Disclosure

In compliance with assignment submission guidelines:
- **Assistant Used:** Google Antigravity (AGY) AI Assistant.
- **Role:** Architectural planning, Google Stitch MCP design extraction, and scaffolding MVC components.
- **Manual Review & Critical Changes Made to Generated Logic:**
  1. *Master Admin Protection & Role Guard:* Standard code generation treated all admin accounts homogeneously. I identified this security gap and implemented a permanent protection mechanism in `AdminController.php` and `app.php` ensuring that the Master Administrator (`Boddeda Prudhvi` / `prudhviboddeda5@gmail.com`) cannot be demoted or altered by any user or administrator. Furthermore, I enforced self-demotion lockout checks so no administrator can accidentally lock themselves out.
  2. *Rejection of Insecure In-Interface Password Reset:* When an initial prototype generated an in-browser password reset flow that displayed tokens on-screen without an external email/SMS delivery service, I recognized that anyone could reset any user's password directly from the UI. Following secure engineering principles, I rejected this fragile mechanism and completely removed it in favor of maintaining strict authentication integrity.
  3. *Deadline Date Guard:* When observing date picker functionality, I noticed standard date pickers permitted arbitrary past dates. I enforced both client-side `min="YYYY-MM-DD"` attributes and robust server-side validation rejecting any due date before the current calendar day.

---

## 10. Test Execution Matrix & Bug Fixes (Manual & TestSprite)

A combined verification strategy was used consisting of manual exploratory testing and automated testing with the **TestSprite MCP** suite and custom PHPUnit/CLI test suites.

### Test Execution Summary

| Test ID | Modality | Category | Scenario / Steps | Expected Outcome | Actual Result | Status |
|---|---|---|---|---|---|:---:|
| **TS-01** | TestSprite MCP | Security | Anti-CSRF Token Validation | Automated POST to state-changing routes without CSRF token. | Request rejected; security redirect triggered; 0 rows changed. | Rejected with: *"Invalid security token (CSRF)"*. | **PASS** |
| **TS-02** | TestSprite MCP | Validation | Boundary & Empty Title Rejection | Automated POST with empty title and title with 165 characters. | Server rejects submission; friendly error returned. | Rejected with friendly error flash; no DB insert. | **PASS** |
| **TS-03** | TestSprite MCP | Security | Cross-User ID Tampering | User Kalyan Varma submits POST to edit/delete task owned by Ananya Rao. | Backend enforces `WHERE user_id = :session_user_id`; returns HTTP 403. | Blocked with HTTP 403 Forbidden; DB untouched. | **PASS** |
| **TS-04** | TestSprite MCP | Security | SQLi & XSS Payload Injection | Injected `' OR '1'='1' --` into search/login, and `<script>` into task title. | PDO prepared statements safely escape SQL; output sanitized. | Treated as safe literals; script escaped as `&lt;script&gt;`. | **PASS** |
| **TS-05** | TestSprite MCP | Validation | Server-Side Past Deadline Bypass | Automated POST bypassing HTML5 date picker with past date `2026-11-13`. | Server-side validation rejects date earlier than current calendar day. | Rejected with: *"Task deadline must be today or a future date"*. | **PASS** |
| **T-01** | Manual | Level 1 CRUD | Task Creation & Toast Notification | Clicked `Add Task`, entered valid details, priority `High`, submitted. | Task stored in DB; success toast appears; renders at top of list. | Verified in DB; toast displayed immediately. | **PASS** |
| **T-02** | Manual | Level 1 CRUD | Task Edit & Pre-filled Dialog | Clicked Edit (pencil) icon, updated task description and priority. | Edit modal opened pre-filled; updated MySQL row on submit. | Changes saved to MySQL; table updated; toast confirmed. | **PASS** |
| **T-03** | Manual | Level 1 CRUD | Task Deletion & Confirmation Modal | Clicked Delete (trash) icon; tested Cancel, then confirmed Delete. | Cancel leaves record untouched; Confirm cleanly removes task from DB. | Record cleanly removed from DB; table updated without error. | **PASS** |
| **T-04** | Manual | Level 2 Status | Task Lifecycle & Sinking Completed Tasks | Changed status from Pending $\rightarrow$ In Progress $\rightarrow$ Completed. | Completed tasks sink to bottom with emerald accent; no strikethrough. | Task sank to bottom; styled with emerald accent and checkmark. | **PASS** |
| **T-05** | Manual | Level 2 Search | Parallel Real-Time Search | Typed keyword into search bar on Dashboard and Admin views. | Matched records appear in dropdown below while table filters in parallel. | Instant matched items rendered in dropdown and table rows filtered. | **PASS** |
| **T-06** | Manual | Level 2 UI | Task Details Inspection Modal | Clicked on task title in table. | Modal opens displaying full title, complete description, priority, dates. | Rich task details modal rendered cleanly with full text. | **PASS** |
| **T-07** | Manual | Level 3 Admin | Admin User Workload Inspection | In Admin User Directory, clicked on user Kalyan Varma. | Dedicated modal opens showing user's 5 tasks and workload counters. | User task details modal rendered cleanly with workload counters. | **PASS** |
| **T-08** | PHP Runner | Level 3 RBAC | 3-Tier RBAC & Master Admin Lockout | Ran `tests/test_role_permissions.php` testing secondary admin role ops. | Secondary admin can promote user/demote admin; cannot touch Master Admin. | 5/5 assertions passed; Master Admin permanently protected. | **PASS** |

### Summary of Key Bugs Found & Fixed
1. **Redundant Checkmark in Action Column:** Removed the non-functional checkmark icon beside the delete button, keeping only Edit and Delete icons.
2. **Seed Account Passwords:** Re-hashed and updated all seed accounts with unique, complex passwords satisfying all 5 strong password criteria.
3. **User Workload Inspection:** Added an interactive User Tasks Modal to the Admin User Directory to view any user's task list and workload metrics.
4. **Task Description Truncation:** Implemented a read-only Task Details Modal opened on title click to read complete task details and descriptions.
5. **Past Deadline Selection:** Enforced dynamic `min="YYYY-MM-DD"` on frontend date inputs and added server-side validation rejecting past deadlines.
6. **Insecure Password Reset:** Removed insecure in-interface password reset flow that printed tokens on-screen without email verification.
7. **Master Admin Protection vs Admin Role Promotion:** Configured Master Admin (`Boddeda Prudhvi`) as permanently immutable (`Master Protected`), while allowing standard admins to promote users and demote other admins, with self-demotion blocked.
8. **Server-Side Date Bypass (TestSprite):** Added backend comparison in `TaskController.php` against `today` at midnight to prevent direct POST bypasses.
9. **XSS Reflection in Modals (TestSprite):** Added client-side `escapeHtml()` sanitization in `app.js` and `users.php` before injecting dynamic text into modal elements.

---

## 11. Known Limitations

1. **Email Service for Password Reset:**
   - In accordance with production security standards, an in-browser password reset that displays reset tokens or links directly on the screen without external verification was omitted. A production deployment requires an external transactional email provider (such as SMTP, Amazon SES, or SendGrid) to securely deliver time-limited tokens to verified user email addresses.
2. **File Attachments & Sub-tasks:**
   - Tasks currently store titles, rich descriptions, priorities, deadlines, and statuses; file uploads (e.g., attachments or PDFs) and nested sub-tasks were not part of the initial assignment scope.
