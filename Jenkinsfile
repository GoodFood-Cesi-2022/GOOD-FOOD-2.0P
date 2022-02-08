pipeline {
    agent any
    stages {
        stage("Build") {
            environment {
                DB_HOST = credentials("goodfood_database_host")
                DB_DATABASE = credentials("goodfood_database")
                DB_USERNAME = credentials("goodfood_database_user")
                DB_PASSWORD = credentials("goodfood_database_password")
                TYPESENSE_API_KEY = credentials("typesense_api_key")
            }
            steps {
                sh "docker-compose build api db nginx cache search"
                sh "docker-compose up -d api db nginx cache search"
                sh "php --version"
                sh "composer --version"
                sh "composer install"
                sh "cp .env.example .env"
                sh "echo DB_HOST=${DB_HOST} >> .env"
                sh "echo DB_DATABASE=${DB_DATABASE} >> .env"
                sh "echo DB_USERNAME=${DB_USERNAME} >> .env"
                sh "echo DB_PASSWORD=${DB_PASSWORD} >> .env"
                sh "echo TYPESENSE_API_KEY=${TYPESENSE_API_KEY} >> .env"
                sh "php artisan key:generate"
                sh "cp .env .env.testing"
                sh "php artisan migrate"
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