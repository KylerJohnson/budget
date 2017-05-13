Hello, and thank you for your interest in the budget repo!

This project is built on Laravel.  Specifically, 5.4.  That means you need to have at least what Laravel requires (php, apache, mysql etc).  

Assuming you have the required software installed, run through the following checklist to get your application up and running:

1. Run composer install
2. Run npm install
3. Run npm run dev
4. Copy the .env.example file to .env and update the relevant fields
5. Run php artisan key:generate
6. Make storage and bootstrap/cache writable by the webserver
7. Hook the application up to a database and run php artisan migrate
8. OPTIONAL run php artisan serve. This sets up the application at localhost:8000.

And you should be good to go with a minimal working setup.  Of course, you may wish to customize further to suit your needs.
