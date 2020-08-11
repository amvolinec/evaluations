## Evaluation project

`git clone https://github.com/amvolinec/evaluations.git projectName`

`cd projectName`

`git checkout tags/start -b tutorial`

`composer install`

`yarn` or `npm install`

`cp .env.example .env`

`php artisan key:generate`

Create an empty database for our application

In the .env file fill in the 

    DB_HOST, 

    DB_PORT, 

    DB_DATABASE, 

    DB_USERNAME, and 

    DB_PASSWORD 

options to match the credentials of the database you just created. This will allow us to run migrations and seed the database in the next step.

`php artisan migrate`
`npm run dev`


