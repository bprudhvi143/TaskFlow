# TaskFlow – Web Application

**Assignment:** Technical Assignment – Intern IT  
**Organization:** Ratan Tata Innovation Hub (RTIH)  
**Author:** Boddeda Prudhvi  
**Repository:** [https://github.com/bprudhvi143/TaskFlow.git](https://github.com/bprudhvi143/TaskFlow.git)  
**Google Stitch Design Project:** `16118606580985303916`  

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
- [x] Quick status popup modal beside task details allowing immediate status transition without opening the edit form.
- [x] Sinking completed tasks: Completed tasks automatically sink to the bottom of the task list, preserving focus on active tasks.
- [x] Completed task highlighting: No strikethrough (`line-through`); completed tasks feature soft emerald background tints, checkmarks, and green accents.
- [x] Clickable metric filter cards: Total Tasks, In Progress, Completed, and Pending summary cards are interactive links that filter the task view.
- [x] Real-time search filter querying tasks by title or description.
- [x] Filter tasks by status dropdown (`Pending`, `In Progress`, `Completed`).
- [x] Filter tasks by priority dropdown (`High`, `Medium`, `Low`).
- [x] Dual-layer validation (client-side character counter and required checks + server-side validation).
- [x] Friendly error messaging matching Stitch validation states (empty title rejection, 150-char max enforcement).

### Level 3 – Authentication, Authorization & Security
- [x] Unified login and user registration flows (registration strictly restricted to `user` role).
- [x] 5 Industry-Standard Strong Password Rules:
  1. Minimum length of 8 characters (12+ recommended).
  2. Uppercase letter required (`A-Z`).
  3. Lowercase letter required (`a-z`).
  4. Number / digit required (`0-9`).
  5. Special symbol required (`!@#$%^&*()_+-=[]{}|;:,.<>?`).
  6. Personal information restriction: Password cannot contain any part of the user's name or email address prefix.
- [x] Interactive real-time password strength meter and checklist on the registration screen.
- [x] Secure password storage using `password_hash()` (Bcrypt).
- [x] Password verification using `password_verify()`.
- [x] Session-based authentication protecting all dashboard routes.
- [x] Session fixation prevention via `session_regenerate_id(true)` upon login.
- [x] Server-side ownership boundary: Queries strictly enforce `WHERE user_id = :session_user_id`. Users cannot view, modify, or delete another user's tasks by tampering with IDs or URL parameters.
- [x] Role-Based Access Control (RBAC):
  - **Standard User:** Restricted strictly to their own personal workspace and tasks.
  - **Administrator:** Elevated access to `/admin/tasks` (global system task oversight with owner details) and `/admin/users` (user directory with task activity and role modification modal), alongside a personal workspace for managing their own assigned tasks.
- [x] CSRF Protection: Cryptographically secure tokens (`$_SESSION['csrf_token']`) verified with `hash_equals()` on all state-changing POST requests.
- [x] XSS Prevention: Strict output escaping using `htmlspecialchars($string, ENT_QUOTES, 'UTF-8')`.

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
| **Administrator** | Boddeda Prudhvi | `prudhviboddeda5@gmail.com` | `FalconShield#2026!Master` | 4 personal tasks + Full administrative oversight (`/admin/tasks`, `/admin/users`). |
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
│       │       ├── modal-task.php  # Add & Edit Task modal dialog
│       │       └── modal-delete.php# Delete confirmation modal dialog
│       └── admin/
│           ├── tasks.php         # Admin all-tasks view with owner details
│           └── users.php         # Admin user directory & role change modal
│
├── public/
│   ├── index.php                 # Application Front Controller & router
│   ├── actions/
│   │   ├── auth.php              # Action handler: login, register, logout
│   │   ├── task-save.php         # Action handler: create/update task
│   │   ├── task-delete.php       # Action handler: delete task
│   │   ├── task-status.php       # Action handler: quick status toggle
│   │   └── admin-role.php        # Action handler: admin role updates
│   └── assets/
│       ├── css/
│       │   └── custom.css        # Subtle scrollbars and utility fallbacks
│       └── js/
│           └── app.js            # Modal controls, character counter, toast triggers
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
├── .env.example                  # Environment template
├── .gitignore                    # Git ignore file
├── PROJECT_CONTEXT.md            # Comprehensive project blueprint and audit notes
└── README.md                     # Documentation and setup instructions
```

---

## 7. Roles & Permissions (Level 3 RBAC)

| Capability / Action | Standard User | Administrator |
|---|:---:|:---:|
| Register new account | Yes | Yes |
| Sign in & view personal dashboard | Yes | Yes |
| Create new personal task | Yes | Yes |
| Edit personal task details | Yes | Yes |
| Update personal task status | Yes | Yes |
| Delete personal task | Yes | Yes |
| View tasks belonging to other users | **No** (Blocked server-side) | Yes (via `/admin/tasks`) |
| Edit/delete another user's task | **No** (HTTP 403 Forbidden) | Yes (Admin delete in oversight) |
| Access Admin All Tasks console | **No** (Redirects to dashboard) | Yes |
| Access Admin User Management | **No** (Redirects to dashboard) | Yes |
| Promote/demote user roles | **No** (Unauthorized) | Yes (via Role modal) |

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

Detailed instructions for automated cron backups are provided in `docs/backup-restore-guide.md`.

---

## 9. AI Coding Assistant Disclosure

In compliance with assignment submission instructions:
- **Assistant Used:** Google Antigravity (AGY) AI Assistant.
- **Role:** Architectural planning, Google Stitch MCP design extraction, and scaffolding modular MVC structures.
- **Manually Reviewed & Enhanced Change:**
  - *Identified Issue in Generated Logic:* In the user role update endpoint (`AdminController::updateUserRole`), standard code generators allow an administrator to modify any user's role including their own. If the sole administrator demoted their own account to `user`, they would lock themselves out of the admin panel permanently.
  - *Manual Review Modification:* Implemented a strict self-demotion prevention guard in `AdminController.php`:
    ```php
    // Prevent self-lockout: Admin cannot demote their own account to 'user'
    if ($userId === (int) $currentUser['id'] && $newRole !== 'admin') {
        set_flash('error', 'Action prohibited: You cannot demote your own administrator account.');
        redirect(BASE_URL . '/public/index.php?page=admin-users');
    }
    ```
    This ensures administrative continuity and avoids unintended privilege loss.
