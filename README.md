# HelpDesk Ticket Management System

A web-based Help Desk Ticket Management System developed using PHP and MySQL to simplify ticket creation, tracking, assignment, and resolution.

## Project Overview

This system provides a centralized platform for managing support requests. Users can create and track tickets, agents can handle assigned tickets, and administrators can manage users, departments, categories, and system operations.

## Features

### Admin

* Dashboard with ticket statistics
* Manage users
* Manage departments
* Manage categories
* Manage ticket statuses
* Create and manage tickets
* View system-wide reports

### Agent

* View assigned tickets
* Update ticket status
* Track ticket progress
* Manage profile

### End User

* Create support tickets
* Track ticket status
* View ticket history
* Manage profile

## Technologies Used

* PHP
* MySQL
* HTML5
* CSS3
* JavaScript
* XAMPP

## Project Structure

```text
admin/
agent/
enduser/
assets/
config/
database/
mail/
```

## Installation

1. Download or clone the repository.
2. Copy the project folder into the XAMPP `htdocs` directory.
3. Start Apache and MySQL using XAMPP.
4. Import the database file from the `database` folder into phpMyAdmin.
5. Update database credentials in `config/db.php` if required.
6. Open the project in your browser.

## User Roles

### Admin

Responsible for overall system management.

### Agent

Handles and resolves assigned support tickets.

### End User

Creates and monitors support requests.

## Educational Purpose

This project was developed as a BCA Major Project to demonstrate concepts of:

* Role-Based Access Control
* Ticket Management System
* Authentication and Authorization
* Database Management
* Web Application Development

## Author

**Sawan Kumar Beniya**
Bachelor of Computer Applications (BCA)

## License

This project is intended for educational and learning purposes.
