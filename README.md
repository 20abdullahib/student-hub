# Student Hub Magic Team

Welcome to the Student Hub Magic Team project! This repository contains the source code for a web application built primarily with CSS, PHP, Blade, and JavaScript.

## Table of Contents

- [Description](#description)
- [Installation](#installation)
- [Setup Environment](#setup-environment)
- [Usage](#usage)
- [Contributing](#contributing)
- [License](#license)

## Description

The Student Hub Magic Team project is a web application designed to provide a platform for students to collaborate, share resources, and manage their academic activities. The application is built with a focus on user experience, performance, and scalability.

## Installation

To get started with the project, follow these steps to set up your local development environment.

### Prerequisites

Make sure you have the following software installed on your system:

- PHP (>= 7.4)
- Composer
- Node.js (>= 14.x)
- npm (or yarn)
- Git

### Steps

1. **Clone the repository**

```bash
git clone https://github.com/20abdullahib/student-hub-magic-team.git
cd student-hub-magic-team
```

2. **Install PHP dependencies**

```bash
composer install
```

3. **Install JavaScript dependencies**

```bash
npm install
```

4. **Set up the environment file**

Copy the `.env.example` file to `.env` and update the necessary environment variables.

```bash
cp .env.example .env
```

5. **Generate the application key**

```bash
php artisan key:generate
```

6. **Run database migrations**

Ensure you have your database configured in the `.env` file, then run:

```bash
php artisan migrate
```

## Setup Environment

To set up the environment for the project, follow these instructions:

1. **Database Configuration**

   Update the following variables in your `.env` file with your database credentials:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_username
   DB_PASSWORD=your_database_password
   ```

2. **Mail Configuration**

   Update the following variables in your `.env` file with your mail server credentials:

   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your_mail_username
   MAIL_PASSWORD=your_mail_password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=hello@example.com
   MAIL_FROM_NAME="${APP_NAME}"
   ```

3. **Run the Application**

   Start the development server:

   ```bash
   php artisan serve
   ```

   You can now access the application at `http://localhost:8000`.

## Usage

After setting up the environment and running the application, you can start using the web application to manage student activities, collaborate on projects, and share resources.

## Contributing

Contributions are welcome! Please read our [contributing guidelines](CONTRIBUTING.md) before submitting a pull request.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more information.