### AppFront Test

### Start the application
1. Install docker and docker compose on your machine
2. On root directory of the project run: "docker compose build"
3. After building the imaged run the containers with: "docker compose up -d"
4. After the containers are up and running, log inside the laravel app container (Ex on Ubuntu: docker exec -it <container_name> /bin/bash)
5. Inside the container run: "composer install" to install the PHP dependencies
6. Then run: "npm install" to install the Node dependencies
7. Config the laravel cache: "php artisan config:cache"
8. Migrate the database: "php artisan migrate --seed"
9. Run the application on: http://localhost:8555
10. If you get a permission error check the permissions of the ./storage directory
11. To compile/watch the assets, in a container terminal run: "npm run build". If you want to listen to the assets change run: "npm run build -- --watch"
