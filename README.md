# Welcome to Demo API!

## Steps to start project:
- Pull project to new directory
- Using terminal navigate to **environment** directory
- Run `make build`
- Add `CONTAINER_DOMAIN_WEB` setting from **environment/.env** to your hosts

## How to run tests?
Just run `make test`

## What about docs?
You can find `CONTAINER_DOMAIN_WEB` setting in **environment/.env**
Change it, if needed, before running `make build`, then go to `api` endpoint, using your browser,
i.e. [demo-api.local/api](http://demo-api.local/api) by default

## What about credentials?
As `make build` command loads fixtures, you can simply use **API_TOKEN** as **X-API-TOKEN**

## RabbitMQ
Use the next command to collecting and displaying messages in your terminal:
`docker exec -it php-fpm bin/console messenger:consume async -vv`

You can access [RabbitMQ dashboard](http://localhost:15672), use `guest` as username/password

## Notifications

You can check notifications via [mailhog](http://localhost:8025) 

## Check
