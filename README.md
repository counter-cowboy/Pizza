
Go to folder  
#### cd Pizza

Start application:
#### make up
(or  
chmod 644 ./docker/volume/init.sql  
mv /src/.env.example /src/.env  
docker compose up -d  
)

After 3-5 minutes. Migrating both DBs and seeding them.
#### make db

Testing (after about 3 min)  
#### make test
