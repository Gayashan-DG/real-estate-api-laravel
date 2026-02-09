# Real Estate Listings API (Laravel)

A backend REST API for managing real estate property listings.  
Built with Laravel, focusing on clean architecture, authentication, validation, and scalable querying.

---

## Purpose

This project was built to strengthen practical backend development skills, including:

- RESTful API design
- MVC architecture in Laravel
- Authentication and authorization
- Database design and migrations
- Secure file handling
- Pagination and filtering for scalable APIs

This is a backend-only project intended for API consumption by web or mobile clients.

---

## Tech Stack

- PHP
- Laravel
- MySQL
- Laravel Sanctum (API authentication)

---

## Core Features

### Properties CRUD API

- Create, read, update, and delete property listings
- Full server-side validation
- Proper HTTP status codes
- JSON-based API responses

---

### Authentication & Authorization

- User registration with hashed passwords
- Login with token-based authentication (Laravel Sanctum)
- Logout with token revocation
- Protected routes for create/update/delete actions
- Public access for viewing property listings
- Ownership control (users can only modify their own properties)

---

### Image Upload System

- Image file validation (type and size)
- Secure storage
- Publicly accessible URLs
- Automatic cleanup when properties are updated or deleted

---

### Filtering

The properties listing endpoint supports dynamic filtering via query parameters.

Available filters:

- `city` (partial match)
- `country` (partial match)
- `property_type` (exact match)
- `status` (exact match)
- `bedrooms` (exact match)
- `bathrooms` (exact match)

Price range filtering:

- `min_price`
- `max_price`

Example:

---

### Pagination

- Server-side pagination for performance
- Default: 15 items per page
- Maximum: 100 items per page

Query parameter:
- `per_page`

Example:

Paginated responses include metadata such as current page, total results, last page, and navigation links.

---

## API Access

- Authentication is required for creating, updating, or deleting properties
- Browsing and filtering properties is publicly accessible

---

## Optional / Future Improvements

- Sorting (price, date, etc.)
- API documentation (Postman collection)
- Automated tests
- Deployment to a production server

---

## Author

University of Colombo — Undergraduate  
Backend-focused Laravel development project
