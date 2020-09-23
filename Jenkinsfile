pipeline {
    agent any

    environment {
        registryUrl = 'https://registry.digitalservice.id'
        registryBaseImageTag = 'registry.digitalservice.id/pikobar-tesmasif/tesmasif-api'
        registryCredential = 'registry_jenkins'
        SHORT_COMMIT = "${GIT_COMMIT[0..7]}"
    }

    stages {
        stage('build') {
            steps {
                script {
                    docker.build registryBaseImageTag + ':$SHORT_COMMIT'
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
                sh "docker rmi $registryBaseImageTag:$SHORT_COMMIT"
            }
        }
    }
}
