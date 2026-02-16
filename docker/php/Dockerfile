FROM dunglas/frankenphp:1-php8.2

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    mariadb-client

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions using install-php-extensions helper
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo_mysql mbstring exif pcntl bcmath gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
RUN chown -R www-data:www-data /var/www

# Set Caddy configuration for FrankenPHP (Laravel needs this for routing)
ENV SERVER_NAME=:80
ENV FRANKENPHP_CONFIG="worker ./public/index.php"

# Use the default entrypoint from FrankenPHP
# CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]

