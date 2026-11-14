# RTIH TaskFlow – Database Backup and Restore Guide

This guide explains how to back up and restore the `task_flow` MySQL database for local development, production backup, or migration.

---

## 1. Quick Backup (Dump)

### Using MySQL CLI (`mysqldump`)
Open your terminal (PowerShell, Command Prompt, or XAMPP Shell):

```bash
# 1. Full backup with table structure, constraints, and data
mysqldump -u root -p task_flow > task_flow_backup.sql

# 2. Or if your MySQL user has no password (default XAMPP):
mysqldump -u root task_flow > task_flow_backup.sql
```

### Using phpMyAdmin (Web UI)
1. Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin) in your browser.
2. Select the **`task_flow`** database from the left-hand navigation.
3. Click the **Export** tab in the top menu.
4. Select **Quick - display only the minimal options** and format **SQL**.
5. Click **Export** to download `task_flow.sql`.

---

## 2. Quick Restore (Import)

### Using MySQL CLI (`mysql`)
```bash
# 1. Ensure the target database exists
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS task_flow CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 2. Restore the database from the backup SQL file
mysql -u root -p task_flow < task_flow_backup.sql
```

### Using phpMyAdmin (Web UI)
1. Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
2. Click **New** in the left sidebar, name the database **`task_flow`**, choose collation `utf8mb4_unicode_ci`, and click **Create**.
3. With `task_flow` selected, click the **Import** tab.
4. Click **Choose File** and browse to `database/database.sql` (or your backup file).
5. Click **Import** at the bottom.

---

## 3. Automated Daily Backup Script (Windows PowerShell)

For scheduled backups, save the following script as `backup.ps1`:

```powershell
# Define Variables
$Date = Get-Date -Format "yyyyMMdd_HHmmss"
$BackupDir = "C:\xampp\htdocs\task-tracker\database\backups"
$BackupFile = "$BackupDir\task_flow_$Date.sql"
$Mysqldump = "C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqldump.exe"

# Create backup directory if missing
if (!(Test-Path $BackupDir)) {
    New-Item -ItemType Directory -Path $BackupDir -Force
}

# Execute dump
& $Mysqldump -u root -p"YOUR_PASSWORD" task_flow > $BackupFile
Write-Host "Backup created: $BackupFile"
```

You can schedule this script in **Windows Task Scheduler** to run daily at midnight.
