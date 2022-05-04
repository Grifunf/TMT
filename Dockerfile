FROM ubuntu:latest
#Setup enviroment variables
ENV DEBIAN_FRONTEND=noninteractive
ENV API_PASSWD=<your_password>
#Install postgresql
RUN apt update && apt upgrade -y
RUN apt install postgresql postgresql-contrib -y
#RUN apt install apache2 curl php libapache2-mod-php php-mbstring php-cli php-bcmath php-json php-xml php-zip php-pdo ph>#Copy repo files into the docker container
COPY . /var/www/tmt
#Setup Database
RUN service postgresql start &&\
    su postgres bash -c "psql -c \"CREATE USER api WITH PASSWORD '${API_PASSWD}';\"" &&\
    su postgres bash -c "psql -c \"CREATE DATABASE tmt;\"" &&\
    su postgres bash -c "psql -d tmt -a -f /var/www/tmt/database/db_creation.sql" &&\
    su postgres bash -c "psql -d tmt -c \"GRANT ALL PRIVILEGES ON DATABASE tmt TO api; GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO api; GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA public TO api; GRANT ALL ON ALL SEQUENCES IN SCHEMA public TO api;\""
RUN chmod o+x /var/www/tmt/scripts/init.sh
CMD ["/var/www/tmt/scripts/init.sh"]