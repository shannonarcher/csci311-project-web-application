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
Create database, __db_name__

### Step 3
Copy source files for <a href="https://github.com/shannonarcher/csci311-project-api">API</a> to www root.

Change document root to /public

Copy .env.example to .env

Update .env with the following fields, replacing __italics__ with your own values

<code>
APP_SITE=__http://example.com__

DB_HOST=localhost

DB_DATABASE=__db_name__

DB_USERNAME=__root__

DB_PASSWORD=__pass__
</code>

Install composer within directory. https://getcomposer.org/download

Run “composer install” or “php composer.phar install”

### Step 4

Copy source files for <a href="https://github.com/shannonarcher/csci311-project-web-application">Web Application</a> to www root.

Change document root to /public

Copy .env.example to .env

Update .env with the following fields, replacing __italics__ with your own values

<code>APP_API=__http://api.example.com__</code>

Install composer within directory. https://getcomposer.org/download

Run “composer install” or “php composer.phar install”

### Step 5

Within the API directory run the following commands to migrate the database and seed with test values. 

<code>
php artisan migrate
php artisan db:seed
</code>

To seed with a single admin user and no test values use this set of commands instead.

<code>
php artisan migrate
php artisan db:seed –class UserTableSeeder
php artisan db:seed –class COCOMO1Seeder
</code>

### Step 6

Navigate to the web application and login using the following details

Email john@company.com
Password admin
