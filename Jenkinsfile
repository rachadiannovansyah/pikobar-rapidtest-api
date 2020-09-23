pipeline {
    agent any

    environment {
        registryUrl = 'https://registry.digitalservice.id'
        registryBaseImageTag = 'registry.digitalservice.id/pikobar-tesmasif/tesmasif-api'
        registryImage = ''
        registryCredential = 'registry_jenkins'
        SHORT_COMMIT = "${GIT_COMMIT[0..7]}"
    }

    stages {
        stage('build') {
            steps {
                script {
                    registryImage = docker.build registryBaseImageTag + ':$SHORT_COMMIT'
                }
            }
        }

        stage('deploy') {
            steps {
                script {
                    docker.withRegistry(registryUrl, registryCredential) {
                        registryImage.push()
                    }
                }
            }
        }

        stage('cleanup') {
            steps {
                sh "docker rmi $registryImage"
            }
        }
    }
}
