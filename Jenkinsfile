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
                sh 'sed -i s/"DB_HOST=[0-9a-zA-Z.]*/DB_HOST=${DB_HOST}/"g .env'
                sh 'sed -i s/"DB_DATABASE=[0-9a-zA-Z.]*/DB_DATABASE=${DB_DATABASE}/"g .env'
                sh 'sed -i s/"DB_USERNAME=[0-9a-zA-Z.]*/DB_USERNAME=${DB_USERNAME}/"g .env'
                sh 'sed -i s/"DB_PASSWORD=[0-9a-zA-Z.]*/DB_PASSWORD=${DB_PASSWORD}/"g .env'
                sh 'sed -i s/"DB_PORT=[0-9a-zA-Z.]*/DB_PORT=${DB_PORT}/"g .env'
                sh 'sed -i s/"TYPESENSE_API_KEY=[0-9a-zA-Z.]*/TYPESENSE_API_KEY=${TYPESENSE_API_KEY}/"g .env'
                sh "docker-compose exec -T api php artisan key:generate"
                sh "cp .env .env.testing"
                sh "docker-compose exec -T api php artisan migrate"
            }
        }
        stage("Unit") {
            steps {
                sh "docker-compose exec -T api php artisan test --without-tty"
            }
        }
        stage("Code Coverage") {
            steps {
                sh "docker-compose exec -T api /bin/bash -c 'export XDEBUG_MODE=coverage && vendor/bin/phpunit --coverage-html reports/coverage'"
                publishHTML([
                    allowMissing: false,
                    alwaysLinkToLastBuild: false,
                    keepAll: false,
                    reportDir: 'reports/coverage', 
                    reportFiles: 'index.html', 
                    reportName: 'HTML GOODFOOD API CODE COVERAGE', 
                    reportTitles: 'CODE COVERAGE'
                ])
            }
        }
        stage('SonarQube Analysis') {
            steps {
                withSonarQubeEnv('SonarQube') {
                    sh "./gradlew sonarqube"
                }
            }
        }
        stage('Quality Gate') {
            steps {
                waitForQualityGate abortPipeline: true
            }
        }
    }
    post {
        always {
            sh "docker-compose down --volumes"
        }
    }
}