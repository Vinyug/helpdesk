# Helpdesk
#### Steps to access project :
- Download folder  
- UnZip then use your local server.  
  * Warning : You must to use a localhost, redirect to folder "public" in absolute path. **Otherwise CSS, IMG, JS don't work.**   
- Copy ".env.example" to ".env" and modify with your settings  
- Open folder helpdesk in your IDE, open CLI to write command : composer install (need install composer)
- Generate an APP_KEY : php artisan key:generate
- Create migration table to Database : php artisan migrate
- Open project with web browser
- Configure a mailer in .env
- Seed : 
    * php artisan db:seed --class=PermissionTableSeeder
    * php artisan db:seed --class=CreateAdminUserSeeder
- Storage : php artisan storage:link 

#### Login admin :
- email : admin@mail.com
- password : password
