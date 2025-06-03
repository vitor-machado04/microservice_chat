# Usando imagem base do PHP com Apache
FROM php:8.1-apache

# Instalações necessárias
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev zip unzip git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilita mod_rewrite do Apache
RUN a2enmod rewrite

# Instala Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Define diretório de trabalho como raiz do projeto (onde está o composer.json)
WORKDIR /var/www/html

# Copia arquivos
COPY . /var/www/html/

# Copia a configuração do Xdebug
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Executa o composer install durante a build da imagem
RUN composer install --no-dev --optimize-autoloader

# Altera DocumentRoot do Apache para a pasta public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Expõe a porta padrão
EXPOSE 80

# Rodar o servidor Apache
CMD ["apache2-foreground"]
