FROM php:8.1-cli

ARG APP_USER="user"
RUN set -x && useradd -u 1000 -m -s /bin/bash ${APP_USER}
ENV APP_USER=${APP_USER}

# MC,COMPOSER,CURL,WGET,GIT
RUN set -x \
&& apt-get update && apt-get install -y \
curl \
wget \
git


#FINAL
WORKDIR /var/www

# COLOR SCHEME
ENV TERM=xterm-color

# ADD VENDOR BIN TO $PATH
ENV PATH /var/www/vendor/bin:$PATH

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y libxml2-dev

RUN pecl install redis
RUN docker-php-ext-enable redis
RUN docker-php-ext-install sockets pdo_mysql soap

CMD ["/docker-init/run.sh"]