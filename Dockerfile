# Use the dwchiang/nginx-php-fpm image with PHP 8.2
# Note: If the tag '8.2' doesn't work, try 'php8.2' or 'latest'
# Verify available tags at: https://hub.docker.com/r/dwchiang/nginx-php-fpm/tags
FROM dwchiang/nginx-php-fpm:latest

# Set working directory
WORKDIR /var/www/html

# Install system dependencies and PHP extensions required for Laravel
RUN apt-get update && apt-get install -y --no-install-recommends \
  git \
  curl \
  libpng-dev \
  libzip-dev \
  libpq-dev \
  libonig-dev \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) \
  pdo_pgsql \
  pdo_mysql \
  mbstring \
  exif \
  pcntl \
  bcmath \
  gd \
  zip \
  opcache \
  && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Copy custom Nginx configuration for Laravel
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf

# Copy deploy script for Render
COPY docker/render-deploy.sh /usr/local/bin/render-deploy.sh
RUN chmod +x /usr/local/bin/render-deploy.sh

# Set proper permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
  && chmod -R 755 /var/www/html \
  && chmod -R 775 /var/www/html/storage \
  && chmod -R 775 /var/www/html/bootstrap/cache

# Build frontend assets (if needed in production)
# Uncomment the following lines if you want to build assets in the container
# RUN npm ci --only=production && npm run build && rm -rf node_modules

# Expose port 80
EXPOSE 80

# Create entrypoint script that runs deploy script then starts services
RUN echo '#!/bin/sh' > /entrypoint.sh && \
  echo 'set -e' >> /entrypoint.sh && \
  echo '/usr/local/bin/render-deploy.sh || true' >> /entrypoint.sh && \
  echo 'exec "$@"' >> /entrypoint.sh && \
  chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]

# Start nginx and php-fpm (the base image may handle this, but we ensure it works)
CMD ["/bin/sh", "-c", "nginx -g 'daemon off;' & php-fpm -F"]

