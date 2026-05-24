<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# IGreja People Platform

People & Ministry Management System built with Laravel and Filament.

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![Laravel](https://img.shields.io/badge/Laravel-12-red)
![Filament](https://img.shields.io/badge/Filament-v5-orange)

---

## Features

- Employee Attendance
- GPS Attendance
- selfie camera
- Overtime Management
- Payroll Management
- Salary Slip Generator
- Church Ministry Management

---

## Tech Stack

- PHP 8.3
- Laravel 12
- Filament v5
- Livewire
- MySQL

---

## Packages Used

### QR Code Generator

- simplesoftwareio/simple-qrcode
- bacon/bacon-qr-code

### QR Scanner

- html5-qrcode

### Permission & Roles

- spatie/laravel-permission

### PDF Export

- barryvdh/laravel-dompdf

---

## Installation

```bash
git clone https://github.com/username/igreja.git

cd igreja

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate

php artisan serve
```

---

## Attendance Flow

1. User login
2. Scan QR Code
3. Validate GPS location
4. Save attendance

---

## Screenshots

### Dashboard

![Dashboard](docs/dashboard.png)

### Attendance QR

![Attendance](docs/attendance.png)

---

## License

MIT License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

