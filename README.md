# 🐄 Tauros-API: Livestock Management System

[![Laravel Version](https://img.shields.io/badge/Laravel-v13.0-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![Docker](https://img.shields.io/badge/Docker-Enabled-2496ED?style=for-the-badge&logo=docker)](https://www.docker.com)
[![PHP Version](https://img.shields.io/badge/PHP-v8.3-777BB4?style=for-the-badge&logo=php)](https://www.php.net)
[![License: AGPL v3](https://img.shields.io/badge/License-AGPL_v3-blue.svg?style=for-the-badge)](https://www.gnu.org/licenses/agpl-3.0)

**Tauros-API** is a powerful backend designed for comprehensive livestock management, developed with Laravel and optimized to run in containerized environments using **Docker Sail**.

---

## 🚀 Main Modules

| Module | Description |
| :--- | :--- |
| **🐂 Livestock** | Traceability of animals, breeds, categories, and life cycles. |
| **🏥 Clinic & Health** | Clinical histories, diagnostics, and veterinary treatments. |
| **🥛 Production** | Milking records, growth weighing, and yields. |
| **📦 Inventory** | Management of batches, supplies, and movement kardex. |
| **🧬 Genetics** | Control of semen straws and embryos. |

---

## 🛠️ Prerequisites

To run this project, you only need:
- **Docker Desktop**
- **Docker Compose**

Local PHP or Composer installations are not required, as everything runs within containers.

---

## ⚙️ Installation with Docker Sail

Follow these steps to set up the development environment:

### 1. Clone the repository
```bash
git clone https://github.com/GeyserVelasquez/Tauros-API.git
cd Tauros-API
```

### 2. Initial Configuration
Create your environment file:
```bash
cp .env.example .env
```

### 3. Install Composer Dependencies
Run this command to install dependencies without needing local PHP:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php8.3-composer:latest \
    composer install --ignore-platform-reqs
```

### 4. Start the Environment (Sail)
Start the containers in the background:
```bash
./vendor/bin/sail up -d
```

### 5. Configure the Application
Generate the application key and prepare the database with test data:
```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
```

### 5. Test the Application
Run the application test
```bash
./vendor/bin/sail artisan test
```

---

## 🏃 Frequently Used Commands

| Action | Command |
| :--- | :--- |
| **Start containers** | `./vendor/bin/sail up -d` |
| **Stop containers** | `./vendor/bin/sail stop` |
| **Run Artisan** | `./vendor/bin/sail artisan [command]` |
| **Run Tests** | `./vendor/bin/sail test` |
| **Access Shell** | `./vendor/bin/sail shell` |

---

## 🔐 Administrator Credentials
Once seeding is complete, you can log in with:
- **URL:** `http://localhost:8000`
- **Email:** `admin@tauros.com`
- **Password:** `password`

---

## 📄 License
This project is open-source software licensed under the [GNU AGPLv3](LICENSE).

---
Optimized for the field, powered with love. 🚜🐄
