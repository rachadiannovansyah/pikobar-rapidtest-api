pipeline {
    agent any

    environment {
        registryUrl = 'registry.digitalservice.id'
        registryImageTag = 'registry.digitalservice.id/pikobar-tesmasif/tesmasif-api'
        registryCredential = 'registry_jenkins'
    }

    stages {
        stage('build') {
            steps {
                script {
                    docker.build registryUrl + ':$GIT_COMMIT'
                }
            }
        }

        stage('deploy') {
            steps {
                script {
                    docker.withRegistry(registryUrl, registryCredential) {
                        dockerImage.push()
                    }
                }
            }
        }

        stage('cleanup') {
            steps {
                sh "docker rmi $registry:$GIT_COMMIT"
            }
        }
    }
}
