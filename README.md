# tasks

### install

- composer install
- cp .env .env.local 
- docker-compose build `- in __docker__ directory `
- docker-compose up    `- in __docker__ directory`
- php bin/console doctrine:database:create `- create DB in docker docker container, Like:` 
    __docker exec  docker_php_1 bin/console doctrine:database:create__
- bin/console doctrine:migrations:migrate `- run  migrates in docker docker container to`
- go to http://localhost:8091/ 
