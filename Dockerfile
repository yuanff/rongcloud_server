# 使用官方 PHP-Apache 镜像
FROM php

# docker-php-ext-install 为官方 PHP 镜像内置命令，用于安装 PHP 扩展依赖
# pdo_mysql 为 PHP 连接 MySQL 扩展
RUN docker-php-ext-install pdo_mysql
RUN apt-get -y install php5-curl php5-gd php5-intl php-pear php5-imagick php5-imap php5-mcrypt php5-memcache php5-ming php5-ps php5-pspell php5-recode php5-sqlite php5-tidy php5-xmlrpc php5-xsl

 

# mysql config
# /var/www/html/ 为 Apache 目录
RUN mkdir -p /usr/src/app 
WORKDIR /usr/src/app 
COPY . /usr/src/app 

EXPOSE 80  
ENTRYPOINT ["php", "-S", "0.0.0.0:80"]
