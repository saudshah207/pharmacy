FROM php:8.3-fpm

# Install nginx and supervisor
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    && docker-php-ext-install mysqli \
    && apt-get clean

WORKDIR /var/www/html

# Copy application
COPY . .

# Remove default nginx site
RUN rm /etc/nginx/sites-enabled/default

# Copy configs
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf
# Debug: print nginx configuration
RUN nginx -T

COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create required directories
RUN mkdir -p /run/php /var/log/supervisor

# Debug: check whether app files are in server root
RUN echo "APP FILES:" && ls -la /var/www/html/public

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]