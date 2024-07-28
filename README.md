# BRIN BiodivInsight

This project is a web application for managing plant species information.

## Prerequisites

Before you begin, ensure you have met the following requirements:

- You have installed [Git](https://git-scm.com/).
- You have installed [Node.js](https://nodejs.org/) and [npm](https://www.npmjs.com/).
- You have installed [Composer](https://getcomposer.org/).
- You have installed [PHP](https://www.php.net/).
- You have a web server like [Apache](https://httpd.apache.org/) or [Nginx](https://www.nginx.com/).
- You have a database server like [MySQL](https://www.mysql.com/) or [PostgreSQL](https://www.postgresql.org/).

## Installation

Follow these steps to set up the project locally:

1. **Clone the repository:**

    ```sh
    git clone https://github.com/yourusername/yourrepo.git
    cd yourrepo
    ```

2. **Install PHP dependencies:**

    ```sh
    composer install
    ```

3. **Install JavaScript dependencies:**

    ```sh
    npm install
    ```

4. **Copy the `.env.example` file to `.env` and configure your environment variables:**

    ```sh
    cp .env.example .env
    ```

    Update the `.env` file with your database and other configuration details.

5. **Generate an application key:**

    ```sh
    php artisan key:generate
    ```

6. **Run database migrations:**

    ```sh
    php artisan migrate
    ```

7. **Seed the database (optional):**

    ```sh
    php artisan db:seed
    ```

8. **Compile the assets:**

    ```sh
    npm run dev
    ```

## Running the Application

1. **Start the local development server:**

    ```sh
    php artisan serve
    ```

2. **Open your browser and navigate to:**

    ```
    http://localhost:8000
    ```

## Usage

- To add a new plant, click on the "Add Plant" button.
- To edit a plant, click on the "Edit" button next to the plant entry.
- To delete a plant, click on the "Delete" button next to the plant entry.

## Contributing

To contribute to this project, follow these steps:

1. Fork the repository.
2. Create a new branch: `git checkout -b feature-branch-name`.
3. Make your changes and commit them: `git commit -m 'Add some feature'`.
4. Push to the branch: `git push origin feature-branch-name`.
5. Create a pull request.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contact

If you want to contact me, you can reach me at [your-email@example.com].
