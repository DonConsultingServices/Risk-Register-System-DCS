# DCS-Best Risk Management System

A comprehensive risk management system built with Laravel for DCS (Due Diligence and Compliance Services).

## Features

### 🎯 Core Risk Management
- **Risk Assessment**: Comprehensive risk evaluation and scoring
- **Client Management**: Full client lifecycle management with risk profiling
- **Risk Categories**: Organized risk classification system
- **Predefined Risks**: Standardized risk templates for common scenarios

### 📊 Dashboard & Analytics
- **Real-time Statistics**: Live risk metrics and performance indicators
- **Risk Matrix**: Visual risk assessment matrix (Impact vs Likelihood)
- **Status Distribution**: Risk status charts and analytics
- **Recent Activities**: Activity timeline and audit trail

### 🔧 System Features
- **User Management**: Role-based access control
- **Performance Optimization**: Cached statistics and optimized queries
- **Responsive Design**: Mobile-friendly interface
- **Export Capabilities**: Data export for reporting

## System Requirements

- **PHP**: 8.1 or higher
- **Laravel**: 10.x
- **Database**: MySQL 8.0 or higher
- **Web Server**: Apache/Nginx
- **Node.js**: For asset compilation (optional)

## Installation

### 1. Clone the Repository
```bash
git clone <repository-url>
cd DCS-Best
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Configuration
Update your `.env` file with database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dcs_best
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations and Seeders
```bash
php artisan migrate:fresh --seed
```

### 6. Start the Application
```bash
php artisan serve
```

## Database Structure

### Core Tables
- **users**: System users and authentication
- **clients**: Client information and risk profiles
- **risks**: Risk records and assessments
- **risk_categories**: Risk classification categories
- **predefined_risks**: Standard risk templates

### Key Relationships
- Clients can have multiple risks
- Risks belong to categories and clients
- Users can be assigned to risks
- All records include audit trails (created_by, updated_by)

## API Endpoints

### Dashboard API
- `GET /api/dashboard/stats` - Dashboard statistics
- `GET /api/dashboard/risk-matrix` - Risk matrix data
- `GET /api/dashboard/recent-risks` - Recent risk items
- `GET /api/dashboard/recent-activities` - Recent activities
- `GET /api/dashboard/risk-status-distribution` - Risk status chart data
- `POST /api/dashboard/clear-cache` - Clear dashboard cache

### Real-time Updates
- `GET /api/dashboard-updates/stats` - Real-time statistics
- `GET /api/dashboard-updates/live-metrics` - Live performance metrics
- `POST /api/dashboard-updates/clear-all-caches` - Clear all caches

## Usage

### 1. Authentication
- Access the system at `/login`
- Use the seeded admin credentials (check seeder files)

### 2. Dashboard
- View comprehensive risk overview
- Monitor key performance indicators
- Access quick actions for common tasks

### 3. Risk Management
- Create and manage risk records
- Assign risks to team members
- Track risk status and progress
- Generate risk reports

### 4. Client Management
- Add and manage client information
- Conduct risk assessments
- Track client risk profiles
- Generate client reports

## File Structure

```
DCS-Best/
├── app/
│   ├── Http/Controllers/     # Application controllers
│   ├── Models/              # Eloquent models
│   └── Services/            # Business logic services
├── database/
│   ├── migrations/          # Database schema
│   └── seeders/            # Sample data
├── resources/
│   └── views/              # Blade templates
├── public/
│   ├── css/                # Stylesheets
│   └── logo/               # Branding assets
└── routes/                  # Application routes
```

## Key Controllers

- **DashboardController**: Main dashboard functionality
- **RiskController**: Risk CRUD operations
- **ClientController**: Client management
- **RiskCategoryController**: Category management
- **DashboardUpdateController**: Real-time updates

## Models

- **Risk**: Core risk entity with relationships
- **Client**: Client information and risk associations
- **RiskCategory**: Risk classification system
- **PredefinedRisk**: Standard risk templates
- **User**: System users and authentication

## Services

- **PerformanceOptimizer**: Dashboard performance optimization
- **RiskCalculationService**: Risk scoring and assessment

## Styling

- **Bootstrap 5**: Responsive UI framework
- **Custom CSS**: Risk matrix and dashboard styling
- **Chart.js**: Data visualization
- **Font Awesome**: Icons and visual elements

## Security Features

- **Authentication**: Laravel's built-in auth system
- **Authorization**: Role-based access control
- **CSRF Protection**: Cross-site request forgery protection
- **Input Validation**: Comprehensive form validation
- **SQL Injection Protection**: Eloquent ORM protection

## Performance Features

- **Caching**: Dashboard statistics caching
- **Optimized Queries**: Efficient database queries
- **Lazy Loading**: Relationship lazy loading
- **Asset Optimization**: Minified CSS and JavaScript

## Troubleshooting

### Common Issues

1. **Migration Errors**: Ensure database credentials are correct
2. **Permission Issues**: Check file permissions for storage and cache
3. **Cache Issues**: Clear application cache with `php artisan cache:clear`
4. **Route Issues**: Clear route cache with `php artisan route:clear`

### Debug Mode
Enable debug mode in `.env`:
```env
APP_DEBUG=true
APP_ENV=local
```

## Support

For technical support or questions about the system, please contact the development team.

## License

This project is proprietary software developed for DCS (Due Diligence and Compliance Services).

---

**Version**: 1.0.0  
**Last Updated**: August 2024  
**Developed By**: DCS Development Team
