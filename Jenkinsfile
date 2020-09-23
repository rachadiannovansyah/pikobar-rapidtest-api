pipeline {
    agent any

    environment {
        registry = "registry.digitalservice.id/pikobar-tesmasif/tesmasif-api"
        registryCredential = ‘registry_jenkins’
    }

    stages {
        stage('build') {
            steps {
                script {
                    docker.build registry + ":$BUILD_NUMBER"
                }
            }
        }

        stage('Deploy Image') {
          steps {
            script {
              docker.withRegistry('', registryCredential ) {
                dockerImage.push()
              }
            }
          }
        }
    }
}
