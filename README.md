# School Administration System

> A web-based school administration management system built with Laravel to centralize student data, administrative requests, approval processes, and request history.

## Overview

**School Administration System** is a web application designed to help schools manage internal administrative processes in a centralized and structured environment.

The system replaces fragmented spreadsheet-based and manual processes with a centralized application that provides role-based access, administrative request management, approval workflows, searchable data, filtering, pagination, and request history tracking.

This project was developed as part of the **Sumatif Tengah Semester — Laravel Project, Client Brief 02**.

---

## Objectives

The system is designed to:

* Centralize school administration data.
* Simplify student and administrative request management.
* Provide a structured approval workflow.
* Track the status and history of administrative requests.
* Restrict system access based on user responsibilities.
* Improve data retrieval through search, filtering, and pagination.
* Optimize database queries through Eloquent relationships and eager loading.

---

## Features

### Authentication & Authorization

* User login and logout.
* Authentication for protected pages.
* Three role-based access levels.
* Custom middleware for role-based route protection.
* Different dashboards and actions based on user roles.

### Student Management

Administrators can:

* Create student records.
* View student details.
* Update student records.
* Delete student records.
* Search students.
* Filter student data.
* Browse student data using pagination.

### Administrative Requests

Students can:

* Create administrative requests.
* Select an administrative request type.
* Provide request details.
* View request status.
* View their request history.

### Approval Workflow

Staff members can:

* View incoming requests.
* Review submitted requests.
* Update request status.
* Approve or reject requests.
* Add staff notes.

### Administrative Types

Administrators can manage:

* Administrative request types.
* Descriptions of each request type.
* Available request categories.

### Search, Filter & Pagination

The system provides data management features including:

* Keyword search.
* Status filtering.
* Administrative type filtering.
* Pagination for large datasets.

### Request History

Every request can maintain a history containing:

* Request reference.
* User who performed the action.
* Status.
* Notes.
* Timestamp.

---

## User Roles

| Role              | Responsibilities                                                                                               |
| ----------------- | -------------------------------------------------------------------------------------------------------------- |
| **Student**       | Manage personal requests, view request status, and access personal request history.                            |
| **Staff**         | Review administrative requests, update request status, approve or reject requests, and review request history. |
| **Administrator** | Manage students, users, administrative types, and monitor all administrative requests.                         |

---

## Application Workflow


                    Authentication
                         │
                         ▼
                  Role Verification
                         │
          ┌──────────────┼──────────────┐
          ▼              ▼              ▼
       Student          Staff       Administrator
          │              │              │
          ▼              ▼              ▼
    Create Request    Review Request  Manage Students
          │              │           Manage Admin Types
          ▼              ▼           Monitor Requests
     Track Status    Update Status
          │              │
          ▼              ▼
       History        History

---

## Database Design

The application uses a relational database consisting of the following core entities:

### `users`

Stores authentication and authorization information.

| Column     | Description     |
| ---------- | --------------- |
| `id`       | Primary key     |
| `name`     | User name       |
| `email`    | User email      |
| `password` | Hashed password |
| `role`     | User role       |

### `siswa`

Stores student information.

| Column    | Description                   |
| --------- | ----------------------------- |
| `id`      | Primary key                   |
| `user_id` | Foreign key → `users.id`      |
| `nis`     | Student identification number |
| `nama`    | Student name                  |
| `kelas`   | Student class                 |
| `alamat`  | Student address               |
| `no_telp` | Phone number                  |

### `jenis_administrasi`

Stores available administrative request types.

| Column      | Description              |
| ----------- | ------------------------ |
| `id`        | Primary key              |
| `nama`      | Request type name        |
| `deskripsi` | Request type description |

### `pengajuan`

Stores administrative requests submitted by students.

| Column                  | Description                           |
| ----------------------- | ------------------------------------- |
| `id`                    | Primary key                           |
| `siswa_id`              | Foreign key → `siswa.id`              |
| `jenis_administrasi_id` | Foreign key → `jenis_administrasi.id` |
| `tanggal_pengajuan`     | Request date                          |
| `keterangan`            | Request description                   |
| `status`                | Current request status                |
| `catatan_petugas`       | Staff notes                           |

### `riwayat_pengajuan`

Stores changes made to administrative requests.

| Column         | Description                  |
| -------------- | ---------------------------- |
| `id`           | Primary key                  |
| `pengajuan_id` | Foreign key → `pengajuan.id` |
| `user_id`      | Foreign key → `users.id`     |
| `status`       | Request status               |
| `catatan`      | Change notes                 |

---

## Entity Relationships

The main relationships are:


User
 │
 ├── hasOne ──────────────── Siswa
 │                              │
 │                              │ hasMany
 │                              ▼
 │                         Pengajuan
 │                              │
 │                              │ hasMany
 │                              ▼
 │                     RiwayatPengajuan
 │
 └── hasMany ──────── RiwayatPengajuan

JenisAdministrasi
 │
 └── hasMany ─────── Pengajuan

The database relationship design is implemented using Laravel Eloquent relationships.

---

## Technology Stack

| Technology       | Purpose                                |
| ---------------- | -------------------------------------- |
| **Laravel**      | Backend framework                      |
| **PHP**          | Backend programming language           |
| **MySQL**        | Relational database                    |
| **Blade**        | Server-side templating                 |
| **Eloquent ORM** | Database interaction and relationships |
| **Tailwind CSS** | User interface styling                 |
| **JavaScript**   | Client-side interaction                |
| **Git**          | Version control                        |
| **GitHub**       | Source code repository                 |

---

## Laravel Implementation

This project applies the following Laravel concepts:

### MVC

The application separates responsibilities between:

* **Model** — database and relationships.
* **View** — Blade templates and user interface.
* **Controller** — application logic and request handling.

### Authentication

Protected application pages require authenticated users.

### Authorization

Access is controlled according to the user's role.

### Custom Middleware

Custom middleware is used to restrict routes and pages based on user roles.

### FormRequest

Form validation is separated from controllers using Laravel FormRequest classes.

### Eloquent Relationships

Relationships are defined between:

* User ↔ Student
* Student ↔ Request
* Administrative Type ↔ Request
* Request ↔ Request History
* User ↔ Request History

### Eager Loading

Eloquent eager loading is used when related data is required.

Example:

```php
$pengajuan = Pengajuan::with([
    'siswa',
    'jenisAdministrasi'
])->paginate(10);

This helps prevent unnecessary repeated relationship queries when displaying related data.

---

## Project Structure


app/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
│
├── Models/
│   ├── User.php
│   ├── Siswa.php
│   ├── Pengajuan.php
│   ├── JenisAdministrasi.php
│   └── RiwayatPengajuan.php
│
database/
├── migrations/
└── seeders/

resources/
└── views/
    ├── layouts/
    ├── auth/
    ├── siswa/
    ├── petugas/
    ├── admin/
    └── pengajuan/

routes/
└── web.php


---

## Installation

### Requirements

Make sure the following are installed:

* PHP
* Composer
* Node.js & npm
* MySQL
* Laravel
* Git

### 1. Clone Repository


git clone https://github.com/USERNAME/REPOSITORY.git
cd REPOSITORY


### 2. Install PHP Dependencies


composer install


### 3. Install Frontend Dependencies


npm install


### 4. Configure Environment

Create the `.env` file:


cp .env.example .env


For Windows PowerShell:

powershell
Copy-Item .env.example .env


Generate the application key:


php artisan key:generate


### 5. Configure Database

Upda
