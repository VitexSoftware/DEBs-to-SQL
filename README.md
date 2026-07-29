# DEBs-to-SQL

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
![Packaging: deb](https://img.shields.io/badge/packaging-.deb-red?logo=debian&logoColor=white)

SQL Database of debian packages and its contents indexer

![Project Logo](debs2sql.svg?raw=true)


Installation
------------


You can use our repo:

```shell
sudo apt install lsb-release wget
echo "deb http://repo.vitexsoftware.cz $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/vitexsoftware.list
sudo wget -O /etc/apt/trusted.gpg.d/vitexsoftware.gpg http://repo.vitexsoftware.cz/keyring.gpg
sudo apt update
sudo apt install debs2sql
```	    


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


AppStream
---------

The package ships AppStream metadata (`io.github.vitexsoftware.debs2sql.metainfo.xml`)
and a scalable stock icon (`debs2sql`) installed to the hicolor icon theme:

```
/usr/share/icons/hicolor/scalable/apps/debs2sql.svg
/usr/share/metainfo/io.github.vitexsoftware.debs2sql.metainfo.xml
```
