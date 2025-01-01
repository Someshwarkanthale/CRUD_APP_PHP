# CRUD_APP_PHP

## Features
- View, add, update, and delete products.
- Bar chart to visualize the count of items per category.
- Modular code for maintainability.

## Setup Instructions
1. Clone the repository.
2. Set up a MySQL database and import the schema:
   ```sql
   CREATE TABLE products (
       id INT AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(255) NOT NULL,
       price DECIMAL(10, 2) NOT NULL,
       category VARCHAR(255) NOT NULL
   );
