# DEBs-to-SQL
SQL Database of debian packages and its contents indexer

![Project Logo](debs2sql.svg?raw=true)


Configuration
-------------


edit /etc/debs2sql/.env :

```
APP_DEBUG=true
EASE_LOGGER=syslog|console  see  
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=debs2sql
DB_USERNAME=debs2sql
DB_PASSWORD=debs2sql
EMAIL_FROM=debs2sql@localhost
SEND_INFO_TO=admin@localhost
REPO_DIR=/home/vitex/WWW/repo.vitexsoftware.cz/
```

See https://github.com/VitexSoftware/php-ease-core for EASE_LOGGER options
See https://github.com/VitexSoftware/php-ease-fluentpdo for DB_CONNECTION options

and run debs2sql-phinx to prepare database structure


Usage
-----


simply run 

```shell
debs2sql
```

to index all new packages into database


![Results](result.png?raw=true)
