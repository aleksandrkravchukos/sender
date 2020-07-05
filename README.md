# Docker / PHP 7.4 console / composer / phpunit 

Task.

*The company has a database of customers who need to send email messages throughout the day. Each client is in its own time zone. Each message has a sending schedule (e.g. 12:00, 13:02). Each message can be sent an unlimited number of times throughout the day and are not tied to customers.

*You need to write a console command that will send emails to clients at the right time in the client’s time zone, provided that the application server is running on UTC + 02: 00. Keep messages, schedules and customers need in the database. Create models, migrations, siders.

*When developing, consider the size of the message base and clients.
*Customers in the database - 1 million.
*Messages - 10 thousand

*Requirements: use the Laravel framework.

*Inform the time of the task.

## Prerequisites

Install Docker.

## Clone the repo

     

## Build docker containers

     docker-compose build 

## Up containers

     docker-compose up -d
     
## Execute migrations for real database

     docker exec -it app_task php artisan migrate 

## Execute migrations for tests database

     docker exec -it app_task php artisan migrate --env=testing
     
## Add random 100 messages

     docker exec -it app_task php artisan db:seed --class=MessageSeeder
     
## Add random 100 message time

     docker exec -it app_task php artisan db:seed --class=MessageTimeSeeder
     
## Add existing message time in all time zones

     docker exec -it app_task php artisan db:seed --class=MessageTimeInTimeZoneSeeder
     
## Add random clients with random time zones 

     docker exec -it app_task php artisan db:seed --class=ClientSeeder
     
## Add messages in current time to queue

     docker exec -it app_task php artisan send 

## Start queue
 
     docker exec -it app_task php artisan queue:work
     
## Run tests

     docker exec -it app_task php artisan test
     
     
