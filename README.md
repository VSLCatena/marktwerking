# Marktwerking

[![Docker](https://github.com/VSLCatena/marktwerking/actions/workflows/main.yml/badge.svg)](https://github.com/VSLCatena/marktwerking/actions/workflows/main.yml)


The forces of a market during one evening? It is possible! Include your drinks and boundaries and enjoy the evening. The prices will fluctuate based on the sellings.

This is a package that is fully web-based. So no special SQL-knowledgde is needed. It contains two view settings:
 - The insert-screen, based on POS
 - The view-screen, based on financial economical news

In the insert-screen, a special settings overlay is available to adjust the program to your needs. Also a financial summary is available to see your gains and losses. 

Languages used:
- PHP, SQL, JS
Modules used: 
- jquery, angular, json, ajax, bootstrap (Modal/Tabs) 



# Changelog
- Initial commit
- 2023-07-31 - Kipjr - Update Dockerfile & file structure. Automatic provision default data. Use volumes and no bind mounts


# Installation 

## Dependencies
- docker host


## Steps

1. ```git clone https://github.com/vslcatena/marktwerking``` 

2. Fill in your variables in docker-compose.yml and check with ```docker compose config```
   - Dev: set build arg to development and use ./src/html as volume
   - Prod: use image from GitHub and comment volumes

3. ```docker-compose up  [--build] ```

4. Wait some minutes , grab some coffee

5. Visit website on http://{docker-host}:4080

