# Docker Testing

Head over to http://labs.play-with-docker.com/ and create a new instance.

Download an application definition

    curl -Lo docker-compose.yml https://raw.githubusercontent.com/mr-php/timesheet/master/docs/resurces/play-with-docker/docker-compose.yml

Run the application setup

    docker-compose run --rm php yii migrate --interactive=0
    
And start the application
    
    docker-compose up -d
    
Your services will be available on their mapped port, just click the label right next to the node IP address.

Login with `admin` / `secret`