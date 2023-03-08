# Helpdesk
#### Steps to access project :
- Download folder  
- UnZip then use your local server.  
  * Warning : You must to use a localhost, redirect to folder "public" in absolute path. **Otherwise CSS, IMG, JS don't work.**   
- Copy ".env.example" to ".env" and modify with your settings  
- Open folder helpdesk in your IDE, open CLI to write command : composer install (need install composer)
- Generate an APP_KEY : php artisan key:generate
- Create migration table to Database : php artisan migrate
- Install npm : npm install
- Open project with web browser
- To compile CSS / JS, need use command : npm run dev
- Configure a mailer in .env
