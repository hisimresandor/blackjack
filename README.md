# <p align="center">Blackjack</p>


## About the project

This is a simple blackjack game made with Laravel. The game is played against the computer. The project has a simple design and it is responsive.

Some of the features of the game:
- The player can place a bet before the game starts.
- The player can hit or end the game.
- The player is able to deposit or withdraw money from the account.
- The history of the games as well as the transactions is saved in the database.

## Used packages

- [Laravel](https://laravel.com/)
- [Laravel Breeze](https://laravel.com/docs/8.x/starter-kits#laravel-breeze)
- [Inertia](https://inertiajs.com/)
- [Tailwind CSS](https://tailwindcss.com/)
- [Heroicons](https://heroicons.com/)
- [Vuetify](https://vuetifyjs.com/en/)

## Installation

1. **Clone the repository** to your local machine.
2. **Navigate** to the project folder.
3. **Install the dependencies**:
    ```bash
    composer install
    ```
4. **Copy the `.env.example` file to `.env`** and configure your environment variables:
    ```bash
    cp .env.example .env
    ```
5. **Start and build the docker containers**:
    ```bash
    sail up -d --build
    ```
6. **Generate an application key**:
    ```bash
    sail artisan key:generate
    ```
7. **Run the migrations and seed the database**:
    ```bash
    sail artisan migrate --seed
    ```
    A user is created with the following credentials:
   - Email: `user@mail.com`
   - Password: `password`
8. **Serve the application**:
    ```bash
   sail up -d
    ```
9. **Install the npm dependencies**:
    ```bash
    sail npm i
    ```
10. **Run the NPM dependencies**:
    ```bash
    sail npm run dev
    ```
    
**You can now access the application** at [http://localhost](http://localhost).

## Running tests

This project uses PHPUnit and Cypress for testing. To run the tests, you can use the following commands:

### PHPUnit
```bash
sail artisan test
```

### Cypress
To run the Cypress tests, you need to have the application running. You can start the Cypress tests with the following command:
```bash
npx cypress open
```
