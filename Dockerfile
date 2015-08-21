# 使用官方 PHP-Apache 镜像
FROM daocloud.io/php:5.6-fpm
# Install modules
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng12-dev \
    && docker-php-ext-install iconv mcrypt \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd

# docker-php-ext-install 为官方 PHP 镜像内置命令，用于安装 PHP 扩展依赖
# pdo_mysql 为 PHP 连接 MySQL 扩展
RUN docker-php-ext-install pdo_mysql

 

# mysql config
# /var/www/html/ 为 Apache 目录
RUN mkdir -p /usr/src/app 
WORKDIR /usr/src/app 
COPY . /usr/src/app 

EXPOSE 80  
#ENTRYPOINT ["php-fpm", "-S", "0.0.0.0:80"]
CMD ["php-fpm"]
