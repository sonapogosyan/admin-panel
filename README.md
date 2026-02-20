# TradeIQ Pulse - Admin Panel

A production-ready, high-performance admin panel built with Laravel 11 and Vue 3. This application serves as a robust foundation for client and order management, featuring a premium UI, role-based access control, and a fully dockerized development environment.

## 🚀 Features

### Core Authentication
- **Secure Onboarding**: User registration and multi-factor ready login system.
- **Account Management**: Comprehensive profile editing, password updates, and secure account deletion.
- **Session Security**: Powered by Laravel Sanctum and Inertia.js for seamless stateful authentication.

### Administrative Tools
- **RBAC (Role-Based Access Control)**: Granular permissions system with predefined `Admin` and `User` roles.
- **Client Management**: Full CRUD operations for client data with intelligent searching and status filtering.
- **Order Tracking**: Relational order management system linked to clients, featuring financial tracking and status workflows.

### User Experience
- **Interactive Dashboard**: Real-time business metrics and recent activity tracking.
- **Premium UI/UX**: Built with Tailwind CSS, featuring glassmorphism elements and shared layouts.
- **Optimized Loading**: Modern skeleton screen placeholders to eliminate layout shift during data fetching.

## 🛠 Tech Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Vue.js 3 (Composition API)
- **State/Routing**: Inertia.js & Ziggy
- **Styling**: Tailwind CSS
- **Database**: MySQL 8.0
- **Environment**: Docker & Docker Compose
- **Build Tool**: Vite

## 📥 Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/yourusername/tradeiq-pulse.git
   cd tradeiq-pulse
   ```

2. **Configure Environment**:
   ```bash
   cp backend/.env.example backend/.env
   ```
   *(Update the `.env` file with your specific database and application settings if not using the default Docker config).*

3. **Build and Start Docker Containers**:
   ```bash
   docker-compose up -d --build
   ```

4. **Install Dependencies**:
   ```bash
   # Install PHP dependencies
   docker exec admin_panel_app composer install

   # Install Frontend dependencies
   docker exec admin_panel_app npm install
   ```

5. **Generate Application Key & Migrate**:
   ```bash
   docker exec admin_panel_app php artisan key:generate
   docker exec admin_panel_app php artisan migrate --seed
   ```

6. **Compile Assets**:
   ```bash
   docker exec admin_panel_app npm run build
   ```

## ⚙️ Environment Variables (.env)

Key variables required for the application to run:

```env
APP_NAME="TradeIQ Pulse"
APP_ENV=local
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=true
APP_URL=http://localhost:7000

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=admin_panel
DB_USERNAME=admin
DB_PASSWORD=secret

SANCTUM_STATEFUL_DOMAINS=localhost:7000
```

## 🐳 Docker Usage

The project includes a multi-container Docker setup:
- **`admin_panel_app`**: PHP-FPM for Laravel.
- **`mysql`**: Database service.
- **`phpmyadmin`**: Database management (accessible at `localhost:7004`).

**Useful Commands:**
- Stop containers: `docker-compose stop`
- View logs: `docker-compose logs -f`
- Run artisan: `docker exec admin_panel_app php artisan [command]`

## 📡 API Overview

The application utilizes a hybrid API approach:
- **Web Routes**: Handled by Inertia.js for seamless SPA-like navigation.
- **RESTful Endpoints**: 
    - `GET /api/dashboard`: Aggregated statistics for the dashboard.
    - `GET /api/clients`: List clients with search/filters.
    - `POST /api/orders`: Create new relational orders.
- **Auth**: Protected by the `auth:sanctum` and `web` middleware groups.

## 🔮 Future Improvements

- [ ] Multi-tenant support for SaaS scaling.
- [ ] Export features (PDF/Excel) for order reports.
- [ ] Real-time notifications via Laravel Echo & Pusher.
- [ ] Dark mode toggle for the dashboard.

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).
