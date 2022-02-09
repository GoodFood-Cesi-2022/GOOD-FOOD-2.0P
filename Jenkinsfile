pipeline {
    agent any
    stages {
        stage("Build") {
            environment {
                DB_HOST = credentials("goodfood_database_host")
                DB_DATABASE = credentials("goodfood_database")
                DB_USERNAME = credentials("goodfood_database_user")
                DB_PASSWORD = credentials("goodfood_database_password")
                DB_PORT = credentials("goodfood_database_port")
                TYPESENSE_API_KEY = credentials("typesense_api_key")
            }
            steps {
                sh "docker-compose build api db nginx cache search"
                sh "docker-compose up -d api db nginx cache search"
                sh "docker exec api php --version"
                sh "docker exec api composer --version"
                sh "docker exec api composer install"
                sh "docker exec api cp .env.example .env"
                sh "docker exec api echo DB_HOST=${DB_HOST} >> .env"
                sh "docker exec api echo DB_DATABASE=${DB_DATABASE} >> .env"
                sh "docker exec api echo DB_USERNAME=${DB_USERNAME} >> .env"
                sh "docker exec api echo DB_PASSWORD=${DB_PASSWORD} >> .env"
                sh "docker exec api echo DB_PORT=${DB_PORT} >> .env"
                sh "docker exec api echo TYPESENSE_API_KEY=${TYPESENSE_API_KEY} >> .env"
                sh "docker exec api php artisan key:generate"
                sh "docker exec api cp .env .env.testing"
                sh "docker exec api php artisan migrate"
            }
        }
        stage("Unit") {
            steps {
                sh "docker exec api php artisan test"
            }
        }
        stage("Code Coverage") {
            steps {
                sh "docker exec api vendor/bin/phpunit --coverage-html 'reports/coverage'"
            }
        }
        stage("Clean") {
            steps {
                sh "docker-compose down --volumes --rmi all"
            }
        }
    }
}