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
- My database dump file link 
<https://drive.google.com/file/d/18b90_gJbqhnlTIH2K_9FQfkQeZ_vJ48Z/view?usp=sharing>
- Database diagram link 
<https://drive.google.com/file/d/1_-w_yLLa4CDMub8zJ5K5jTPLgGMPyjXF/view?usp=sharing>  
