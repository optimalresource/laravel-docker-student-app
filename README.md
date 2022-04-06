# Laravel API Endpoints For Books, Characters and Comments

This is a project where students can be onboarded, find courses, subscribe to a course, start and complete the courses. It is just a simple demonstration of CRUD operations. The application in the end will be wrapped in a docker container and hosted in the cloud.

The following technologies were used; - API endpoints are built using Laravel. - Database is powered by MySQL. - NGINX serves the application. - Docker is used as a container service to house the app. - AWS ECR was used to host the docker image. - AWS EC2 instance was used to host the docker application on the cloud.

To launch the docker service - Edit the sample.docker-compose.yml file and put the MySQL configuration of your choice. - Go to /web root and add a .env file with the primary Lumen environment configuration and change the MySQL details to match the one in the docker-compose file you configured earlier. - Go to /conf root and create an nginx.conf file and copy the sample.nginx.conf details into your new file, add more configurations as necessary for your server, such as reverse proxy, ssl and so on. - Open terminal and cd to the root of this project, then run "docker-compose up" to see the way the application images are pulled and server started. Check out for possible errors. Subsequently, simply run "docker-compose up --detach" so you can continue running scripts on the same terminal. - To stop the service, run "docker-compose stop" - To restart the already created service, run "docker-compose start"

Once the app service is running, you can access the application on port 80, adjust the port as it suits you in the docker-compose file.

To access the files of the NGINX service, run "docker exec -it nginx bash"
To access the files of the application service, run "docker exec -it php bash"
To run MySQL queries on the terminal, run "docker exec -it mysql bash"

To check a running instance of this project, vist my instance here and test the endpoints:
http://ec2-3-93-60-154.compute-1.amazonaws.com/documentation.html

Thank you.
