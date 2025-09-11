# Laravel E-commerce API

A modern, secure e-commerce REST API built with Laravel 12, featuring role-based access control, comprehensive product management, and order processing capabilities.

## 🚀 Features

- **User Management**: Role-based authentication with Admin, Seller, and Customer roles
- **Product Management**: Full CRUD operations for products with stock tracking
- **Category Management**: Organize products with categories
- **Order Processing**: Complete order workflow with order items and status tracking
- **Stock Management**: Real-time inventory tracking
- **Security**: Laravel Sanctum token-based authentication with role-specific abilities
- **API Versioning**: Structured v1 API endpoints
- **Data Validation**: Comprehensive request validation and error handling

## 🛠 Technology Stack

- **Framework**: Laravel 12.28.1
- **PHP**: 8.4.1
- **Database**: MySQL
- **Authentication**: Laravel Sanctum 4.2.0
- **Testing**: PHPUnit 11.5.36
- **Code Style**: Laravel Pint 1.24.0
- **Additional Packages**: Spatie Query Builder

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

- PHP >= 8.2
- Composer
- MySQL
- Node.js & NPM (for frontend assets)
- Git

## 🔧 Installation & Setup

### 1. Clone the Repository

```bash
git clone https://github.com/init0x1/laravel-ecommerce-api.git
cd laravel-ecommerce-api
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Database

Edit your `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_ecommerce_api
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations & Seeders

```bash
# Create database tables
php artisan migrate

# Seed the database (optional)
php artisan db:seed
```

### 6. Clear & Optimize Application

```bash
# Clear and optimize cache
php artisan optimize:clear
php artisan optimize
```

### 7. Start the Development Server

```bash
# Start Laravel development server
php artisan serve

# Server will run at: http://127.0.0.1:8000
```

## 🏗 Architecture Overview

The application follows a clean architecture pattern with:

### Directory Structure

```
app/
├── DTOs/                 # Data Transfer Objects
│   ├── Categories/
│   ├── Orders/
│   ├── Products/
│   ├── Stocks/
│   └── Users/
├── Entities/
│   ├── Enums/           # Application enums (UserType, OrderStatus)
│   ├── Models/          # Eloquent models
│   └── Relations/       # Model relationships
├── Http/
│   ├── Controllers/     # API controllers
│   └── Requests/        # Form request validation
├── Policies/            # Authorization policies
├── Repositories/        # Repository pattern implementation
├── Services/            # Business logic layer
└── Traits/              # Reusable traits
```

### Key Design Patterns

- **Repository Pattern**: Abstraction layer for data access
- **Service Layer**: Business logic encapsulation
- **DTO Pattern**: Type-safe data transfer
- **Policy-based Authorization**: Role and ability-based access control

## 🔐 Authentication & Authorization

### User Roles

- **Admin**: Full system access (categories, products, users, orders, stock)
- **Seller**: Product and inventory management
- **Customer**: Order creation and viewing

### Authentication Flow

1. **Register/Login**: Obtain an access token
2. **Include Token**: Add `Authorization: Bearer {token}` header to requests
3. **Role Validation**: Each endpoint validates user permissions

### Example Authentication

```bash
# Register a new user
curl -X POST http://127.0.0.1:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Abdelrahman Ali",
    "email": "admin@init0x1.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "admin"
  }'

# Login
curl -X POST http://127.0.0.1:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "admin@init0x1.com",
    "password": "password123"
  }'
```

## 📚 API Documentation

### Base URL
```
http://127.0.0.1:8000/api/v1
```

### Authentication Routes

| Method | Endpoint | Description | Access |
|--------|----------|-------------|---------|
| POST | `/auth/register` | Register new user | Public |
| POST | `/auth/login` | User login | Public |

### Category Routes

| Method | Endpoint | Description | Access |
|--------|----------|-------------|---------|
| GET | `/categories` | List all categories | Public |
| GET | `/categories/{id}` | Get category by ID | Public |
| POST | `/categories` | Create category | Admin |
| PUT | `/categories/{id}` | Update category | Admin |
| DELETE | `/categories/{id}` | Delete category | Admin |

### Product Routes

| Method | Endpoint | Description | Access |
|--------|----------|-------------|---------|
| GET | `/products` | List all products | Public |
| GET | `/products/{id}` | Get product by ID | Public |
| POST | `/products` | Create product | Admin/Seller |
| PUT | `/products/{id}` | Update product | Admin/Seller |
| DELETE | `/products/{id}` | Delete product | Admin/Seller |

### Order Routes

| Method | Endpoint | Description | Access |
|--------|----------|-------------|---------|
| GET | `/orders` | List user orders | Authenticated |
| GET | `/orders/{id}` | Get order details | Authenticated |
| POST | `/orders` | Create new order | Customer |
| PUT | `/orders/{id}` | Update order | Admin/Seller |

### Stock Routes

| Method | Endpoint | Description | Access |
|--------|----------|-------------|---------|
| GET | `/stocks` | List all stock | Public |
| GET | `/stocks/{id}` | Get stock by ID | Public |
| PUT | `/stocks/{id}` | Update stock quantity | Admin/Seller |

## 💡 API Usage Examples

### Create a Product (Admin/Seller)

```bash
curl -X POST http://127.0.0.1:8000/api/v1/products \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer {your-token}" \
  -d '{
    "name": "Laptop Pro",
    "description": "High-performance laptop",
    "unit_price": 1299.99,
    "category_id": 1,
    "initial_quantity": 10
  }'
```

### Create an Order (Customer)

```bash
curl -X POST http://127.0.0.1:8000/api/v1/orders \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer {customer-token}" \
  -d '{
    "shipping_address": "123 Main St, City, State 12345",
    "products": [
      {
        "product_id": 1,
        "quantity": 2
      },
      {
        "product_id": 2,
        "quantity": 1
      }
    ]
  }'
```

### Update Stock (Admin/Seller)

```bash
curl -X PUT http://127.0.0.1:8000/api/v1/stocks/1 \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer {admin-token}" \
  -d '{
    "quantity": 50
  }'
```

## 🗃 Database Schema

### Core Tables

- **users**: User accounts with roles (admin, seller, customer)
- **categories**: Product categories
- **products**: Product catalog with pricing and seller info
- **stocks**: Inventory tracking per product
- **orders**: Customer orders with status and shipping
- **order_items**: Individual items within orders
- **personal_access_tokens**: Sanctum authentication tokens

### Relationships

- Products belong to categories and sellers
- Orders belong to customers (users)
- Order items link orders to products
- Stocks have one-to-one relationship with products

## 🧪 Testing

### Run Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ProductTest.php

# Run with coverage
php artisan test --coverage
```

### Test Structure

- **Feature Tests**: API endpoint testing
- **Unit Tests**: Service and repository testing
- **Database**: Factory-based test data generation

## 🔍 Development Tools

### Code Quality

```bash
# Format code with Pint
vendor/bin/pint

# Check code style
vendor/bin/pint --test
```

### Debugging

```bash
# View application logs
tail -f storage/logs/laravel.log

# Use Tinker for interactive debugging
php artisan tinker
```

### Cache Management

```bash
# Clear all caches
php artisan optimize:clear

# Cache configuration
php artisan optimize
```

## 🚦 Error Handling

The API returns consistent JSON responses:

### Success Response Format
```json
{
  "data": {
    // Response data
  }
}
```

### Error Response Format
```json
{
  "message": "Error description",
  "errors": {
    "field": ["Validation error message"]
  }
}
```

### HTTP Status Codes

- **200**: Success
- **201**: Created
- **400**: Bad Request
- **401**: Unauthorized
- **403**: Forbidden
- **404**: Not Found
- **422**: Validation Error
- **500**: Server Error

## 🔒 Security Features

- **Token-based Authentication**: Secure API access with Laravel Sanctum
- **Role-based Authorization**: Fine-grained permissions per user role
- **Input Validation**: Comprehensive request validation
- **SQL Injection Protection**: Eloquent ORM with prepared statements
- **CORS Protection**: Configurable cross-origin requests
- **Rate Limiting**: Built-in request throttling

## 📊 Performance Considerations

- **Database Indexing**: Optimized queries with proper indexes
- **Eager Loading**: Prevent N+1 query problems
- **Caching**: Laravel's built-in cache system
- **Query Optimization**: Efficient repository patterns

