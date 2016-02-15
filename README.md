# GIS-api. Test app

Small exmample of the rest-api app.

### Version
1.0.0

### Installation

Install [composer](https://getcomposer.org/download/):

```sh
$ curl -s http://getcomposer.org/installer | php
```

Install sources:
```sh
$ php composer.phar install
```

Install [SphinxSearch](http://sphinxsearch.com/docs/current.html#installing-debian):
```sh
$ sudo apt-get install mysql-client unixodbc libpq5
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
$ mv dist/* ../docs
$ cd ../docs
$ sed -i 's/url = "http:\/\/petstore.swagger.io\/v2\/swagger.json"/url = "swagger.yaml"/g' new.html
$ sed -i 's/host: "127.0.0.1\/gis-api"/host: "192.168.0.1\/gis-api"/g' new.html
```

Open docs (http://hostname/docs) and try API examples

License
----

MIT
