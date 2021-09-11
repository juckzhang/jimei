#!/bin/bash
# 添加web用户
useradd -ms /bin/bash nobody
groupadd nobody

# 更新源
apt-get update

# 创建/data1目录
mkdir /data1
# 编译安装openresty
apt-get install -y libpcre3-dev libssl-dev perl make build-essential curl zlib1g-dev
cd /opt && wget https://openresty.org/download/openresty-1.15.8.1.tar.gz && tar -zxvf openresty-1.15.8.1.tar.gz
cd openresty-1.15.8.1
./configure --prefix=/data1/openresty
make && make install

# 安装php
apt-get install -y php7.2 php7.2-cli php7.2-curl php7.2-gd php7.2-json php7.2-mysql php7.2-odbc php7.2-opcache php7.2-bz2 php7.2-mbstring php7.2-mcrypt php7.2-zip php7.2-fpm

# 修改目录权限
mkdir -p /data1/openresty/htdocs
mkdir /data1/openresty/logs && chmod 0777 /data1/openresty/logs

# 下载项目代码
#cd /data1/openresty/htdocs
cd APP_PATH
git clone https://github.com/juckzhang/jimei.git jimei

# 创建文件上传目录
#mkdir -p jimei/backend/web/upload/media-poster

# 添加项目入口文件
cp -f kung/backend/web/index jimei/backend/web/index.php
cp -f kung/console/yii jimei/console/yii.php

# 拷贝配置文件
cp -f jimei/conf/nginx.conf /data1/openresty/nginx/conf/nginx.conf
cp -f jimei/conf/php.ini /etc/php/7.2/fpm/php.ini
cp -f jimei/conf/php-fpm.conf /etc/php/7.2/fpm/php-fpm.conf
cp -f jimei/conf/php-fpm.d/www.conf /etc/php/7.2/fpm/pool.d/www.conf

# 修改目录所属人
chown -R nobody.nobody jimei

# 启动服务
`which php-fpm7.2`
/data1/openresty/nginx/sbin/nginx

# 删除文件
rm -rf /opt/openresty-1.18.8.1*