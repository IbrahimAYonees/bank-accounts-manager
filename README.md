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
- My database dump file  
<https://drive.google.com/file/d/18b90_gJbqhnlTIH2K_9FQfkQeZ_vJ48Z/view?usp=sharing>
- Database diagram  
<https://drive.google.com/file/d/1_-w_yLLa4CDMub8zJ5K5jTPLgGMPyjXF/view?usp=sharing>  


## Technologies Used

- Build with Laravel Framework 7.0
- Api Authentication using Json Web Token JWT
- Front end Build with Vue Js Framework 2.6.11


## About Bank Account Manager

- A helper tool to save and track many bank accounts in one place
- You have the ability to create bank accounts list activate and deactivate them
- You have the ability to list existing accounts transactions deposits withdraw and transfers
- You have the ability to do any new transaction with one or more deposit or withdraw
- You have the ability make a transfer to any given bank account
- Transfer can be canceled during 36 hours
 
