# Queue Sample

## Overview
This project demonstrates the performance difference between using Laravel's Query Builder and Eloquent ORM while handling large datasets. It also showcases the benefits of using queues to process large amounts of data efficiently.

## Database Seeding
The following data is injected into the database using seeders:
- **200,000 records** in the `orders` table
- **1,000,000 records** in the `items` table
- **1,000 records** in the `products` table

## Performance Comparison
### You need to increase your timeout to at least 60 seconds for both tests

### Loading & Processing Orders
- **Without Queue:**
  - Method: `getAllSimple` in `QueueController`
  - Process: Loads **5000 records** from the `orders` table and saves an invoice for each order.
  - Result: **Timeout error due to heavy processing.**

- **With Queue:**
  - Method: `getAllWithQueue` in `QueueController`
  - Process: Loads **5000 records** and queues the invoice generation for each order.
  - Result: **Faster execution without timeout issues.**

## Tools Used
The following Laravel tools are integrated to improve performance, debugging, and queue management:
1. **[Debugbar](https://github.com/barryvdh/laravel-debugbar)** - Debugging and performance monitoring.
2. **[Horizon](https://laravel.com/docs/queues#monitoring)** - Queue monitoring and management.
3. **[Laravel DomPDF](https://github.com/barryvdh/laravel-dompdf)** - PDF generation for invoices.
4. **[Redis](https://laravel.com/docs/redis)** - High-performance queue driver.

## Installation & Setup
1. Clone the repository:
   ```sh
   git clone <repository-url>
   cd queue-sample
   ```
2. Install dependencies:
   ```sh
   composer install
   npm install
   ```
3. Configure environment:
   ```sh
   cp .env.example .env
   php artisan key:generate
   ```
4. Set up database and run migrations:
   ```sh
   php artisan migrate --seed
   ```
5. Start Horizon:
   ```sh
   php artisan horizon
   ```
6. Use 127.0.0.1/orders

## Usage
- To test performance without queue:
  ```sh
  php artisan queue:test --without-queue
  ```
- To test performance with queue:
  ```sh
  php artisan queue:test --with-queue
  ```

## Conclusion
Using Laravel queues significantly improves performance when handling large datasets, preventing timeout issues and ensuring efficient background processing.

