
Go to folder  
#### cd Pizza
Rename .env.example to .env

Start application
#### make up
(or  
chmod 644 ./docker/volume/init.sql  
mv .env.example .env  
docker compose up -d  
)

After 3-5 minutes. Migrating both DBs and seeding them.
#### make db

Testing
#### make test
