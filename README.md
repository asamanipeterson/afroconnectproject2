# AfroConnect

## About AfroConnect

AfroConnect is a social platform designed to bridge the gap in representation and connectivity for Africans and the African diaspora. The platform celebrates African heritage, empowers creators, and fosters meaningful global connections through culturally relevant social experiences.

AfroConnect is built with a robust technical foundation using **Laravel 12** for the backend and **Bootstrap 5** for responsive frontend styling, alongside modern frontend tools to ensure a smooth and user‑friendly experience.

## Features

* User authentication (registration & login)
* Create posts and stories
* Like, save, and share posts
* Comment on other users’ posts
* User search by username or location (country)
* Live streaming functionality
* Marketplace for buying and selling goods (no payment integration yet)

## Requirements

Before running the project, ensure you have the following installed:

* PHP **8.4.7** or higher
* Composer
* MySQL
* XAMPP (or any local PHP server)

## Installation

Follow the steps below to set up the project locally:

1. Clone the repository

```bash
git clone https://github.com/asamanipeterson/afroconnectproject2.git
```

<!-- 2. Navigate into the project directory -->

```bash
cd afroconnectproject2
```

<!-- 3. Install PHP dependencies -->

```bash
composer install
<!-- ``` -->

<!-- 4. Environment setup -->

* Copy `.env.example` to `.env` (if `.env` does not exist)
* Configure your database credentials in the `.env` file
* Ensure the database connection is set to **MySQL**

5. Generate application key (if not already set)

```bash
php artisan key:generate
```

<!-- 6. Run database migrations -->

```bash
php artisan migrate
```

<!-- 7. Start the development server -->

```bash
php artisan serve
```

<!-- The application will be available at: -->

```bash
http://127.0.0.1:8000
```

## Technologies Used

* Laravel
* Bootstrap 5
* Tailwind CSS
* JavaScript
* MySQL

## Contributing

Thank you for considering contributing to AfroConnect. Contributions are welcome and appreciated.

## Code of Conduct

Please ensure respectful and inclusive behavior when contributing to this project.

## Security Vulnerabilities

If you discover a security vulnerability within this project, please report it responsibly so it can be addressed promptly.

## License

This project is open‑sourced software licensed under the **MIT license**.
