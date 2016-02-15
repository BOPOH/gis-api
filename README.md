# GIS-api. Test app

Small exmample of the rest-api app.

### Version
1.0.0

### Installation

Needs:
    - ubuntu 14.04
    - php >=5.5
    - mysql >= 5.5
    - apache2 (see site config example in `install/configs/apache-site.conf`)
    - apache rewrite module (`a2enmod rewrite`)

Install [composer](https://getcomposer.org/download/):

```sh
$ curl -s http://getcomposer.org/installer | php
```

Install sources:
```sh
$ php composer.phar install
```

Sets up service config (`config/config.php`)

Install [SphinxSearch](http://sphinxsearch.com/docs/current.html#installing-debian):
```sh
$ sudo apt-get install mysql-client unixodbc libpq5
$ sudo apt-get install python-software-properties
$ sudo add-apt-repository ppa:builds/sphinxsearch-rel22
$ sudo apt-get update
$ sudo apt-get install sphinxsearch
```

Create sphinx config (see examples in the `install` dir)

Create db:
```sh
$ cd install
$ mysql-hHOST -uUSER -p DATABASE < db.sql
```

Move data to db. For example:
```sh
$ cd install/data
$ mysql-hHOST -uUSER -p DATABASE < data.sql
```
or

```sh
$ cd web
$ php console.php test --company-count 50 --address-count 30 --rubric-count 40
```

Rebuild sphinxsearch index:

```sh
$ indexer --rotate --all
```

Install [swagger-ui](http://swagger.io/swagger-ui/):
```sh
$ mkdir swagger
$ cd swagger
$ wget https://github.com/swagger-api/swagger-ui/archive/master.zip
$ unzip master.zip
$ mv swagger-ui-master/dist/* ../docs
$ cd ../docs
$ sed -i 's/url = "http:\/\/petstore.swagger.io\/v2\/swagger.json"/url = "HOSTNAME\/gis-api/docs/swagger.yaml"/g' index.html
$ sed -i 's/host: "127.0.0.1\/gis-api"/host: "HOSTNAME\/gis-api"/g' swagger.yaml
```

Copy docs to public folder (for example, http://hostname/gis-api/docs) and try API examples

License
----

MIT
