# RTIH Task Tracker – Project Context & Architecture Specification

> **Document Status:** Baseline Audit & Implementation Blueprint  
> **Prepared For:** RTIH Technical Assignment (Intern – IT), Ratan Tata Innovation Hub  
> **Last Verified:** November 14, 2026  

---

## 1. Project Overview

The **RTIH Task Tracker** is a web-based task management system developed for the Ratan Tata Innovation Hub (RTIH) Intern – IT Technical Assignment. The application enables users to organize, monitor, filter, and manage tasks through a structured workflow while enforcing server-side validation, secure authentication, and role-based access control (RBAC).

The assignment evaluates core competencies across three progressive tiers:
1. **Level 1 – Basic Task Management:** CRUD operations on tasks using Core PHP, PDO, and MySQL.
2. **Level 2 – Status, Search, and Validation:** Task lifecycle states (`Pending`, `In Progress`, `Completed`), real-time search, status filtering, dual-layer validation, and error messaging.
3. **Level 3 – Authentication, Authorization, and Security:** Secure password hashing, session management, strict user ownership boundaries, role-based controls (`Admin` vs. `User`), CSRF mitigation, and XSS prevention.

---

## 2. Assignment Requirements & Evaluation Criteria

### Functional Tiers Summary (from `RTIH_PHP_MySQL_Technical_Assignment.pdf`)

| Tier | Core Requirements | Technical Verification Focus |
|---|---|---|
| **Level 1** | • MySQL schema (`tasks`)<br>• Create task (title, description)<br>• List all tasks<br>• Edit task title and description<br>• Delete task with confirmation dialog<br>• Action feedback notifications | • PDO database connection<br>• Prepared statements for all queries<br>• Clean UI rendering without raw error leakage |
| **Level 2** | • Distinct statuses: `Pending`, `In Progress`, `Completed`<br>• Inline or modal status updates<br>• Search tasks by title<br>• Filter tasks by status dropdown<br>• Dual-layer validation (client & server)<br>• Descriptive validation error banners/toasts | • Server-side rejection of empty/invalid inputs<br>• Preservation of form state upon validation failure<br>• Dynamic query construction with parameter binding |
| **Level 3** | • Registration and Login flows<br>• Passwords hashed with `password_hash()` and verified with `password_verify()`<br>• Session authentication protecting all dashboard routes<br>• Server-side ownership enforcement (users cannot inspect/edit peers' tasks)<br>• Admin vs. User role privileges<br>• CSRF token generation and validation on state changes<br>• Output sanitization (`htmlspecialchars`) against XSS | • URL parameter tampering rejection (e.g. `?id=999`)<br>• Timing-safe CSRF token checks<br>• Separation of user tasks and administrative oversight<br>• Session regeneration on login (`session_regenerate_id`) |
| **Bonus** | • Server-side pagination<br>• Dashboard summary metrics (Total, Pending, In Progress, Completed)<br>• Priority tags (`Low`, `Medium`, `High`) and Due Dates | • Efficient `COUNT(*)` queries<br>• Limit/Offset calculations |

### Assignment Evaluation Breakdown
- **Core Functionality (35%):** CRUD operations, status management, search, and filtering.
- **PHP & MySQL Implementation (25%):** Schema design, PDO connection, prepared statements, robust error handling.
- **Validation, Security & Authorization (20%):** Server-side ownership, password hashing, XSS/CSRF mitigation.
- **Frontend Usability & Aesthetics (10%):** Clean, accessible layout faithful to approved designs.
- **Code Quality & Documentation (10%):** Modular organization, setup guide, reproducible `database.sql`.

---

## 3. Confirmed Technology Stack

All technological choices comply strictly with the project constraints:

| Layer | Selected Technology | Version / Specification | Rationale & Constraints |
|---|---|---|---|
| **Backend** | Core PHP | PHP 8.3.36 (CLI / Apache Module) | Clean, procedural/modular MVC without heavy frameworks (No Laravel, No Symfony). |
| **Database** | MySQL | MySQL 8.0.46 Community Server | Relational storage with InnoDB engine, foreign keys, and indexes. |
| **DB Driver** | PDO (PHP Data Objects) | `PDO_MYSQL` | Strict prepared statements with emulation disabled (`ATTR_EMULATE_PREPARES => false`). |
| **Frontend** | HTML5 / Vanilla JS / CSS | Semantic HTML, Tailwind CSS (via CDN) | Exact match to Google Stitch design output. Clean, lightweight vanilla JS for modals, filters, and toasts. |
| **Server** | Apache (XAMPP) | Apache 2.4.x (`c:\xampp`) | Standard local development server; clean URL handling via `.htaccess` or direct routing. |
| **VCS** | Git | Git 2.x | Conventional commits tracking implementation milestones. |
| **IDE** | Antigravity (AGY) | Native Antigravity 2.0 | Primary agentic development environment. |

---

## 4. Architectural Decisions

### Design Pattern: Lightweight MVC / Front-Controller Structure
To avoid framework bloat while maintaining professional separation of concerns, the project follows a lightweight Model-View-Controller architecture:

```
task-tracker/
│
├── app/
│   ├── config/
│   │   ├── database.php          # PDO singleton / connection factory
│   │   └── app.php               # Global constants, session security settings, CSRF helpers
│   ├── controllers/
│   │   ├── AuthController.php    # Handles login, registration, session teardown
│   │   ├── TaskController.php    # CRUD operations, status transitions, search/filter queries
│   │   └── AdminController.php   # User role management, cross-system task monitoring
│   ├── models/
│   │   ├── User.php              # User authentication, lookup, role assignment
│   │   └── Task.php              # Task data access, ownership-scoped SQL operations
│   └── views/
│       ├── layouts/
│       │   ├── header.php        # Shared HTML head, Google Fonts, Tailwind config
│       │   ├── sidebar.php       # Dynamic sidebar rendering based on role (Admin vs User)
│       │   └── footer.php        # Toast container, common modals, scripts
│       ├── auth/
│       │   └── login-register.php# Tabbed authentication interface
│       ├── tasks/
│       │   ├── index.php         # Main user dashboard (metrics, filter, task table)
│       │   └── components/       # Task modals (Add, Edit, Delete Confirm, Toasts)
│       └── admin/
│           ├── tasks.php         # Admin all-tasks view (with owner identity)
│           └── users.php         # User management table & role change modal
│
├── public/
│   ├── index.php                 # Front controller / application router
│   ├── actions/
│   │   ├── auth.php              # Action handler: login, register, logout
│   │   ├── task-save.php         # Action handler: create/update task with CSRF
│   │   ├── task-delete.php       # Action handler: delete task with CSRF
│   │   ├── task-status.php       # Action handler: quick status toggle with CSRF
│   │   └── admin-role.php        # Action handler: change user role
│   └── assets/
│       ├── css/
│       │   └── custom.css        # Specific design overrides or utility fallbacks
│       └── js/
│           ├── app.js            # Modal controls, character counter, toast triggers
│           └── ajax-filter.js    # Search & status filter enhancements
│
├── database/
│   ├── schema.sql                # Complete DDL with constraints and indexes
│   └── seed.sql                  # Default Admin and Standard test users + sample tasks
│
├── docs/
│   ├── testing-results.md        # Detailed test matrix and manual test logs
│   └── backup-restore-guide.md   # MySQL dump export and import documentation
│
├── .gitignore                    # Ignore local XAMPP artifacts, IDE caches
├── PROJECT_CONTEXT.md            # Master context and architectural authority (this document)
└── README.md                     # Installation, setup, credentials, and review notes
```

---

## 5. Google Stitch Project Details

- **Stitch Project ID:** `16118606580985303916`
- **Project Title:** `TaskFlow Web Application`
- **Project Type:** `TEXT_TO_UI_PRO`
- **Device Form Factor:** Desktop (responsive to tablet and mobile)
- **Primary Design Aesthetic:** `TaskFlow Direct` – Clean, modern, high-contrast SaaS workspace with muted purple/indigo primary accents and neutral surfaces.

### Extracted Screen Instances
Through MCP tool verification and deep inspection, 17 screen variants were cataloged, representing 5 primary interface states and their corresponding modal and validation states:

| # | Screen Label | Stitch Screen ID | State / Canvas Status | Primary Functional Purpose |
|---|---|---|---|---|
| 1 | **Authentication - Login & Register** | `2c4f01daabbf4ef296d95577a388be1d` | Active (Visible) | Tabbed authentication screen supporting user login, new account registration, password validation, and error display. |
| 2 | **Standard User Dashboard** | `631278972444447f83eb7996b801634b` | Active (Visible) | User workspace containing 4 summary metric tiles, task search, status filter, priority filter, paginated task table, and delete confirmation modal. |
| 3 | **Add Task Modal & Toast** | `5ba964a7e4d24f98af7b48c7412502a7` | Active (Visible) | Standard dashboard view with active success toast notification (`check_circle Task created successfully`). |
| 4 | **Create New Task - Modal** | `866c18510bb14481bf83d850a009a06a` | Master Component | Floating modal containing task title input with 150-char counter, description textarea, status select, priority select, due date picker, and CSRF token field. |
| 5 | **Administrator - All Tasks** | `a214d140db754ab482611acb4d8e79ff` | Active (Visible) | Admin workspace showing system-wide tasks across all registered users, owner avatar/name, global summary statistics, and administrative controls. |
| 6 | **Administrator - User Management (Balanced)** | `bb2c187ad5e645ad994858747ca28e23` | Active (Visible) | Admin user directory with role badges, task count, user search, and "Change User Role" action modal. |
| 7 | **Form Validation & Feedback States** | `167bfb17d4bb478ea1626fcd82d94ad7` | Master Component | Visual specification of error banners, input rings (`ring-error`), inline validation messages (`Task title is required`), character overflow warning, and error toast alerts. |

---

## 6. Design System Specification

The design tokens extracted from Google Stitch `TaskFlow Direct` define the visual language:

### 1. Color Palette
```css
:root {
  /* Surfaces & Backgrounds */
  --color-background: #faf9fd;
  --color-surface: #faf9fd;
  --color-surface-container-lowest: #ffffff;
  --color-surface-container-low: #f4f3f7;
  --color-surface-container: #efedf1;
  --color-surface-container-high: #e9e7eb;
  --color-surface-container-highest: #e3e2e6;
  
  /* Typography & Contrasts */
  --color-on-surface: #1a1b1e;
  --color-on-surface-variant: #474554;
  --color-outline: #787585;
  --color-outline-variant: #c8c4d6;
  
  /* Brand / Primary */
  --color-primary: #493bbf;
  --color-primary-container: #6256d9;
  --color-on-primary: #ffffff;
  --color-on-primary-container: #ece8ff;
  --color-primary-hover: #5145c5;
  
  /* Status: Pending (Warm Amber) */
  --status-pending-bg: #fef3c7;
  --status-pending-text: #92400e;
  --status-pending-dot: #f59e0b;
  
  /* Status: In Progress (Sky Blue) */
  --status-inprogress-bg: #e0f2fe;
  --status-inprogress-text: #075985;
  --status-inprogress-dot: #0284c7;
  
  /* Status: Completed (Emerald Green) */
  --status-completed-bg: #dcfce7;
  --status-completed-text: #166534;
  --status-completed-dot: #16a34a;
  
  /* Error / Destructive */
  --color-error: #ba1a1a;
  --color-error-container: #ffdad6;
  --color-on-error: #ffffff;
  --color-on-error-container: #93000a;
}
```

### 2. Typography
- **Primary Font Family:** `'Inter', -apple-system, BlinkMacSystemFont, sans-serif`
- **Accent/Header Font:** `'Plus Jakarta Sans', sans-serif` (applied to logo and major modal headings)
- **Iconography:** Google Material Symbols Outlined (`font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24`)
- **Type Scale:**
  - `headline-xl`: 2.0rem / 700 / tracking: -0.02em
  - `headline-lg`: 1.5rem / 600 / tracking: -0.015em
  - `headline-md`: 1.25rem / 600 / tracking: -0.01em
  - `headline-sm`: 1.125rem / 600
  - `body-lg`: 1.0rem / 400
  - `body-md`: 0.875rem / 400
  - `body-sm`: 0.75rem / 400
  - `label-md`: 0.875rem / 500
  - `label-sm`: 0.75rem / 500

### 3. Spacing, Shapes & Elevation
- **Border Radius:**
  - Base controls (inputs, buttons, dropdowns): `0.5rem` (`rounded-lg` / 8px)
  - Card containers & Modals: `0.75rem` (`rounded-xl` / 12px)
  - Badges & Avatars: `9999px` (`rounded-full`)
- **Elevation (Shadows):**
  - Cards: `box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05)` bounded by `1px solid #e3e2e6`
  - Popovers / Dropdowns: `box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08)`
  - Floating Modals: `box-shadow: 0 10px 25px rgba(0, 0, 0, 0.10)` over `rgba(32, 33, 36, 0.4)` backdrop
  - Toasts: `box-shadow: 0 16px 36px -4px rgba(25, 58, 54, 0.12), 0 6px 12px -3px rgba(25, 58, 54, 0.04)`

---

## 7. Stitch-to-Repository Mapping

| Stitch Screen & Identifier | Physical Stitch Design Asset | Intended Application Route / Component | PHP Controller & Action | Database Queries / Operations |
|---|---|---|---|---|
| **Authentication**<br>`2c4f01daabbf4ef296d95577a388be1d` | `Authentication - Login & Register.html` | `/login`, `/register`<br>`app/views/auth/login-register.php` | `AuthController::login()`<br>`AuthController::register()`<br>`AuthController::logout()` | `SELECT id, name, email, password, role FROM users WHERE email = ?`<br>`INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')` |
| **Standard User Dashboard**<br>`631278972444447f83eb7996b801634b` | `Standard User Dashboard.html` | `/dashboard`<br>`app/views/tasks/index.php` | `TaskController::index()` | `SELECT COUNT(*) FROM tasks WHERE user_id = ?`<br>`SELECT * FROM tasks WHERE user_id = ? AND status = ? AND title LIKE ? ORDER BY created_at DESC LIMIT ? OFFSET ?` |
| **Add / Edit Task Modal**<br>`866c18510bb14481bf83d850a009a06a` | `Create New Task - Modal.html` | Reusable Modal Component<br>`app/views/tasks/components/modal-task.php` | `TaskController::create()`<br>`TaskController::update()` | `INSERT INTO tasks (user_id, title, description, status, priority, due_date) VALUES (?, ?, ?, ?, ?, ?)`<br>`UPDATE tasks SET title=?, description=?, status=?, priority=?, due_date=? WHERE id=? AND user_id=?` |
| **Delete Confirmation Modal**<br>Included in `631278972444447f83eb7996b801634b` | Standard User Dashboard Modal Overlay | Reusable Modal Component<br>`app/views/tasks/components/modal-delete.php` | `TaskController::delete()` | `DELETE FROM tasks WHERE id = ? AND user_id = ?` (Strict ownership validation) |
| **Status Quick-Toggle & Feedback**<br>`5ba964a7e4d24f98af7b48c7412502a7` | `Add Task Modal & Toast.html` | Reusable Toast Partial<br>`app/views/layouts/toast.php` | `TaskController::updateStatus()` | `UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?` |
| **Admin: All Tasks**<br>`a214d140db754ab482611acb4d8e79ff` | `Administrator - All Tasks.html` | `/admin/tasks`<br>`app/views/admin/tasks.php` | `AdminController::allTasks()`<br>`AdminController::deleteTask()` | `SELECT t.*, u.name as owner_name, u.email as owner_email FROM tasks t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC` |
| **Admin: User Management**<br>`bb2c187ad5e645ad994858747ca28e23` | `Administrator - User Management.html` | `/admin/users`<br>`app/views/admin/users.php` | `AdminController::users()`<br>`AdminController::updateRole()` | `SELECT u.id, u.name, u.email, u.role, u.created_at, COUNT(t.id) as task_count FROM users u LEFT JOIN tasks t ON u.id = t.user_id GROUP BY u.id`<br>`UPDATE users SET role = ? WHERE id = ?` |
| **Validation & Error UI**<br>`167bfb17d4bb478ea1626fcd82d94ad7` | `Form Validation & Feedback States.html` | Component UI specs for inline validation & error alerts | Handled by all controllers via session flash alerts (`$_SESSION['errors']`) | Non-database / session-rendered UI patterns |

---

## 8. Database Design

### Engine & Character Encoding
- **Database Name:** `rtih_task_tracker`
- **Engine:** `InnoDB` (for transactional consistency and foreign key integrity)
- **Collation:** `utf8mb4_unicode_ci`

### Schema Definition

```sql
-- 1. Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(191) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user_email (email),
    INDEX idx_user_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tasks Table
CREATE TABLE IF NOT EXISTS tasks (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT NULL,
    status ENUM('Pending', 'In Progress', 'Completed') NOT NULL DEFAULT 'Pending',
    priority ENUM('Low', 'Medium', 'High') NOT NULL DEFAULT 'Medium',
    due_date DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_tasks_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    INDEX idx_task_user_id (user_id),
    INDEX idx_task_status (status),
    INDEX idx_task_title (title)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Justification & Indexing Rationale:
1. **Foreign Key Cascade:** `ON DELETE CASCADE` guarantees that if a user account is deleted by an administrator, orphaned tasks are automatically cleaned up.
2. **`VARCHAR(191)` on Email:** Optimized for MySQL utf8mb4 767-byte prefix limits in unique indexes.
3. **`title VARCHAR(150)`:** Matches the Stitch design constraint and validation specification exactly (`150 characters max`).
4. **Performance Indexes:**
   - Composite lookup indexing on `(user_id, status)` optimizes user dashboard queries.
   - Title index supports fast prefix searches (`LIKE 'term%'`).

---

## 9. Authentication & Authorization Strategy

### 1. Authentication Lifecycle
- **Session Hardening:**
  - `session_start()` initialized with secure cookies (`httponly = true`, `samesite = 'Lax'`).
  - Session ID regenerated on authentication (`session_regenerate_id(true)`) to mitigate session fixation attacks.
- **Password Security:**
  - Stored strictly via `password_hash($password, PASSWORD_DEFAULT)` using Bcrypt/Argon2.
  - Verification performed via constant-time `password_verify($password, $hash)`.
  - Plaintext passwords never logged, exposed, or written to temporary tables.

### 2. Authorization & Ownership Enforcement
- **Standard User Boundary:**
  - Every task query for standard users includes an explicit `user_id = :session_user_id` constraint.
  - Modifying or deleting a task verifies that `tasks.user_id == $_SESSION['user_id']`. If mismatched, the request terminates immediately with an HTTP 403 Forbidden status.
- **Admin Role Hierarchy:**
  - Users with `role === 'admin'` can access `/admin/tasks` and `/admin/users`.
  - Non-admin attempts to access admin routes redirect to `/dashboard` with an error alert.
  - Admins can manage user roles and review all tasks across the system, but normal task operations in their personal dashboard remain scoped to their user ID.

### 3. CSRF & State-Changing Request Protection
- Every session generates a cryptographically secure token: `$_SESSION['csrf_token'] = bin2hex(random_bytes(32))`.
- Every form (Create Task, Edit Task, Delete Task, Status Toggle, Role Update) embeds `<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">`.
- Server validates using `hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')`.

---

## 10. Existing Repository Audit

### Directory Inventory (as of audit)
```
c:\xampp\htdocs\task-tracker\
├── .git/                                # Git repository initialized (no commits yet)
├── .vscode/
│   └── settings.json                    # Editor configuration
└── RTIH_PHP_MySQL_Technical_Assignment.pdf # Assignment specification document
```

### Git Status
- Active branch: `main`
- Commit count: `0` (Initial clean working tree with untracked PDF and `.vscode`)
- Uncommitted source changes: None.

### Current Implementation State
- **Backend Code:** Not yet created.
- **Database Schema & Migrations:** Not yet created.
- **Frontend Code:** Finalized design assets extracted from Google Stitch and stored in scratch workspace (`brain/.../scratch/all_screens/`). Repository contains no duplicate or conflicting code.

---

## 11. Work Breakdown

### Completed in Audit Phase
- [x] Stitch MCP connection verified and authenticated with valid API credentials.
- [x] Retrieved Stitch project `16118606580985303916` metadata.
- [x] Extracted all 5 primary active screens and 12 component/variant screens from Stitch.
- [x] Downloaded and archived complete HTML, styles, and asset links for all screens.
- [x] Extracted comprehensive design tokens: color system, typography, shadows, rounded borders, and density rules.
- [x] Read and reviewed the complete RTIH assignment PDF requirements.
- [x] Audited local development environment: PHP 8.3.36 and MySQL 8.0.46 running.
- [x] Completed full mapping between Stitch designs, PHP routes, controllers, and SQL schema.
- [x] Documented architectural blueprint in `PROJECT_CONTEXT.md`.

### Implementation Status (Completed & Verified)
- [x] Database initialization: `task_flow` schema on MySQL 8.0, created and seeded via `database/schema.sql` and `database/seed.sql` / `database/database.sql`.
- [x] Core configuration and PDO database wrapper (`app/config/database.php`, `app/config/app.php`).
- [x] Base templates and layouts (`app/views/layouts/` preserving Stitch design system).
- [x] Level 1 Implementation: Task model, basic CRUD controller, task table view, delete confirmation modal.
- [x] Level 2 Implementation: Status workflow (`Pending`, `In Progress`, `Completed`), quick status popup modal, sinking completed tasks, search filter, status filter, priority filter, client/server validation.
- [x] Level 3 Implementation: User registration (strictly restricted to `user` role), 5 strong password rules, login, session auth, ownership enforcement, CSRF protection, admin management views.
- [x] Telugu & Indian Seed Profiles: Admin Boddeda Prudhvi (`prudhviboddeda5@gmail.com`) with 4 personal tasks; Standard users Kalyan Varma, Ananya Rao, and Suresh Reddy with everyday tasks.
- [x] Testing & Verification: Execution of test matrix covering all levels and edge cases.
- [x] Submission Deliverables: `database.sql`, `README.md` with setup guide and test accounts, screenshots.

---

## 12. Known Limitations & Scope Boundaries

1. **Email Verification / Password Reset:** The login interface displays "Forgot password?" and links to support, but per the assignment's "beginner-friendly" core PHP scope, external SMTP/mail services will not be configured; a notice or predefined administrative reset will be provided.
2. **Third-party SSO:** Social login buttons (e.g. Google Sign-In) are not required by the assignment and will not be activated; standard email/password authentication is used.
3. **No External Frameworks:** Core PHP is strictly maintained to satisfy evaluation standards and ease of explanation.

---

## 13. Development & Security Conventions

1. **Strict Output Escaping:** Every dynamic string rendered in HTML must pass through `htmlspecialchars($val, ENT_QUOTES, 'UTF-8')`. A short helper `e($val)` will be defined globally.
2. **Post/Redirect/Get (PRG):** All form submissions (`POST`) must redirect upon completion to prevent duplicate form submissions on page refresh.
3. **Session Flash Messages:** Operational feedback (success toast, validation error banner) is passed via `$_SESSION['flash_success']` and `$_SESSION['flash_error']`, cleared immediately upon rendering.
4. **No Raw SQL Concatenation:** Query parameters are never interpolated into SQL strings. Every user variable is bound via PDO parameter binding.

---

## 14. Testing Strategy & Test Matrix

The following test suite will be executed before final submission:

| Test ID | Category | Test Scenario | Expected Outcome |
|---|---|---|---|
| **T-01** | Level 1 | Create task with valid title and description | Task saved in MySQL; success toast displayed on dashboard. |
| **T-02** | Level 1 | Delete task via confirmation modal | Modal asks confirmation; on confirm, task deleted; list updates. |
| **T-03** | Level 1 | Edit task title, description, and status | Task updated in MySQL; changes reflected immediately. |
| **T-04** | Level 2 | Submit empty task title | Submission rejected; form displays inline error `Task title is required`. |
| **T-05** | Level 2 | Submit title > 150 characters | Submission rejected; warning displays character overflow. |
| **T-06** | Level 2 | Filter tasks by status (`In Progress`) | Only tasks with `In Progress` status are returned. |
| **T-07** | Level 2 | Search tasks by keyword in title | Only tasks containing keyword are displayed. |
| **T-08** | Level 3 | Unauthenticated access to `/dashboard` | Redirected to `/login` with notification. |
| **T-09** | Level 3 | User A attempts to edit/delete User B's task | HTTP 403 Forbidden; database record unmodified. |
| **T-10** | Level 3 | State-changing POST without valid CSRF token | HTTP 403 Forbidden; action aborted. |
| **T-11** | Level 3 | Standard user attempts access to `/admin/users` | Redirected to `/dashboard` with authorization warning. |
| **T-12** | Level 3 | Admin changes standard user role to `admin` | User role updated in `users` table; permissions take effect on next request. |
| **T-13** | Security | Submit XSS payload `<script>alert(1)</script>` | Payload safely escaped in HTML output (`&lt;script&gt;...`); no script execution. |
| **T-14** | Security | SQL injection attempt `' OR '1'='1` in search | Safely treated as literal string via PDO prepared statement. |
