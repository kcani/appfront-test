### Laravel Developer Test Task

You are provided with a small Laravel application that displays a list of products and individual product details. Additionally, the application includes an admin interface for editing products, or alternatively, products can be edited using a command-line command.

### Task Objectives
Your goal is to refactor the provided application, focusing on the following:

- **Code Refactoring:**
    - Improve the overall quality, readability, and maintainability of the code.
    - **Apply Laravel best practices, design patterns, and standards suitable for enterprise-level applications.**

- **Bug Fixing:**
    - Identify and fix any existing bugs.

- **Security Audit:**
    - Perform a thorough security review.
    - Implement necessary fixes and enhancements to secure the application.

- **Improvements:**
    - Implement any additional improvements that you consider beneficial (performance optimization, better code organization, etc.).

### Important Constraints
1. The visual appearance of the application in the browser must remain exactly the same.
2. The existing functionality must be preserved completely.
3. The structure of the database cannot be changed.

Your final submission should demonstrate your ability to write clean, secure, and maintainable code adhering to industry standards.

**Submission:**

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
12. For running the test, create a sqlite database file in: "database/database.sqlite". Then inside the container terminal run: "php artisan test"

### What is done in this task
1. Used Service Design Patter. The business login is implemented inside Service classes. The services will be injected in the controllers and their methods will be called in specific controller methods (or other places in the project like other service or commands).
2. Used the FormRequest classes to validate the request inputs. The validation logic is moved from the controllers to the FormRequest classes, where they are injected in the controller methods.
3. Add Facade for the exchange rate service. Since it implements an external callback, this logic is wrapped in a specific class and is added a Facade over it in order to mock it for testing purposes.
4. Used cache system. For the exchange rate API, is used caching in order to avoid sending requests that often to the API.
5. Use config to store environment variables. The variables that were directly used with "env" method, are moved in config files.
6. Refactored the routes, used the resource standard of Laravel.
7. Change the upload directory from public path to storage, and set up an endpoint to fetch the product image.
8. Dockerized the application. To run the application a docker environment is set up to include the laravel app, mysql and redis.
9. Implemented tests. All the endpoints are tested and checked by Featured tests.
10. Implemented the localization. All the text in the entire project are wrapped in translation and the text are moved in "lang" directory.
