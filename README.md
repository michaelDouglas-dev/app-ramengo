# Ramen Orders API Project

## Description

This project is an API for managing ramen orders, including listing available broths and proteins, and registering new orders. The API is developed in PHP using best practices for security, scalability, and unit testing.

## Project Structure

The project structure is organized as follows:

project-root/
├── config/
│ ├── database.php
│ ├── api_auth.php
│ └── headers.php
│ └── config.php
├── logs/
│ └── api.log
├── src/
│ ├── Broths/
│ │ └── listBroths.php
│ ├── Proteins/
│ │ └── listProteins.php
│ ├── Orders/
│ │ └── placeOrder.php
├── tests/
│ └── BrothsTest.php
│ └── OrdersTest.php
│ └── ProteinsTest.php
├── .env
├── .gitignore
├── composer.json
├── index.php
└── README.md

markdown
Copiar código

## Configuration

### Requirements

- PHP 7.3+
- Composer
- MySQL

### Setup Steps

1. **Clone the repository:**

    ```bash
    git clone https://github.com/your-username/ramen-orders-api.git
    cd ramen-orders-api
    ```

2. **Install dependencies:**

    ```bash
    composer install
    ```

3. **Configure the environment:**

    Create a `.env` file in the project root with the following variables:

    ```env
    API_KEY=your_api_key_here
    DB_HOST=your_db_host
    DB_NAME=your_db_name
    DB_USERNAME=your_db_username
    DB_PASSWORD=your_db_password
    ```

4. **Database Setup:**

    Execute the SQL script provided in `database.sql` to create the necessary tables.

## Usage

### Available Endpoints

#### List Broths

- **Endpoint:** `/broths`
- **Method:** `GET`
- **Headers:**
  - `x-api-key: your_api_key`
- **Response:**

    ```json
    [
        {
            "id": "int",
            "imageInactive": "string",
            "imageActive": "string",
            "name": "string",
            "description": "string",
            "price": "int",
        },
        ...
    ]
    ```

#### List Proteins

- **Endpoint:** `/proteins`
- **Method:** `GET`
- **Headers:**
  - `x-api-key: your_api_key`
- **Response:**

    ```json
    [
        {
            "id": "int",
            "imageInactive": "string",
            "imageActive": "string",
            "name": "string",
            "description": "string",
            "price": "int",
        },
        ...
    ]
    ```

#### Place an Order

- **Endpoint:** `/order`
- **Method:** `POST`
- **Headers:**
  - `x-api-key: your_api_key`
- **Request Body:**

    ```json
    {
        "brothId": "1",
        "proteinId": "1"
    }
    ```

- **Response:**

    ```json
    {
        "id": "orderId",
        "description": "string",
        "image": "string"
    }
    ```

## Tests

Unit tests are implemented using PHPUnit.

### Run Tests

1. **Install PHPUnit:**

    ```bash
    composer require --dev phpunit/phpunit
    ```

2. **Execute tests:**

    ```bash
    vendor/bin/phpunit --configuration phpunit.xml
    ```

## Improvements Implemented

- **Logging System:** Implemented with Monolog for better activity and error tracking.
- **Validation and Security:** Protection against SQL injection and XSS attacks.
- **Scalability:** Database configuration to support high volume of data.

## Contribution

1. Fork the project
2. Create a feature branch (`git checkout -b feature/new-feature`)
3. Commit your changes (`git commit -m 'Add new feature'`)
4. Push to the branch (`git push origin feature/new-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
