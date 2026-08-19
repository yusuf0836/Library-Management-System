# Library Management System

A full-stack web-based Library Management System developed to digitize daily library operations. The system manages books, authors, categories, publishers, members, book copies, issue/return records, overdue fines, reports, and role-based user access.

## Features

### Authentication and Roles
- Secure login and logout
- Role-based access control
- Admin, Librarian, and Member roles
- Inactive member login restriction
- Profile photo and password management

### Book Catalog Management
- Category management
- Author management
- Publisher management
- Book add, edit, delete, search, and filter
- Multiple authors for a book
- Book cover image upload
- Book details page
- Book copy management
- Accession number and shelf location tracking
- Available, issued, reserved, lost, and damaged copy status

### Member Management
- Member registration and login account creation
- Unique member code
- Department, phone number, address, and joining date
- Active and inactive membership control
- Member self-profile management
- Member borrowing history

### Circulation Management
- Book issue to active members
- Due-date tracking
- Book return process
- Automatic book copy status update
- Overdue book detection
- Fine calculation based on overdue days

### Fine Management
- Automatic fine generation for late returns
- Unpaid, partial, paid, and waived fine status
- Fine payment recording
- Outstanding fine calculation

### Dashboard and Reports
- Admin and Librarian dashboard
- Book, copy, member, issue, overdue, and fine statistics
- Book copy status chart
- Monthly book issue chart
- Overdue book report
- Circulation report
- Date-wise and status-wise report filtering
- CSV report export

### System Settings
- Library name, email, phone, and address
- Default borrowing period
- Fine rate per overdue day
- Admin-only settings access

## Technology Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.3, Laravel 13 |
| Database | MySQL |
| Frontend | Blade Templates, Bootstrap 5, HTML5, CSS3, JavaScript |
| Charts | Chart.js |
| ORM | Laravel Eloquent ORM |
| Authentication | Laravel Session Authentication |
| Version Control | Git and GitHub |
| Local Development | XAMPP |

## Database Overview

| Table | Purpose |
|---|---|
| users | Stores user login credentials, roles, and profile photo |
| members | Stores library member profiles and membership details |
| categories | Stores book categories |
| authors | Stores author records and biographies |
| publishers | Stores publisher information |
| books | Stores book title, ISBN, edition, description, and cover image |
| author_book | Connects books with one or more authors |
| book_copies | Stores physical copies, accession numbers, shelf locations, and status |
| book_issues | Stores issue, due, return, and circulation records |
| fines | Stores overdue fine amounts and payment information |
| settings | Stores library rules and general system configuration |

## User Roles

| Role | Access |
|---|---|
| Admin | Complete system access, user management, settings, reports, and dashboard |
| Librarian | Book catalog, members, book issue/return, fines, reports, and dashboard |
| Member | Personal profile, borrowing history, due dates, and fine status |

## Installation Guide

### 1. Clone the Project

```bash
git clone https://github.com/yusuf0836/Library-Management-System
cd library-management-system