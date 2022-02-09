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
                sh "docker-compose exec -T api ls -l"
                sh "docker-compose exec -T api php --version"
                sh "docker-compose exec -T api composer --version"
                sh "docker-compose exec -T api composer install"
                sh "cp .env.example .env"
                sh "echo DB_HOST=${DB_HOST} >> .env"
                sh "echo DB_DATABASE=${DB_DATABASE} >> .env"
                sh "echo DB_USERNAME=${DB_USERNAME} >> .env"
                sh "echo DB_PASSWORD=${DB_PASSWORD} >> .env"
                sh "echo DB_PORT=${DB_PORT} >> .env"
                sh "echo TYPESENSE_API_KEY=${TYPESENSE_API_KEY} >> .env"
                sh "docker-compose exec -T api php artisan key:generate"
                sh "cp .env .env.testing"
                sh "docker-compose exec -T api php artisan migrate"
            }
        }
        stage("Unit") {
            steps {
                sh "docker-compose exec -T api php artisan test"
            }
        }
        stage("Code Coverage") {
            steps {
                sh "docker-compose exec -T api vendor/bin/phpunit --coverage-html 'reports/coverage'"
            }
        }
    }
    post {
        always {
            sh "docker-compose down --volumes --rmi all"
        }
    }
}