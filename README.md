<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<!-- make chmod -->
Go to folder  
#### cd Pizza
Rename .env.example to .env

Start application
#### make up
(or  
chmod 644 ./docker/volume/init.sql  
docker compose up -d  
)

After 3-5 minutes. Migrating both DBs and seeding them.
#### make db

Testing
#### make test
