# Project installation Guide

## Prerequisites
    - Docker installed
    - SSH key added to Gitlab account
    - `MAKE` installed (for Windows users, you can use `cmake`) 

## Installation
1. Clone the repository
    ```bash
    git clone git@gitlab.com:codalityco/byd-order-pilot.git
    ```
   
2. Run docker compose
    ```bash 
    cd %project_root%
    make build
    make up 
    ```
3. Copy  docker/.env.dist to /docker/.env
    ```bash
    cp docker/.env.dist /docker/.env
    ```
4. Run the composer
    ```bash
    make php
    composer install
    php bin/console app:create-printers
    ```
5. Run the migrations
    ```bash
    make db_migrate
    ```
6. Set key pair for JWT
    ```bash
    make php
    php bin/console lexik:jwt:generate-keypair

## Available commands
- `make build` - Build the docker containers
- `make restart` - Restart the docker containers
- `make up` - Start the docker containers
- `make down` - Stop the docker containers
- `make php` - Access the php container
- `make db_diff` - Generate the migrations
- `make db_migrate` - Run the migrations
- `make db_migration_down` - Down the migration
- `make db_drop` - Drop all the migrations
- `make cs_fix` - Fix the code style
