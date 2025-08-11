# DCS Risk Register System - AML & FIC Compliance

A comprehensive anti-money laundering (AML) and Financial Intelligence Centre (FIC) compliance risk management system built with PHP, designed for financial audit and accounting firms. This system helps DCS identify, assess, and manage AML risks based on client profiles and service types in accordance with regulatory requirements.

## 🚀 Features

### Core Functionality
- **AML Risk Identification & Registration**: Complete risk entry forms with all required fields for FIC compliance
- **Client Risk Assessment**: Automated risk rating calculation based on client and service factors
- **AML Risk Monitoring**: Track risk status, priority, and ownership for regulatory reporting
- **FIC Compliance Dashboard**: Visual representation of risk distribution and statistics
- **Search & Filter**: Quick search functionality across all risk records
- **Export & Print**: Export data to CSV for regulatory reporting and audits

### Risk Assessment Categories
- **Client Type**: Natural Person vs Legal Person
- **Service Type**: Accounting, HR, Risk Advisory, Tax Consulting
- **Geographical Area**: Domestic, Regional, Foreign clients
- **Delivery Channel**: Face-to-face, Non-face-to-face, Combination
- **Payment Methods**: EFTs, SWIFT, Cash, POS

### AML Risk Rating System
- **Low Risk** (1-5): Accept with standard due diligence
- **Medium Risk** (6-8): Accept with enhanced monitoring
- **High Risk** (9+): Enhanced due diligence required under FIC regulations

## 📋 Requirements

- **Web Server**: Apache/Nginx with PHP support
- **PHP**: Version 7.4 or higher
- **Database**: MySQL 5.7 or higher
- **Browser**: Modern web browser with JavaScript enabled

## 🛠️ Installation

### 1. Server Setup
Ensure you have XAMPP, WAMP, or similar local server environment installed.

### 2. Database Setup
```bash
# Run the database setup script
php setup_database.php
```

This will:
- Create the `dcs_risk_register` database
- Create the `risks` table with all required fields
- Insert sample data for testing

### 3. Configuration
Edit `config/database.php` if you need to change database settings:
```php
return [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'dcs_risk_register',
    // ... other settings
];
```

### 4. Access the Application
Open your browser and navigate to:
```
http://localhost/DCS-Best/public/index.php
```

## 📁 Project Structure

```
DCS-Best/
├── config/
│   └── database.php          # Database configuration
├── public/
│   ├── index.php            # Main application file
│   ├── css/
│   │   └── style.css        # Custom styles
│   ├── js/
│   │   └── app.js           # JavaScript functionality
│   └── .htaccess            # Apache configuration
├── setup_database.php       # Database setup script
├── README.md               # This file
└── composer.json           # Project dependencies (if using Composer)
```

## 🎯 Usage Guide

### Adding a New Risk
1. Click "Add New Risk" from the main menu
2. Fill in the risk identification details
3. Complete the risk description and analysis
4. Set risk management parameters
5. Configure client risk assessment factors
6. The system will automatically calculate the risk rating
7. Click "Create Risk" to save

### Viewing Risk Register
- **Main List**: View all risks in a sortable table
- **Search**: Use the search box to find specific risks
- **Risk Rating Dashboard**: See risk distribution and statistics
- **Export**: Download data as CSV for external analysis

### Risk Assessment Factors
The system automatically calculates risk ratings based on:

| Factor | Low Risk | Medium Risk | High Risk |
|--------|----------|-------------|-----------|
| Client Type | Natural Person | Legal Person | - |
| Geographical Area | Domestic | Regional | Foreign |
| Delivery Channel | Face-to-face | Combination | Non-face-to-face |
| Payment Method | EFTs/SWIFT | POS | Cash |

## 🔧 Customization

### Adding New Risk Categories
Edit the form options in `public/index.php`:
```php
<option value="New Category">New Category</option>
```

### Modifying Risk Calculation
Update the calculation logic in `public/js/app.js`:
```javascript
// Modify the calculateRiskRating() function
if (likelihood.value === 'Very likely') totalRating += 3;
// ... adjust scoring as needed
```

### Styling Changes
Modify `public/css/style.css` to customize the appearance:
```css
:root {
    --primary-color: #your-color;
    --success-color: #your-color;
    /* ... other variables */
}
```

## 🔒 Security Features

- **SQL Injection Protection**: All database queries use prepared statements
- **XSS Protection**: Input sanitization and output escaping
- **CSRF Protection**: Form token validation
- **Security Headers**: X-Frame-Options, X-XSS-Protection, etc.
- **Directory Protection**: Disabled directory browsing

## 📊 Database Schema

### Risks Table
```sql
CREATE TABLE risks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    risk_name VARCHAR(255) NOT NULL,
    identification_date DATE DEFAULT CURRENT_TIMESTAMP,
    subtitle VARCHAR(255),
    risk_description TEXT,
    risk_category VARCHAR(255),
    risk_likelihood ENUM('Not likely','Likely','Very likely'),
    risk_impact_level ENUM('Very low','Low','Medium','High','Very high'),
    risk_mitigation_plan TEXT,
    risk_priority ENUM('1','2','3') DEFAULT '1',
    risk_owner VARCHAR(255),
    risk_status ENUM('Open','In progress','Closed','Active') DEFAULT 'Open',
    client_type ENUM('Natural Person','Legal Person'),
    service_type VARCHAR(255),
    geographical_area ENUM('Domestic client','Regional client','Foreign client'),
    delivery_channel VARCHAR(255),
    payment_method ENUM('EFTs','SWIFT','Cash','POS'),
    total_risk_rating INT,
    risk_assessment VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## 🚨 Troubleshooting

### Common Issues

**Database Connection Error**
- Ensure MySQL service is running
- Check database credentials in `config/database.php`
- Verify database exists: `dcs_risk_register`

**Page Not Found**
- Ensure Apache/Nginx is running
- Check file permissions
- Verify URL path is correct

**JavaScript Not Working**
- Check browser console for errors
- Ensure JavaScript is enabled
- Verify all JS files are loading

### Performance Optimization
- Enable Apache mod_deflate for compression
- Use browser caching for static files
- Optimize database queries for large datasets

## 📈 Future Enhancements

- [ ] User authentication and role-based access
- [ ] Advanced reporting and analytics
- [ ] Email notifications for high-risk items
- [ ] Risk trend analysis and forecasting
- [ ] Integration with external risk databases
- [ ] Mobile-responsive design improvements
- [ ] API endpoints for external integrations

## 🤝 Support

For technical support or feature requests, please contact your system administrator.

## 📄 License

This project is developed for internal use by DCS-Best. All rights reserved.

---

**Version**: 1.0.0  
**Last Updated**: January 2024  
**Compatibility**: PHP 7.4+, MySQL 5.7+ # Risk-Register-System-DCS
