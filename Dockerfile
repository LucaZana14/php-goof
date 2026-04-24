# 1. Usiamo Apache su Debian Bookworm (molto più compatibile e sicuro)
FROM php:8.3-apache-bookworm

# 2. Usiamo apt-get (Debian) invece di apk (Alpine)
RUN apt-get update && apt-get upgrade -y && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    zlib1g-dev \
    && apt-get --only-upgrade install -y zlib1g \
    && docker-php-ext-install mysqli pdo pdo_mysql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Copiamo Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Ora a2enmod funzionerà!
RUN a2enmod rewrite

WORKDIR /var/www/html/

# 5. Copiamo il progetto
COPY . .

# 6. Installiamo le dipendenze. 
# Rimuoviamo il lock vecchio per evitare i conflitti di versione che hai visto
RUN rm -f composer.lock && \
    composer install --ignore-platform-reqs --no-interaction --no-plugins --no-scripts --prefer-dist

# 7. Permessi corretti per Apache
RUN chown -R www-data:www-data /var/www/html/