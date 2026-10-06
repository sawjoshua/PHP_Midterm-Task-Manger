README.md
# Student Task Manager

## Project Description

Student Task Manager is a PHP and MySQL web application developed for the PHP Midterm Project.

The system allows students to:

- Add new tasks
- View all tasks
- Edit existing tasks
- Delete tasks
- Mark tasks as completed
- Count completed tasks

The project demonstrates CRUD operations using PHP PDO and MySQL.

---

## Features

### Create
Users can add a new task by entering:

- Task Title
- Description
- Category
- Priority
- Due Date

### Read
Users can view all tasks in a table format.

### Update
Users can edit existing task information and update task status.

### Delete
Users can delete tasks from the database.

### Challenge Feature
Displays the total number of completed tasks.

---

## Technologies Used

- PHP
- MySQL
- PDO (PHP Data Objects)
- HTML5
- CSS3
- XAMPP
- phpMyAdmin

---

## Database Name

task_manager

---

## Table Structure

### tasks

| Field | Type |
|---------|---------|
| id | INT |
| title | VARCHAR(150) |
| description | TEXT |
| category | VARCHAR(50) |
| priority | VARCHAR(20) |
| due_date | DATE |
| completed | TINYINT(1) |
| created_at | TIMESTAMP |

---

## Project Structure