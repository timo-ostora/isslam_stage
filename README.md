## Prerequisites

Before setting up the project, ensure you have the following installed on your local environment:

*   **PHP** (Check the `composer.json` file for the required version)
*   **Composer** (Dependency manager for PHP)
*   **Node.js & NPM** (For frontend asset compilation)
*   **Database Engine** (SQLite)

---

## Installation & Setup Instructions

Follow these step-by-step instructions to get the application running locally.

### 1. Clone or Extract the Project
If you haven't cloned it yet, navigate to your local server directory and clone the repository:
```bash
git clone https://github.com/timo-ostora/isslam_stage.git
cd isslam_stage
```
*(If you downloaded the project as a ZIP file, extract it and change your terminal directory into the root folder).*

### 2. Install PHP Dependencies
Install the required backend packages using [Composer](https://getcomposer.org):
```bash
composer install
```

### 3. Install Frontend Dependencies
Install the required Node modules and compile the assets:
```bash
npm install
npm run dev
```
*(Note: Use `npm run build` if you want to compile assets for production).*

### 4. Create the Environment Configuration
Copy the template `.env.example` file to create your local `.env` configuration file:
```bash
cp .env.example .env
```

### 5. Generate Application Key
Laravel requires a unique application encryption key. Generate it by running:
```bash
php artisan key:generate
```

### 7. Run Database Migrations & Seeders
Run the migrations to create tables in your database. If the project includes dummy data, append the seed flag:
```bash
php artisan migrate --seed
```

### 8. Link Storage (Optional)
If the application handles file uploads (like user avatars or documents), link the storage directory to the public directory:
```bash
php artisan storage:link
```

### 9. Start the Local Server
Launch Laravel's built-in development server:
```bash
php artisan serve
```

You can now access the application by opening your web browser and navigating to: **`http://127.0.0.1:8000`**

---
### 10. In another tirminal run vite server
Launch vite server:
```bash
npm run dev 
```


---
### 11. generate permission for all 
Launch filament shild generate policy and permission for all table:
```bash
php artisan shield:generate --all
```


---

## Additional Information

*   **login as superadmin** in localhost:8000/auth/login use this creditional
*   **email** : superadmin@test.com
*   **password**:   Superadmin
*   then navigate to localhost:8000/admin to see the admin
