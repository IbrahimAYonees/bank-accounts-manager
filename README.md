## Setup Guide

- `git clone`
- `composer install`
- `npm install`
- `cp .env.example .env`
- Add your db parameters to .env
- `php artisan key:gen`
- `php artisan jwt:secret`
- `php artisan migrate --seed`
-  Run `php artisan db:seed --class=TestingDataSeeder` for some fake data
- `npm run dev` Or `npm run prod` for production ready assets
- Use `ibrahim21383@gmail.com` and password of `123456` to login  
