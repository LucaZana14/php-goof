# Usiamo l'immagine ufficiale di PHP con Apache
FROM php:8.3-fpm-alpine

# Installiamo i pacchetti base, inclusa la libreria zip (spesso vitale per Composer)
RUN apt-get update && apt-get install -y \
    git zip unzip libzip-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql zip \
    && apt-get clean

# Copiamo Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Abilitiamo il modulo rewrite di Apache
RUN a2enmod rewrite

WORKDIR /var/www/html/

# Questa volta copiamo TUTTO il progetto prima di lanciare Composer.
# Così troverà non solo il composer.json, ma anche il composer.lock e altri file necessari.
COPY . .

# Lanciamo Composer in modalità "blindata":
# --no-plugins e --no-scripts evitano che codice vecchio mandi in crash l'installazione
RUN composer install --ignore-platform-reqs --no-interaction --no-plugins --no-scripts --prefer-dist

# Diamo i permessi ad Apache
RUN chown -R www-data:www-data /var/www/html/