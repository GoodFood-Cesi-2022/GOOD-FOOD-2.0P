pipeline {
    agent any
    stages {
        stage("Build") {
            environment {
                DB_HOST = credentials("goodfood_database_host")
                DB_DATABASE = credentials("goodfood_database")
                DB_USERNAME = credentials("goodfood_database_user")
                DB_PASSWORD = credentials("goodfood_database_password")
            }
            steps {
                sh "php --version"
                sh "composer --version"
                sh "composer install"
                sh "cp .env.example .env"
                sh "echo DB_HOST=${DB_HOST} >> .env"
                sh "echo DB_DATABASE=${DB_DATABASE} >> .env"
                sh "echo DB_USERNAME=${DB_USERNAME} >> .env"
                sh "echo DB_PASSWORD=${DB_PASSWORD} >> .env"
                sh "php artisan key:generate"
                sh "cp .env .env.testing"
                sh "php artisan migrate"
            }
        }
        stage("Unit") {
            steps {
                sh "php artisan test"
            }
        }
        stage("Code Coverage") {
            steps {
                sh "vendor/bin/phpunit --coverage-html 'reports/coverage'"
            }
        }
    }
}