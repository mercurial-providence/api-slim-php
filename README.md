# api-slim-php

A SLIM Framework API capable to handling request and process accordingly.

## ER DIAGRAM OF DATABASE

The SQL file for this database can be found enclosed within.

## ROUTES
I have implemented following 	**ROUTES** to this API.

> public/index.php/api/info/author/1

|route|purpose|
|--|--|
|api/info/author			|Displays all the AUTHORS and their attributes  |
|api/info/author/{$id}		|Displays all the attributes for AUTHOR with id {$id}  |
|api/info/author/{$char}    |Displays all the AUTHOR with names starting with {$char}  |
|api/info/form				|Displays all the FORM and their attributes  |
|api/info/form/{$id}		|Displays all the attributes for FORM with id {$id}    	|
|api/info/location			|Displays all the LOCATION and their attributes  |
|api/info/location/{$id}	|Displays all the attributes for LOCATION with id {$id}    |
|api/info/school			|Displays all the SCHOOL and their attributes  |
|api/info/school/{$id}		|Displays all the attributes for SCHOOL with id {$id}    |
|api/info/timeframe			|Displays all the TIMEFRAME and their attributes  |
|api/info/timeframe/{$id}	|Displays all the attributes for TIMEFRAME with id {$id}    |
|api/info/type				|Displays all the TYPE and their attributes  |
|api/info/type/{$id}		|Displays all the attributes for TYPE with id {$id}    |
|--|--  |
|api/art/all				|Displays all the attributes from ARTDATA   |
|api/art/all/{$id}			|Displays all the attributes from ARTDATA with ART_ID {$id}  |
|api/art/author/{$id}		|Displays all the attributes from ARTDATA with AUTHOR_ID {$id} |
|api/art/form/{$id}			|Displays all the attributes from ARTDATA with FORM_ID {$id}    	|
|api/art/location/{$id}		|Displays all the attributes from ARTDATA with LOCATION_ID {$id}  |
|api/art/school/{$id}		|Displays all the attributes from ARTDATA with SCHOOL_ID {$id}   |
|api/art/timeframe/{$id}	|Displays all the attributes from ARTDATA with TIMEFRAME_ID {$id}  |
|api/art/type/{$id}			|Displays all the attributes from ARTDATA with TYPE_ID {$id}    |
|--|--  |
|api/search?q={$string}     |Searches the ARDATA for ($string) and displays all attributes  |
|--|--  |
|api/random                 |Returns a random image  |
|/api/filter?au=1&fo=1&lo=1&sc=1&ti=1&ty=1              |Takes ID and filters  and returns list of ARTS |

|/api/detailinfo            |Displays all the AUTHORS and their attributes with additional information|

>PUT REQUEST

|/api/log                   |Logs data to server, takes following param, {category: '', value: ''}  |



## FEATURES

This is a well **PAGINATED** API.
 
## DIRECTORY TREE
 
Following is the directory **TREE**

    .
    ├── composer.json
    ├── composer.lock
    ├── api-slim-php-database.tar.gz
    ├── public
    │   └── index.php
    ├── README.md
    ├── src
    │   ├── config
    │   │   └── db.php
    │   └── routes
    │       ├── art.php
    │       ├── boilerplate.php.bkp
    │       ├── info.php
    │       └── search.php
    └── vendor

## PREREQUISITE

You might want to install `composer` first.
Navigate to your project directory.

> sudo apt install composer

> composer install