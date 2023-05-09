# tasks

### install

- composer install
- cp .env .env.local 
- cd docker && docker-compose build `- in docker directory `
- docker-compose up    `- in docker directory to`
- php bin/console doctrine:database:create `- create DB in docker container, Like:` 
    __docker exec  docker_php_1 bin/console doctrine:database:create__
- bin/console doctrine:migrations:migrate `- run  migrates in docker docker container to`
- go to http://localhost:8091/ 




#### for ADMIN, need add permission in DB _ROLE_ADMIN_
