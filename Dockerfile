FROM ubuntu:latest
#Setup enviroment variables
ENV DEBIAN_FRONTEND=noninteractive
ENV API_PASSWD=<your_password>
#Install postgresql
RUN apt update && apt upgrade -y
RUN apt install postgresql postgresql-contrib -y
#Install Necessary packages for Laravel Backend
RUN apt install unzip curl apache2 php libapache2-mod-php php-mbstring php-cli php-bcmath php-json php-xml php-zip php-pdo php-common php-tokenizer php-curl php-pgsql -y &&\
    curl -sS https://getcomposer.org/installer | php &&\
    mv composer.phar /usr/local/bin/composer &&\
    chmod +x /usr/local/bin/composer
#Install Necessary packages for Vue3 front-end
RUN curl -sL https://deb.nodesource.com/setup_16.x -o /tmp/nodesource_setup.sh &&\
    bash /tmp/nodesource_setup.sh &&\
    apt install nodejs -y &&\
    npm install -g npm@latest
#Copy repo files into the docker container
COPY . /var/www/tmt
#Setup Database
RUN service postgresql start &&\
    su postgres bash -c "psql -c \"CREATE USER api WITH PASSWORD '${API_PASSWD}';\"" &&\
    su postgres bash -c "psql -c \"CREATE DATABASE tmt;\"" &&\
    su postgres bash -c "psql -d tmt -a -f /var/www/tmt/database/db_creation.sql" &&\
    su postgres bash -c "psql -d tmt -c \"GRANT ALL PRIVILEGES ON DATABASE tmt TO api; GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO api; GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA public TO api; GRANT ALL ON ALL SEQUENCES IN SCHEMA public TO api;\""
#Setup website
RUN service postgresql start &&\
    mv /var/www/tmt/config/tmt.conf /etc/apache2/sites-available/ &&\
    a2dissite 000-default &&\
    a2ensite tmt &&\
    a2enmod rewrite &&\ 
    cd /var/www/tmt &&\
    composer install &&\
    cp .env.example .env &&\
    php artisan migrate --path=/database/migrations/0000_00_00_000000_create_websockets_statistics_entries_table.php &&\
    php artisan key:generate
#Setup Vue3
RUN cd /var/www/tmt &&\
    npm install &&\
    npm run dev &&\
    chown -R www-data:www-data /var/www/tmt
RUN chmod o+x /var/www/tmt/scripts/init.sh
CMD ["/var/www/tmt/scripts/init.sh"]