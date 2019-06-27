Witsync Lease Management Deployment

Before doing the deployment please make sure that you should have a database user with the name `witsyncf_flexsin` with all the privileges provided to the user.

This is because we have used this user inside the sql files which we will import to the database, as in the above screenshot :


As in the above image we have used `witsyncf_flexsin`@`localhost` as the Definer.

Hence, we will need to have this database user created on the local and the server to which we will upload the database as well.

Once the mysql user has been created , follow the below steps to set up the project compeletly :

STEP 1 : Clone the code from github (details will be provided by the Project Manager).
STEP 2 : `run composer update`
STEP 3 : Make the changes to the .env file for the database credentials and make sure that you will use the same user that we have created above.
STEP 4 : Create the database on mysql and make sure that you used the same database name on the .env file as well.
STEP 5 : Run the command `php artisan migrate`, this will create all the database tables, views and procedures as well.
STEP 6 : Run the command `php artisan db:seed` , this will truncate all the tables that we will have currently on the database and will insert the data to the tables like countries and other master tables as well.
STEP 7 : Next we need to have the Clamav installed on the server as well, as we have used this anti-virus to scan the files that will be uploaded on the server at run-time. For the documentation on the same please refer to the link : 

https://github.com/sunspikes/clamav-validator

We have used the above package for the clamav validations.

STEP 8 : Next, we also need to start the laravel email queue and for the same we have used supervisor, please refer to the laravel documentation at the below link for the same  :

https://laravel.com/docs/5.8/queues#supervisor-configuration

We have used the supervisor to keep the laravel email queue up and running. The first thing that we need to do is to install the supervisor on the server, once the supervisor have been installed we need to create a configuration file , For the same we have to create a file on the server at the location :

/etc/supervisor/conf.d/
On the demo server we have created the file with the name witsync-email-queue.conf however you can name the to any of the name.

The contents of the file are :

[program:laravel-email-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/witsync-lease-management/artisan queue:work
autostart=true
autorestart=true
user=mobilewallet
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/html/witsync-lease-management/storage/logs/email-queue.log

Once we have created the file and saved the same.  We need to run the below commands :

sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start all

This will complete the setup of the project and we do not need to import the database manually. Complete project can be deployed on any server by the above steps.

Now run `php artisan serve` to run the project on your local machine.