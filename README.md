# Vigyapanam Affiliate Manager

A comprehensive WordPress plugin for managing affiliate programs, freelancers, and tracking performance.

## Features

### For Freelancers
- **Profile Management**: Create and manage professional profiles
- **Analytics Dashboard**: Track revenue, traffic, and RPM
- **Withdrawal System**: Easy withdrawal requests
- **Program Access**: View and participate in affiliate programs

### For Administrators
- **Client Management**: Add and manage client information
- **Performance Tracking**: Monitor conversions and traffic
- **Freelancer Management**: Track top performers and manage accounts
- **Analytics**: Comprehensive reporting and visualization

## Installation

1. Download the plugin files
2. Upload the `vigyapanam-affiliate` folder to your `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Configure the plugin settings via the admin dashboard

## Usage

### Shortcodes

The plugin provides several shortcodes for displaying different components:

```php
// Display the freelancer registration form
[vigyapanam_freelancer_profile]

// Display the freelancer dashboard
[vigyapanam_freelancer_dashboard]
```

### Admin Dashboard

Access the admin dashboard by navigating to:
`WordPress Admin Panel > Vigyapanam Affiliate`

Features available:
- Client Management
- Performance Tracking
- Freelancer Management
- Analytics Reports

### Freelancer Registration

1. Place the registration shortcode on any page:
```php
[vigyapanam_freelancer_profile]
```

2. Freelancers can fill out their profile with:
   - Personal Information
   - Social Media Links
   - Payment Details

### Freelancer Dashboard

1. Place the dashboard shortcode on a page:
```php
[vigyapanam_freelancer_dashboard]
```

2. Freelancers can access:
   - Performance Analytics
   - Revenue Reports
   - Withdrawal Options
   - Program Details

## Security Features

- IP tracking to prevent fake clicks
- Form validation and sanitization
- Secure data handling
- WordPress security best practices

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher

## Configuration

### Email Settings

The plugin uses WordPress's default email system. To customize email templates:

1. Navigate to the plugin settings
2. Modify email templates under "Email Settings"
3. Save changes

### Payment Settings

Configure payment options:
1. Go to plugin settings
2. Set minimum withdrawal amount
3. Configure payment methods
4. Save settings

## Troubleshooting

### Common Issues

1. Charts not displaying:
   - Ensure JavaScript is enabled
   - Check console for errors
   - Verify data exists in the database

2. Email notifications not working:
   - Check WordPress email settings
   - Verify email templates are configured
   - Check spam filters

### Support

For support:
1. Check documentation
2. Contact support team
3. Submit bug reports with detailed information

## Development

### File Structure

```
vigyapanam-affiliate/
├── assets/
│   ├── css/
│   │   ├── admin-style.css
│   │   └── frontend-style.css
│   └── js/
│       ├── admin-dashboard.js
│       └── frontend-dashboard.js
├── includes/
│   ├── Admin/
│   │   └── AdminDashboard.php
│   ├── Frontend/
│   │   ├── FreelancerDashboard.php
│   │   └── FreelancerProfile.php
│   ├── Utils/
│   │   ├── EmailManager.php
│   │   └── IPTracker.php
│   └── Core/
│       └── Plugin.php
└── vigyapanam-affiliate.php
```

### Adding New Features

1. Create new files in appropriate directories
2. Follow WordPress coding standards
3. Add necessary hooks and filters
4. Update documentation

## License

This plugin is licensed under the GPL v2 or later.

## Credits

Developed by Vigyapanam Team

## Changelog

### 1.0.0
- Initial release
- Basic functionality implemented
- Core features added