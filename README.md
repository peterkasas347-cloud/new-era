# UrbanSync App

**A Community Housing + Marketplace Platform**

UrbanSync is a comprehensive web platform connecting students with housing opportunities and local traders with customers through an integrated marketplace.

## Features

### Core Functionality
- **User Management**: Student renters/buyers, Housing providers (landlords), Traders (sellers), Admin panel
- **Housing Listings**: Rent/own properties with details (bedrooms, bathrooms, price, location)
- **Marketplace**: E-commerce for local goods and services
- **Search & Filters**: By location, price range, bedrooms, category
- **Interactive Maps**: Leaflet.js + OpenStreetMap integration
- **In-app Messaging**: Tenant↔Landlord and Buyer↔Seller communication
- **Favorites/Bookmarks**: Save listings for later
- **Orders & Payments**: Shopping cart, checkout, order tracking
- **Admin Dashboard**: Moderation and content management

## Tech Stack

- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Maps**: Leaflet.js + OpenStreetMap
- **Server**: Apache with mod_rewrite

## Project Structure

```
urbansync/
├── public_html/
│   ├── index.php
│   ├── css/
│   │   ├── style.css
│   │   ├── responsive.css
│   │   └── components.css
│   ├── js/
│   │   ├── main.js
│   │   ├── map.js
│   │   ├── auth.js
│   │   └── validation.js
│   └── images/
│       └── (static assets)
├── protected/
│   ├── config/
│   │   ├── config.php
│   │   └── database.php
│   ├── models/
│   │   ├── User.php
│   │   ├── House.php
│   │   ├── Product.php
│   │   ├── Order.php
│   │   ├── Message.php
│   │   └── Favorite.php
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── HouseController.php
│   │   ├── ProductController.php
│   │   ├── OrderController.php
│   │   ├── MessageController.php
│   │   └── AdminController.php
│   ├── views/
│   │   ├── home.php
│   │   ├── houses/
│   │   ├── products/
│   │   ├── user/
│   │   ├── admin/
│   │   └── messaging/
│   └── utils/
│       ├── Session.php
│       ├── Validator.php
│       └── Helper.php
└── database/
    └── schema.sql
```

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/peterkasas347-cloud/new-era.git
   cd new-era
   ```

2. **Setup Database**
   ```bash
   mysql -u root -p < database/schema.sql
   ```

3. **Configure Settings**
   - Edit `protected/config/config.php` with your database credentials
   - Update `protected/config/database.php` connection settings

4. **Set Permissions**
   ```bash
   chmod 755 public_html
   chmod 644 public_html/*.php
   chmod 700 protected
   ```

5. **Start Development Server**
   ```bash
   php -S localhost:8000 -t public_html
   ```

6. **Access Application**
   - Navigate to `http://localhost:8000`

## Documentation

- [API Documentation](docs/API.md)
- [Database Schema](database/schema.sql)
- [User Guide](docs/USER_GUIDE.md)
- [Developer Guide](docs/DEVELOPER_GUIDE.md)

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct and the process for submitting pull requests.

## License

This project is licensed under the MIT License - see [LICENSE](LICENSE) file for details.

## Support

For support, email support@urbansync.local or open an issue on GitHub.

---

**UrbanSync** - Connecting Communities, One Listing at a Time.
