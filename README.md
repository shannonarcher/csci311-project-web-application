# CSCI311 Project Web Application
A Project Management System developed for a course I had in University. This repository exists for purely archival reasons, I will be developing version 2.0 of this system on a seperate repository that will be linked here in the future.

## Features
- User Accounts
- Data management for projects, tasks, task comments, user accounts and milestones
- Gantt Chart
- Function Point Analysis
- COCOMO 81 & II Analysis
- PERT Analysis

## To Install
Requires <a href="https://github.com/shannonarcher/csci311-project-api">API</a> to work.

### Step 1
Install an AMP stack on your operating system of choice.

### Step 2
Create database, _db_name_

### Step 3
Copy source files for <a href="https://github.com/shannonarcher/csci311-project-api">API</a> to www root.

Change document root to /public

Copy .env.example to .env

Update .env with the following fields, replacing each field with your own values

```
APP_SITE=http://example.com
DB_HOST=localhost
DB_DATABASE=db_name
DB_USERNAME=root
DB_PASSWORD=pass
```

Install composer within directory. https://getcomposer.org/download

Run “composer install” or “php composer.phar install”

### Step 4

Copy source files for <a href="https://github.com/shannonarcher/csci311-project-web-application">Web Application</a> to www root.

Change document root to /public

Copy .env.example to .env

Update .env with the following fields, replacing each field with your own values

```
APP_API=http://api.example.com
```

Install composer within directory. https://getcomposer.org/download

Run “composer install” or “php composer.phar install”

### Step 5

Within the API directory run the following commands to migrate the database and seed with test values. 

```
php artisan migrate
php artisan db:seed
```

To seed with a single admin user and no test values use this set of commands instead.

```
php artisan migrate
php artisan db:seed –class UserTableSeeder
php artisan db:seed –class COCOMO1Seeder
```

### Step 6

Navigate to the web application and login using the following details

Email: john@company.com
Password: admin
