# API Transaction Documentation

## Setup Application

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL/MariaDB
- Git

### Installation Steps
1. Clone the repository
```bash
git clone <repository-url>
cd api_transaksi
```

2. Install dependencies
```bash
composer install
```

3. Environment setup
```bash
cp .env.example .env 
cp .env .env.testing (untuk menggunakan testing)
php artisan key:generate
```

4. Configure database in `.env`
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Run migrations
```bash
php artisan migrate
```

## API Documentation

1. get transaksi 

```
GET /api/sales
```
2.  get transaksi  data dan target
```
GET /api/sales/current-month
```
- request Params 
```
?sales=Salimah Handayani
```
3. get transaksi bulan ini dan target
```
GET api/sales/current-month-with-target
```
- request Params 
```
?is_underperform=true&month=April 2025
```

#### Create Transaction
```
POST /api/customer
```
Request body:
```json
{
  "name": "amarValidationPhone",
  "phone": "085732931740",
  "address": "jogja"
}
```

```
POST /api/sales-order
```
Request Body
```json
{
  "reference_no": "INV89867553345",
  "sales_id": "14",
  "customer_id": "1001"
}
```

```
POST /api/sales-order-item
```
Request Body
```json
{
  "quantity": 1,
  "sales_id": 14,
  "product_id": 17,
  "customer_id": 1001,
  "production_price": 4523325,
  "selling_price" : 324231243,
  "order_id": 73
}
```

#### update transaction
```
PUT /api/customer/{id}
```
Path Params 
```
id -> id dari tabel customers
```
Request Body
```json
{
  "name": "amartest",
  "phone": "6285732931730",
  "address": "jogja"
}
```

## Unit Testing

### Setup Testing Environment


1. Configure `.env.testing`
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=testing_database
```

### Running Tests
```bash
php artisan test
```

### Creating Tests
```bash
php artisan make:test TransactionTest
```

Example test case:
```php
public function test_can_create_transaction()
{
    $response = $this->postJson('/api/transactions', [
        'amount' => 1000,
        'description' => 'Test transaction'
    ]);

    $response->assertStatus(201);
}
```

## License
This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
