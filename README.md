## Task and projects assessment

This project is a Project-task management system. It is created in two hours so it has only the minimal features

### Features
- Add/remove projects
- Add/remove tasks inside the projects
- Re-order the priority of tasks by dragging and dropping

### Improvements
The following improvements can be applied to the project in further development:
- Improvement the UI (especially the add form)
- Validation the inputs
- Encrypting the MySQL password

### Requiements
You need to have [Docker](https://www.docker.com/) and [Docker-compose](https://docs.docker.com/compose/) installed on your system.

### Deployment
1. Unzip the contents of the .zip file (or clone the repository if you came from Github)
2. Go to the application directory and run the following code in Terminal/Command prompt:
   ``docker-compose up -d``.
3. Copy ``.env.example`` file to ``.env`` and edit the file with following data:
    ```
   DB_HOST=db
   DB_PASSWORD=102030
   ```
4. Now you should initialize the Laravel application. Just copy and paste the following commands:
    ```
   docker-compose exec app composer update
   docker-compose exec app php artisan key:generate
   docker-compose exec app php artisan migrate
   ```
3. After everything is ready, you can open the project by navigating to ``https://localhost`` in your browser.
