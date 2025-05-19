# Book Rental Application

A Laravel-based web application for managing a book rental system. This application allows users to manage books and authors and track the borrowed status of books.

## Features

- **Book Management**
  - Create, view, edit, and delete books
  - Filter books by title, author, and borrowed status
  - Toggle borrowed status of books

- **Author Management**
  - Create, view, edit, and delete authors
  - Filter authors by name and surname
  - View book count for each author
  - Prevent deletion of authors with associated books

## Technology Stack

- **Framework**: Laravel
- **Database**: MySQL
- **Frontend**: Blade templates, Bootstrap

## Installation

### Option 1: Standard Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd book-rental-app
   ```

2. Install dependencies:
   ```bash
   composer install
   npm install
   ```

3. Set up environment variables:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Configure your database in the `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=book_rental
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. Run migrations:
   ```bash
   php artisan migrate
   ```

6. Compile assets:
   ```bash
   npm run dev
   ```

7. Start the development server:
   ```bash
   php artisan serve
   ```

### Option 2: Installation with devenv.nix

If you have [Nix](https://devenv.sh/getting-started/) installed, you can use devenv.nix for a reproducible development environment:

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd book-rental-app
   ```

2. Start the development environment:
   ```bash
   devenv up
   ```
   This will automatically:
   - Set up PHP 8.2 with all required extensions
   - Install MariaDB and create a database
   - Install Node.js 20
   - Run `composer install` and `npm install`
   - Start a development server on http://localhost:9876

3. Configure your database in the `.env` file to use the devenv database:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=devenv
   DB_USERNAME=devenv
   DB_PASSWORD=
   ```

4. Run migrations:
   ```bash
   devenv shell
   php artisan migrate
   ```

5. Compile assets:
   ```bash
   devenv shell
   npm run dev
   ```

Note: The development server is automatically started at http://localhost:9876

## Usage

1. **Managing Books**
   - Navigate to `/books` to view all books
   - Use the filter options to search for specific books
   - Click on a book to view details
   - Use the "Add Book" button to create a new book
   - Use the "Edit" and "Delete" buttons to modify or remove books
   - Toggle the borrowed status using the dedicated button

2. **Managing Authors**
   - Navigate to `/authors` to view all authors
   - Use the filter options to search for specific authors
   - Click on an author to view details
   - Use the "Add Author" button to create a new author
   - Use the "Edit" and "Delete" buttons to modify or remove authors

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
