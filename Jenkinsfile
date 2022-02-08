pipeline {
    agent any
    stages {
        stage("Build") {
            environment {
                DB_HOST = credentials("db")
                DB_DATABASE = credentials("goodfood")
                DB_USERNAME = credentials("goodfood_user")
                DB_PASSWORD = credentials("goodfood_user")
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
        stage("Static Code Analysis") {
            steps {}
        }
        stage("Build Technical Doc - MkDocs") {
            steps {}
        }
        stage("Build Reference Doc - Doctum") {
            steps {}
        }
        stage("Build API Doc - OpenApi") {
            steps {}
        }
    }
}