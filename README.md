<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

#Instructions to install

## Clon repo
git clone https://alexandr-kravchuk@bitbucket.org/alexandr-kravchuk/elksearch.git
cd elksearch

## Start Docker

#Add project path to Docker file sharing on MacOs like on screen
https://prnt.sc/rwtbd3
Don't know how this will be on Windows or Linux


## docker-compose up -d --build

#Check running containers
docker ps 


## docker exec -it app composer install
## docker exec -it app cp env.example .env
## docker exec -it app php artisan key:generate
## docker exec -it app php artisan config:cache

## Check php 
http://localhost/php

Check elastic connection 
http://localhost:9200/

Create first elastic index
curl -X POST http://localhost:9200/customers/my_local_test_app -d '{"name": "Aleksandr Kravchuk","login": "aleksandr.kravchuk@ukr.net"}' -H 'Content-Type: application/json'

Check indeces
http://localhost:9200/_cat/indices?v&pretty

## run http://localhost and search your inserted values 


## /api/addDocument

curl -X POST \
  http://localhost/api/addDocument \
  -H 'content-type: application/json' \
  -d '{
 "name": "kravch",
 "login": "My login 2"
}'

