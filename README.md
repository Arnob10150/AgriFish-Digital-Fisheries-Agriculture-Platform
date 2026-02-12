# AgriFish - Digital Fisheries & Agriculture Platform

## Overview
AgriFish is a comprehensive web-based platform designed to digitize and streamline operations in the fisheries and agriculture sectors. It provides tailored tools and services for a wide range of users, including administrators, customers, fishermen, and farmers, enabling efficient management and operations in the agri-fish industry.

## Technologies Used
This platform is built using the following technologies:
- **PHP** (78.5%) - Server-side scripting for backend logic and authentication.
- **CSS** (20.7%) - Styling for responsive and modern user interfaces.
- **JavaScript** (0.8%) - For enhanced interactivity and dynamic content.

## Features
- **User Authentication**: Provides secure login with role-based access control and individual dashboards.
- **Role-Based Dashboards**:
  - Admin Dashboard: Manage users and platform settings.
  - Customer Dashboard: Browse and purchase products.
  - Fisherman Dashboard: Facilitate the sale of fish.
  - Farmer Dashboard: Manage and sell agricultural products.
- **Product Management**: Supports listings for a wide range of products, including:
  - Freshwater fish
  - Sea fish
  - Shellfish
- **Demo User Accounts**: Pre-configured demo accounts for easy testing.

## Installation
### Prerequisites
To set up the platform, you will need:
- A local web server (e.g., XAMPP, WAMP, or Apache) with PHP version 7.4 or higher installed.

### Steps to Install
1. Clone the repository:
   ```bash
   git clone https://github.com/Arnob10150/AgriFish-Digital-Fisheries-Agriculture-Platform.git
   ```
2. Move the project files to your web server's root directory. For example:
   ```bash
   /htdocs/DFAP/
   ```
   **Note**: The directory must be named `DFAP` for correct loading of resources (e.g., images).
3. Set up the database if required (check `models/User.php` for database connection settings).
4. Start the local server and open the application in your browser:
   ```bash
   http://localhost/DFAP/
   ```

## Demo Users
Use the following test credentials to explore the platform. Each role provides unique features and access:

| **Role**      | **Email**            | **Password**    |
|---------------|----------------------|-----------------|
| Admin         | admin@dfap.com       | admin123        |
| Customer      | customer@dfap.com    | customer123     |
| Fisherman     | fisherman@dfap.com   | fisherman123    |
| Farmer        | farmer@dfap.com      | farmer123       |

## Contributing
Contributions are welcome! Follow these steps to contribute:
1. Fork the repository.
2. Create a new branch for your feature or bugfix.
3. Commit your changes with clear, descriptive messages.
4. Create a pull request and provide a detailed description of your changes.

## License
This project is licensed under the [MIT License](https://opensource.org/licenses/MIT). Feel free to use, modify, and distribute this project, as long as proper credit is given.
