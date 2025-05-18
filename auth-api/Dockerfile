# Usando imagem base do PHP com Apache
FROM php:8.1-apache

# Instalando extensões necessárias (se precisar de mais, você pode adicionar)
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Habilitar mod_rewrite do Apache
RUN a2enmod rewrite

# Definir o diretório de trabalho dentro do container
WORKDIR /var/www/html

# Copiar todos os arquivos do projeto para dentro do container
COPY . .

# Instalar Composer no container
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Expor a porta do Apache
EXPOSE 80

# Comando para rodar o Apache em foreground
CMD ["apache2-foreground"]
