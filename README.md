# Book Rental Application

A modern web application for managing a book rental system, built with Laravel and Vue.js. This single-page application (SPA) allows users to manage books and authors and track the borrowed status of books.

## Features

- **Book Management**
  - Create, view, edit, and delete books
  - Filter books by title, author, and borrowed status
  - Toggle borrowed status of books
  - Real-time UI updates when changing book status

- **Author Management**
  - Create, view, edit, and delete authors
  - View book count for each author
  - Browse books by author
  - Prevent deletion of authors with associated books

## Technology Stack

- **Backend**: 
  - Laravel (PHP framework)
  - MySQL database
  - RESTful API architecture

- **Frontend**: 
  - Vue.js 3 (JavaScript framework)
  - Vue Router (for client-side routing)
  - Pinia (for state management)
  - Bootstrap 5 (for styling)
  - Axios (for API requests)

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

6. Start the Laravel development server:
   ```bash
   php artisan serve
   ```

7. In a separate terminal, start the Vite development server for the Vue.js frontend:
   ```bash
   npm run dev
   ```

8. Access the application at http://localhost:8000

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
   - Start the Laravel server on http://localhost:9876
   - Start the Vite development server for the Vue.js frontend

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

5. Access the application at http://localhost:9876

## Usage

The application is a Single Page Application (SPA) with client-side routing. Once loaded, navigation between pages happens without full page reloads.

1. **Home Page**
   - The landing page provides quick access to both Books and Authors management

2. **Managing Books**
   - Click on "Books" in the navigation bar to view all books
   - Use the filter form to search by title, author, or borrowed status
   - Click on a book title or the "View" button to see details
   - Use the "Add New Book" button to create a new book
   - Use the "Edit" and "Delete" buttons to modify or remove books
   - Toggle the borrowed status using the "Available/Borrowed" button (changes color based on status)

3. **Managing Authors**
   - Click on "Authors" in the navigation bar to view all authors
   - Each author shows their book count
   - Click on an author name or the "View" button to see details and their books
   - Use the "Add New Author" button to create a new author
   - Use the "Edit" and "Delete" buttons to modify or remove authors
   - Authors with associated books cannot be deleted (system will show an error message)

4. **API Endpoints**
   - The application provides a RESTful API at `/api`:
     - `/api/books` - CRUD operations for books
     - `/api/books/{id}/toggle-borrowed` - Toggle borrowed status
     - `/api/authors` - CRUD operations for authors

## Development

### Frontend Development

The Vue.js frontend is built with Vite. To make changes to the frontend:

1. Modify the components in `resources/js/components/`
2. Run `npm run dev` to see changes in real-time
3. Run `npm run build` for production builds

### Backend Development

The Laravel backend follows a service-repository pattern:

- Controllers: `app/Http/Controllers/Api/`
- Services: `app/Services/`
- Repositories: `app/Repositories/`
- Models: `app/Models/`

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
