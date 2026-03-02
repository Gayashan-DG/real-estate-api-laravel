# Property Listing API

A production-ready RESTful API for managing property listings with authentication, image uploads, filtering, pagination, and sorting.

## Tech Stack

- **PHP** 8.x
- **Laravel** 11.x
- **MySQL** 8.x
- **Laravel Sanctum** (API Authentication)

## Features

### ✅ Complete CRUD Operations
- Create, read, update, and delete property listings
- Full input validation
- Proper HTTP status codes

### ✅ Authentication & Authorization
- User registration with bcrypt password hashing
- Token-based authentication (Laravel Sanctum)
- Login/logout with token management
- Protected routes (only authenticated users can create/update/delete)
- Ownership control (users can only edit their own properties)

### ✅ Image Upload System
- File validation (JPEG, PNG, GIF, max 2MB)
- Secure storage in Laravel's storage system
- Auto-generated public URLs
- Automatic cleanup on update/delete

### ✅ Advanced Filtering
- Filter by city (partial match)
- Filter by country (partial match)
- Filter by property type (house, apartment, land, commercial)
- Filter by status (available, sold, rented)
- Filter by bedrooms/bathrooms
- Filter by price range (min_price, max_price)
- Combine multiple filters

### ✅ Pagination
- Server-side pagination (efficient and scalable)
- Flexible items per page (1-100, default 15)
- Full metadata (current page, total pages, navigation links)

### ✅ Sorting
- Sort by price, created date, bedrooms, bathrooms
- Ascending/descending order (use `-` prefix for descending)
- Works with all filters

## Installation

### Prerequisites
- PHP 8.x
- Composer
- MySQL 8.x

### Setup Steps

1. **Clone the repository**
```bash
git clone <your-repo-url>
cd property-api
```

2. **Install dependencies**
```bash
composer install
```

3. **Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database**

Edit `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=property_api
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Run migrations**
```bash
php artisan migrate
```

6. **Create storage symlink**
```bash
php artisan storage:link
```

7. **Start the development server**
```bash
php artisan serve
```

API will be available at: `http://127.0.0.1:8000`

## API Documentation

### Base URL
```
http://127.0.0.1:8000/api
```

---

## Authentication Endpoints

### Register
Create a new user account.

**Endpoint:** `POST /register`

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response:** `201 Created`
```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2026-02-03T10:00:00.000000Z",
        "updated_at": "2026-02-03T10:00:00.000000Z"
    },
    "access_token": "1|a8sdf7a9sd8f7a9sd8f7a9sd8f7a9sd8f...",
    "token_type": "Bearer"
}
```

---

### Login
Authenticate and receive an access token.

**Endpoint:** `POST /login`

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response:** `200 OK`
```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    },
    "access_token": "2|b9teg8b0te9g8b0te9g8b0te9g8b0te9...",
    "token_type": "Bearer"
}
```

---

### Logout
Revoke the current access token.

**Endpoint:** `POST /logout`

**Headers:**
```
Authorization: Bearer {your_token}
```

**Response:** `200 OK`
```json
{
    "message": "Logged out successfully"
}
```

---

## Property Endpoints

### Get All Properties (Public)
Retrieve a paginated list of properties with optional filtering and sorting.

**Endpoint:** `GET /properties`

**Query Parameters:**
- `page` - Page number (default: 1)
- `per_page` - Items per page (1-100, default: 15)
- `city` - Filter by city (partial match)
- `country` - Filter by country (partial match)
- `property_type` - Filter by type (house, apartment, land, commercial)
- `status` - Filter by status (available, sold, rented)
- `bedrooms` - Filter by number of bedrooms
- `bathrooms` - Filter by number of bathrooms
- `min_price` - Minimum price
- `max_price` - Maximum price
- `sort` - Sort field (price, created_at, bedrooms, bathrooms). Prefix with `-` for descending

**Examples:**

Basic query:
```
GET /api/properties
```

With filters and sorting:
```
GET /api/properties?city=Miami&property_type=house&min_price=200000&max_price=600000&sort=-price&per_page=10
```

**Response:** `200 OK`
```json
{
    "current_page": 1,
    "data": [
        {
            "id": 1,
            "user_id": 1,
            "title": "Luxury Beach House",
            "description": "Beautiful 3-bedroom house with ocean view",
            "price": "450000.00",
            "property_type": "house",
            "address": "123 Ocean Drive",
            "city": "Miami",
            "province_state": "Florida",
            "country": "USA",
            "bedrooms": 3,
            "bathrooms": 2,
            "area_sqft": "2500.50",
            "latitude": "25.76170000",
            "longitude": "-80.19180000",
            "image_path": "properties/abc123xyz.jpg",
            "status": "available",
            "created_at": "2026-02-03T10:00:00.000000Z",
            "updated_at": "2026-02-03T10:00:00.000000Z"
        }
    ],
    "first_page_url": "http://127.0.0.1:8000/api/properties?page=1",
    "from": 1,
    "last_page": 5,
    "last_page_url": "http://127.0.0.1:8000/api/properties?page=5",
    "next_page_url": "http://127.0.0.1:8000/api/properties?page=2",
    "path": "http://127.0.0.1:8000/api/properties",
    "per_page": 15,
    "prev_page_url": null,
    "to": 15,
    "total": 72
}
```

---

### Get Single Property (Public)
Retrieve a specific property by ID.

**Endpoint:** `GET /properties/{id}`

**Response:** `200 OK`
```json
{
    "id": 1,
    "user_id": 1,
    "title": "Luxury Beach House",
    "description": "Beautiful 3-bedroom house with ocean view",
    "price": "450000.00",
    "property_type": "house",
    "address": "123 Ocean Drive",
    "city": "Miami",
    "province_state": "Florida",
    "country": "USA",
    "bedrooms": 3,
    "bathrooms": 2,
    "area_sqft": "2500.50",
    "latitude": "25.76170000",
    "longitude": "-80.19180000",
    "image_path": "properties/abc123xyz.jpg",
    "status": "available",
    "created_at": "2026-02-03T10:00:00.000000Z",
    "updated_at": "2026-02-03T10:00:00.000000Z"
}
```

---

### Create Property (Protected)
Create a new property listing. User is automatically assigned as the owner.

**Endpoint:** `POST /properties`

**Headers:**
```
Authorization: Bearer {your_token}
Content-Type: application/json
```

**Request Body (JSON - without image):**
```json
{
    "title": "Modern Downtown Condo",
    "description": "Newly renovated 2BR apartment",
    "price": 350000,
    "property_type": "apartment",
    "address": "456 Park Avenue",
    "city": "New York",
    "province_state": "New York",
    "country": "USA",
    "bedrooms": 2,
    "bathrooms": 2,
    "area_sqft": 1500.00,
    "latitude": 40.7589,
    "longitude": -73.9851,
    "status": "available"
}
```

**Request Body (form-data - with image):**

Use `form-data` in Postman with these fields:
- `title` (text): "Modern Downtown Condo"
- `description` (text): "Newly renovated 2BR apartment"
- `price` (text): 350000
- `property_type` (text): apartment
- `address` (text): "456 Park Avenue"
- `city` (text): "New York"
- `province_state` (text): "New York"
- `country` (text): "USA"
- `bedrooms` (text): 2
- `bathrooms` (text): 2
- `status` (text): available
- `image` (file): [select file]

**Response:** `201 Created`
```json
{
    "id": 2,
    "user_id": 1,
    "title": "Modern Downtown Condo",
    "description": "Newly renovated 2BR apartment",
    "price": "350000.00",
    "property_type": "apartment",
    "address": "456 Park Avenue",
    "city": "New York",
    "province_state": "New York",
    "country": "USA",
    "bedrooms": 2,
    "bathrooms": 2,
    "area_sqft": "1500.00",
    "latitude": "40.75890000",
    "longitude": "-73.98510000",
    "image_path": "properties/xyz789abc.jpg",
    "image_url": "http://127.0.0.1:8000/storage/properties/xyz789abc.jpg",
    "status": "available",
    "created_at": "2026-02-03T11:00:00.000000Z",
    "updated_at": "2026-02-03T11:00:00.000000Z"
}
```

---

### Update Property (Protected)
Update a property. Only the owner can update.

**Endpoint:** `PUT /properties/{id}`

**Headers:**
```
Authorization: Bearer {your_token}
Content-Type: application/json
```

**Request Body (partial update allowed):**
```json
{
    "price": 375000,
    "status": "sold"
}
```

**Response:** `200 OK`
```json
{
    "id": 2,
    "user_id": 1,
    "title": "Modern Downtown Condo",
    "price": "375000.00",
    "status": "sold",
    ...
}
```

**Error Response (not owner):** `403 Forbidden`
```json
{
    "message": "Forbidden. You can only update your own properties."
}
```

---

### Delete Property (Protected)
Delete a property. Only the owner can delete.

**Endpoint:** `DELETE /properties/{id}`

**Headers:**
```
Authorization: Bearer {your_token}
```

**Response:** `204 No Content`

**Error Response (not owner):** `403 Forbidden`
```json
{
    "message": "Forbidden. You can only delete your own properties."
}
```

---

## Error Responses

### 401 Unauthorized
Missing or invalid authentication token.
```json
{
    "message": "Unauthenticated."
}
```

### 404 Not Found
Resource doesn't exist.
```json
{
    "message": "No query results for model [App\\Models\\Property] 999"
}
```

### 422 Unprocessable Entity
Validation errors.
```json
{
    "message": "The price field is required. (and 2 more errors)",
    "errors": {
        "price": ["The price field is required."],
        "city": ["The city field is required."],
        "title": ["The title has already been taken."]
    }
}
```

---

## Database Schema

### Properties Table
```sql
id                  bigint (primary key)
user_id             bigint (foreign key → users.id)
title               varchar(255)
description         text (nullable)
price               decimal(10,2)
property_type       enum('house','apartment','land','commercial')
address             varchar(255)
city                varchar(255)
province_state      varchar(255) (nullable)
country             varchar(255)
bedrooms            int (nullable)
bathrooms           int (nullable)
area_sqft           decimal(10,2) (nullable)
latitude            decimal(10,8) (nullable)
longitude           decimal(11,8) (nullable)
image_path          varchar(255) (nullable)
status              enum('available','sold','rented') default 'available'
created_at          timestamp
updated_at          timestamp
```

### Users Table
```sql
id                  bigint (primary key)
name                varchar(255)
email               varchar(255) (unique)
password            varchar(255) (hashed)
created_at          timestamp
updated_at          timestamp
```

---

## What I Learned

### Backend Development
- **MVC Architecture** - Separation of concerns (Models, Controllers, Routes)
- **RESTful API Design** - Proper HTTP methods, status codes, and resource naming
- **Database Design** - Migrations, relationships, constraints
- **Authentication** - Token-based auth with Laravel Sanctum
- **Authorization** - Ownership checks and permission control

### Laravel Specific
- **Eloquent ORM** - Model relationships, query building, mass assignment protection
- **Validation** - Request validation with custom rules
- **File Storage** - Handling file uploads, storage drivers, symlinks
- **Middleware** - Authentication middleware, route protection
- **API Resources** - Transforming data for API responses

### Advanced Features
- **Pagination** - Server-side pagination with metadata
- **Filtering** - Dynamic query building with multiple conditions
- **Sorting** - Flexible sorting with whitelisted fields
- **Security** - Input validation, SQL injection prevention, password hashing

### Best Practices
- **DRY Principle** - Avoiding code repetition with loops and arrays
- **Error Handling** - Proper HTTP status codes and error messages
- **API Documentation** - Clear endpoint documentation with examples
- **Code Organization** - Clean, readable, maintainable code structure

---

## Future Improvements

- [ ] Add sorting to more fields (address, title, etc.)
- [ ] Implement multiple image uploads per property
- [ ] Add user profile management (change password, delete account)
- [ ] Implement email verification
- [ ] Add rate limiting to prevent API abuse
- [ ] Create automated tests (PHPUnit/Pest)
- [ ] Add API versioning
- [ ] Implement soft deletes for properties
- [ ] Add property favorites/bookmarks feature
- [ ] Create an admin panel
- [ ] Deploy to production server

---

## License

This project is open-source and available under the MIT License.

---

## Contact

**Developer:** [Dewmina Gayashan]  
**GitHub:** [https://github.com/Gayashan-DG]  
**Email:** [dewminagayashan.dg@gmail.com]

---

## Acknowledgments

Built as a learning project to master Laravel backend development and RESTful API design.
