# Sử dụng PHP 8.3 với FPM
FROM php:8.3-fpm

# Cài đặt Nginx + extension PHP cần thiết
RUN apt-get update && apt-get install -y \
    nginx \
    zip unzip git curl libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev zlib1g-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && rm -rf /var/lib/apt/lists/*

# Cài composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Đặt thư mục làm việc
WORKDIR /var/www
COPY . .

# Cài đặt thư viện composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Gán quyền cho thư mục storage & cache
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Cấu hình Nginx
RUN rm /etc/nginx/sites-enabled/default
COPY ./nginx.conf /etc/nginx/conf.d/default.conf

# Mở port HTTP để Render nhận
EXPOSE 80

# Chạy song song nginx + php-fpm
CMD service nginx start && php-fpm
