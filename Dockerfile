# PHP Apache 8.4
FROM php:8.4-apache

# Diretório no contâiner
WORKDIR /var/www/html

# Dependências de sistema
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpq-dev \ 
    libcurl4-openssl-dev \
    libssl-dev \
    unzip \
    wget \
    lsb-release \
    ca-certificates \
    apt-transport-https \
    libkrb5-dev \
    && curl -sL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \ 
    && rm -rf /var/lib/apt/lists/*

# Instala extensões PHP 
RUN docker-php-ext-install pdo_mysql zip gd pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis

# Habilita apache reescrever URLs
RUN a2enmod rewrite headers

# Seta onde o apache vai procurar a raiz do projeto
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Muda pasta do npm-cache para dentro do projeto, afim de evitar conflitos de permissão
ENV NPM_CONFIG_CACHE=/var/www/html/.npm-cache

# Define usuários
ARG UID=1000
ARG GID=1000
RUN usermod -u ${UID} www-data && groupmod -g ${GID} www-data

# Cria entrypoint para instalação de dependências após build do container
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chown www-data:www-data /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

RUN mkdir -p /var/www/.config/psysh
RUN chmod -R 775 /var/www/.config 
RUN chown -R www-data:www-data /var/www/.config

# Passa para o user www-data
RUN chown -R www-data:www-data /var/www/html
USER www-data

# Entrypoint para composer install e npm install
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 80