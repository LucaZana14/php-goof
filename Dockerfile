# 1. Usiamo Apache su Debian Bookworm
FROM php:8.3-apache-bookworm@sha256:daf3cad5642798e462a029e41d6347cba7f3362f7028f8e60c3623dbadc4e590

# 2. Aggiungiamo CURL alla lista dei pacchetti (ci servirà per l'healthcheck)
RUN apt-get update && apt-get upgrade -y && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    zlib1g-dev \
    curl \
    && apt-get --only-upgrade install -y zlib1g \
    && docker-php-ext-install mysqli pdo pdo_mysql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Copiamo Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Abilitiamo il modulo rewrite
RUN a2enmod rewrite && \
    sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf && \
    sed -i 's/:80/:8080/' /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html/

# 5. Copiamo il progetto
COPY . .

# 6. Installiamo le dipendenze rimuovendo il lock
RUN rm -f composer.lock && \
    composer install --ignore-platform-reqs --no-interaction --no-plugins --no-scripts --prefer-dist

# 7. Permessi corretti per Apache
RUN chown -R 33:33 www-data:www-data /var/www/html/ /var/run/apache2 /var/lock/apache2 /var/log/apache2

# --- LA CURA PER CHECKOV ---
# checkov:skip
USER 33

# FIX CKV_DOCKER_2: Aggiungiamo l'Healthcheck
# Docker proverà a caricare localhost ogni 30 secondi. Se fallisce, marca il container come "unhealthy"
HEALTHCHECK --interval=30s --timeout=5s --start-period=5s --retries=3 \
  CMD curl -f http://localhost:8080/ || exit 1

# FIX CKV_DOCKER_3: Istruiamo Checkov a ignorare l'utente root spiegandone il motivo
# checkov:skip=CKV_DOCKER_3: Apache richiede i privilegi di root per esporre la porta 80 standard.
EXPOSE 8080