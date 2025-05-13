# MoLibrary
**MoLibrary** is a comprehensive and scalable web-based Library Management System built to digitize and streamline the real-world operations of libraries. Its purpose is to provide a **smooth, organized, and user-friendly digital solution for managing library activities** — from book inventory and membership plans to borrowing, returning, and fines — all within a unified platform that supports multiple libraries.

It supports various user roles (Super Admin, Library Admin, Librarian, and Member) and includes rich features such as real-time notifications, secure authentication, and plan-based subscriptions — offering a seamless experience for both administrators and members.

## 🛠️ Tech Stack
- **Frontend:** HTML, CSS, Javascript, Bootstrap
- **Backend:** PHP 8.3(Laravel 12 Framework)
- **Database:** Mysql(managed via phpMyAdmin)
- **Templating Engine:** Mysql(managed via phpMyAdmin)
- **Database:** Mysql(managed via phpMyAdmin)
- **Authentication:** Laravel's built-in Auth class  
- **Notifications:** Laravel Notification System (Database channel),Real time notification(Pusher),Mail Notification(Mail trap)  
- **Payment Integration:** Stripe API  
- **Task Scheduling:** Laravel Scheduler & Queued Jobs using Supervisor
- **Version Control:** Git, GitHub  
- **Deployment:** Docker, AWS



## 🌐 Project Setup
Follow the steps below to set up and run the project on your local machine:
### 1. Install basic Software:
- Php 8.3.19
- Laravel 12
- Apache 2.4.52
- Mysql 8.0
- phpmyadmin 5.2.1

### 1. Clone the Repository
```bash
git clone https://github.com/PradyumnaRoy-mindfire/molibrary.git
cd molibrary
```
### 2. Install Composer(2.8.6) and Dependencies
Open the terminal and run the following commands
```bash
composer install
npm install
```
### 3. Copy the Environment File
```bash
cp .env.example .env
```

### 4. Set up Virtual Host
* ```bash
    sudo nano /etc/apache2/sites-available/mysite.conf
    ```
* ``` bash
    <VirtualHost *:80>
        ServerAdmin webmaster@localhost
        ServerName molibrary.in
        ServerAlias https://www.molibrary.in
        DocumentRoot /var/www/html/molibrary/public
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
    </VirtualHost>
    ```
* ```bash 
    sudo a2ensite mysite.conf
    ```
* ```bash 
    sudo apache2ctl configtest
    ```
* ```bash 
    sudo systemctl restart apache2
    ```
* ```bash 
    sudo nano /etc/hosts
    127.0.0.1       molibrary.in
    ```

### 5. Configure Environment Variables
Open the .env file and set up your database and other credentials:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password   
```
Configure Mail, Stripe, and other services here.

### 6. Run Migrations
```bash
php artisan migrate
```
### 7. Compile Frontend Assets
```bash
npm run dev
```
### 8. Start Laravel Development Server
```bash
php artisan serve
```
Now open http://molibrary.in in the browser.

## 🔑 Role-wise Key Features

### 🛡️ 1. Super Admin
- Add and manage multiple libraries
- Assign Library Admin to each library
- Activate/Deactivate library accounts
- View overall system activity and library status
- Monitor librarian and member activity across libraries
- Manage system-wide notifications

---

### 🏢 2. Library Admin
- Approve or reject librarian registration requests
- Manage library-specific books (add, edit, delete)
- Track book inventory and availability
- Manage member subscriptions and approvals
- View issued/returned book reports

---

### 👨‍🏫 3. Librarian
- Issue and return or reject books request to members
- View and manage active book loans
- Send notification to the members with pending fines 

---

### 👤 4. Member
- Register and subscribe to a library plan
- Search and explore available books
- Borrow and return books based on membership
- Can reserve a out of stock book and get notification after it becomes available
- View active loans and due dates
- Pay fine for the overdue books
- Get notified about expiry, due books, and reminders
- Renew or upgrade membership plans





