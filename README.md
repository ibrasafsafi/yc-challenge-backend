# YouCan Challenge Backend API Documentation

## Installation

### Requirements

- PHP 8.1
- Composer
- NodeJS

### Backend Installation

To install the backend project, follow these steps:

1. Clone the repository
2. Run `composer install`
3. Run `cp .env.example .env`
4. Run `php artisan key:generate`
5. Run `php artisan migrate --seed`
6. Run `php artisan serve`
7. Go to run the frontend project
8. Login with `admin@admin.com` and `password`
9. Enjoy!

### Testing

1. Run `php artisan test`
2. Enjoy!

Or You can run tests one by one by running `php artisan test --filter=TestName`

### Project Structure

- `app/Http/Controllers` contains all the API controllers
- `app/Http/Requests` contains all the API requests
- `app/Models` contains all the models
- `app/Repositories` contains all the repositories
- `app/Http/Ressources` contains all the API resources (transformers)
- `database/migrations` contains all the database migrations
- `database/seeders` contains all the database seeders
- `database/factories` contains all the database factories
- `routes/api.php` contains all the API routes
- `tests/Feature` contains all the feature tests

### Description

This project is a simple CRUD application for products and categories with authentication.

In the backend, I used the repository pattern to separate the business logic from the controllers.

In This project, I used Laravel Sanctum for authentication and VueJS for the frontend.

In This project i implemented the following features:

- CRUD for products with categories (belongs to many)
- CRUD for categories
- Authentication
- API Documentation
- Feature Testing

## API Documentation

### API Routes For Products

<table>
<tr>
<th>Method</th>
<th>URI</th>
<th>Description</th>
<th>Payload</th>
</tr>

<tr>
<td>GET</td>
<td>/products</td>
<td>Get all products from the database with categories</td>
<td></td>
</tr>

<tr>
<td>POST</td>
<td>/products</td>
<td>Add a product to the database with categories if you want to</td>
<td>

```json
{
  "name": "product name",
  "price": 1000,
  "image": "https://via.placeholder.com/150",
  "description": "product description",
  "categories": [
    {
      "id": 1,
      "name": "category name"
    }
  ]
}
```

</td>

</tr>

<tr>
<td>PUT</td>
<td>/products/{product}</td>
<td>Update a product from the database with categories if you want to</td>
<td>

```json
{
  "name": "product name",
  "price": 1000,
  "image": "https://via.placeholder.com/150",
  "description": "product description",
  "categories": [
    {
      "id": 1,
      "name": "category name"
    }
  ]
}
```

</td>

</tr>

<tr>
<td>DELETE</td>
<td>/products/{product}</td>
<td>Delete a product from the database</td>
<td></td>
</tr>

</table>

### API Routes For Categories

<table>
<tr>
<th>Method</th>
<th>URI</th>
<th>Description</th>
<th>Payload</th>
</tr>

<tr>
<td>GET</td>
<td>/categories</td>
<td>Get all categories from the database</td>
<td></td>
</tr>

<tr>
<td>POST</td>
<td>/categories</td>
<td>Add a category to the database</td>
<td>

```json
{
  "name": "category name",
  "parent_category": {
    "id": 1,
    "name": "parent category name"
  }
}
```

</td>

</tr>

<tr>
<td>PUT</td>
<td>/categories/{category}</td>
<td>Update a category from the database</td>
<td>

```json
{
  "name": "category name",
  "parent_category": {
    "id": 1,
    "name": "parent category name"
  }
}
```

</td>

</tr>

<tr>
<td>DELETE</td>
<td>/categories/{category}</td>
<td>Delete a category from the database</td>
<td></td>
</tr>

</table>

#### Authentication

##### Login

- **URL:** `/api/v1/login`
- **Method:** `POST`
- **URL Params:** `None`
- **Data Params:**
  - `email: string`
  - `password: string`

##### Logout

- **URL:** `/api/v1/logout`
- **Method:** `POST`
- **URL Params:** `None`
- **Data Params:** `None`

##### Register

- **URL:** `/api/v1/register`
- **Method:** `POST`
- **URL Params:** `None`
- **Data Params:**
  - `name: string`
  - `email: string`
  - `password: string`
  - `password_confirmation: string`

## CLI Commands

#### Create a new product

- **Command:** `php artisan product:manage --create`
- **Description:** Create a new product, and then you can fill what you want to add from the questions that will appear

#### Update a product

- **Command:** `php artisan product:manage --update`
- **Description:** update a product by id, and then you can choose what you want to update from the questions that will
  appear, and you can leave the field empty if you don't want to update it

#### Delete a product

- **Command:** `php artisan product:manage --delete`
- **Description:** delete a product by id






