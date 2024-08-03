
## About This Repo
this repo is example of crud with minimal style. It uses latest Laravel 11 and InertiaJS and Vue3

## What to do to make it works
<ul>
    <li>Clone this repo</li>
    <li>Create Empty MySQL database</li>
    <li>Fill .env file with your database credential</li>
    <li>Open Terminal / CMD / Powershell / Bash</li>
    <li>Run: <i>composer i</i></li>
    <li>Run: <i>npm i</i></li>
    <li>Run: <i>php artisan migrate</i></li>
    <li>Run: <i>php artisan db:seed</i></li>
</ul>
Run this additional for dummy data (you can skip this step):
<ul>
    <li>Run: <i>php artisan php artisan db:seed --class=LoginHistorySeeder</i></li>
    <li>Run: <i>php artisan db:seed --class=EmployeeSeeder</i></li>
</ul>

Now Run the app!
 <ul>
    <li>Run: <i>php artisan serve</i></li>
    <li>Run: <i>npm run dev</i></li>
</ul>

Open your app on your favorite browser http://localhost:8080/login
Log in with username / password: test@example.com / 123456
