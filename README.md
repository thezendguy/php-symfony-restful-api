# php-symfony-restful-api
A basic and lean recipe for implementing a RESTful API in symfony3..

This application will connect to the local mysql server, create a new database
called <strong><code>psra</code></strong> and a new table inside this called
<strong><code>product</code></strong>, which will be used for testing purposes.

The API is hardened against both client- and server-side errors, and will always
return the appropriate headers and response (if the latter is applicable).

Dependencies
-
git  
php v5.5.9  
composer  
MySQL

To install
-
```
$ mkdir -vp php-symfony-restful-api  
$ cd php-symfony-restful-api  
$ git clone https://github.com/benjaminvickers/php-symfony-restful-api.git .  
$ composer install
```
Enter the database connection details when prompted by composer. Otherwise you
can edit the details in the app/config/parameters.yml file. Remember the database
name should be set to <strong><code>psra</code></strong>.

To setup
-
```
$ sudo service mysql start
$ php bin/console doctrine:database:create  
$ php bin/console doctrine:schema:update --force
```

To run in dev mode
-
```
$ php bin/console server:run
```

To test using cURL
-
Experiment loading values:
```
$ curl -X POST -H "accept: application/json" --data '{"name":"product1", "price":"299", "description":"desc1"}' http://localhost:80/api/v1/products/  
$ curl -X POST -H "accept: application/json" --data '{"name":"product2", "price":"399", "description":"desc2"}' http://localhost:80/api/v1/products/  
$ curl -X POST -H "accept: application/json" --data '{"name":"product3", "price":"499", "description":"desc3"}' http://localhost:80/api/v1/products/  
```

Experiment getting values:
```
$ curl -X GET -H "accept: application/json" http://localhost:80/api/v1/products/  
$ curl -X GET -H "accept: application/json" http://localhost:80/api/v1/products/1  
$ curl -X GET -H "accept: application/json" http://localhost:80/api/v1/products/10  
$ curl -X GET -H http://localhost:80/api/v1/products/2
```

Experiment updating values:
```
$ curl -X PUT -H "accept: application/json" --data '{"price":"1299"}' http://localhost:80/api/v1/products/1  
$ curl -X PUT -H "accept: application/json" http://localhost:80/api/v1/products/2  
$ curl -X PUT --data '{"price":"3499"}' http://localhost:80/api/v1/products/3  
$ curl -X PUT -H "accept: application/json" --data '{"invalid":"data"}' http://localhost:80/api/v1/products/4
```

Experiment deleting values:
```
$ curl -X DELETE -H "accept: application/json" http://localhost:80/api/v1/products/1  
$ curl -X DELETE http://localhost:80/api/v1/products/2  
$ curl -X DELETE -H "accept: application/json" http://localhost:80/api/v1/products/10
```
