pipeline {
    agent any

    environment {
        registryUrl = "https://registry.digitalservice.id"
        registryBaseImageTag = "registry.digitalservice.id/pikobar-tesmasif/tesmasif-api"
        registryImage = ""
        registryCredential = "registry_jenkins"
        CAPROVER_URL = "http://captain.rover.digitalservice.id"
        CAPROVER_PASSWORD = "caprover_admin"
        CAPROVER_APP = "tesmasif-api"
        SHORT_COMMIT = "${GIT_COMMIT[0..7]}"
    }

    stages {
        stage("build") {
            steps {
                script {
                    registryImage = docker.build registryBaseImageTag + ":$SHORT_COMMIT"
                }
            }
        }

        stage("deploy") {
            steps {
                script {
                    docker.withRegistry(registryUrl, registryCredential) {
                        registryImage.push()
                    }
                }

                sh "docker run caprover/cli-caprover:v2.1.1 caprover deploy --caproverUrl $CAPROVER_URL --caproverPassword $CAPROVER_PASSWORD --caproverApp $CAPROVER_APP --imageName $registryBaseImageTag:$SHORT_COMMIT"
            }
        }

        stage("cleanup") {
            steps {
                sh "docker rmi $registryBaseImageTag:$SHORT_COMMIT"
            }
        }
    }
}
