# mongodb-multi-tenancy
Laravel Multi Tenancy for MongoDB

#Install

```
composer require codexshaper/mongodb-multi-tenancy
```

#Publish Configs and Migrations

```
php artisan vendor:publish --tag=tenancy
```

#Tenant Commands

```
tenant:create {website} {hostname}
tenant:delete {host}
tenant:migrate
tenant:migrate:refresh
```
Example: ```php artisan tenant:create dev dev.codexshaper.com```
