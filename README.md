<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p> <p align="center"> <a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a> <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a> <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a> <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a> </p>
☕ Coffee Shop Fullstack App (No API)
A simple coffee shop website built with Laravel v12 (PHP framework).
Guests can view the menu, and registered users can reserve tables and place orders. The system includes full admin CRUD access for managing products, categories, and tables.

Note: The front-end UI is a free template and not my original design. I only made a few customizations to it.

🔑 Authentication
The app uses traditional Laravel session-based authentication (no API).

Guests can browse but must register/login to order or reserve.

If you want admin access, create an account and manually set is_admin = 1 in the database.
This will unlock admin-only routes like:

CRUD for products

Category and table management

Viewing all reservations and orders

🔧 Features
User registration & login

Public menu (for guests)

Authenticated ordering & table reservations

Admin panel for:

Product/category/table CRUD

Viewing user orders and reservations

Frontend integrated with Blade

Laravel v12 structure & logic

🧠 Technologies Used
Laravel 12

PHP 8.2+

MySQL

Blade Templating

Bootstrap (via the template)

📚 About Laravel
Laravel is a web application framework with expressive, elegant syntax.
It helps ease common tasks such as:

Fast routing engine

Powerful service container

Eloquent ORM

Robust migration system

Background jobs

Real-time broadcasting

Full documentation is available at laravel.com/docs.

🎓 Learning Resources
Laravel Documentation

Laravel Bootcamp

Laracasts

🛡️ License
Laravel is open-sourced software licensed under the MIT license.


