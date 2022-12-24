#!/usr/bin/env bash
GREEN='\033[0;32m'
NC='\033[0m' # No Color
echo -e "${GREEN}Start provisioning$...${NC}"
echo -e "\n\n${GREEN}Initial config...${NC}"
apt-get update
apt-get -y install apt-transport-https lsb-release ca-certificates curl
curl -sSLo /usr/share/keyrings/deb.sury.org-php.gpg https://packages.sury.org/php/apt.gpg
sh -c 'echo "deb [signed-by=/usr/share/keyrings/deb.sury.org-php.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list'

echo -e "\n\n${GREEN}Installing Apache and PHP...${NC}"
apt-get update
apt-get install -y apache2 git
apt-get install -y php7.4 php7.4-common php7.4-gd php7.4-mysql php7.4-imap php7.4-cli php7.4-cgi libapache2-mod-fcgid apache2-suexec-pristine mcrypt  imagemagick libruby libapache2-mod-python php7.4-curl php7.4-intl php7.4-pspell  php7.4-sqlite3 php7.4-tidy php7.4-xmlrpc php7.4-xsl memcached php7.4-memcache php7.4-imagick php7.4-zip php7.4-mbstring memcached libapache2-mod-passenger php7.4-soap php7.4-fpm php7.4-opcache php7.4-apcu libapache2-reload-perl php7.4-xdebug libapache2-mod-php7.4

a2enmod php7.4
a2enmod rewrite


update-alternatives --set php /usr/bin/php7.4
sed -i 's#DocumentRoot /var/www/html#DocumentRoot /var/www/html/public#g' /etc/apache2/sites-available/000-default.conf
sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride all/' /etc/apache2/apache2.conf

echo -e "\n\n${GREEN}Configuring XDEBUG...${NC}"
cat > /etc/php/7.4/fpm/conf.d/20-xdebug.ini << CONFIG
    zend_extension=xdebug.so

    xdebug.mode=debug
    xdebug.start_with_request=On
    xdebug.discover_client_host=On
    xdebug.client_port=9001
    xdebug.idekey="dev"

CONFIG

cat > /etc/php/7.4/cli/conf.d/20-xdebug.ini << CONFIG
    zend_extension=xdebug.so

    xdebug.mode=debug
    xdebug.start_with_request=On
    xdebug.discover_client_host=On
    xdebug.remote_host=10.0.2.2
    xdebug.client_port=9001
    xdebug.idekey="dev"

CONFIG

cat > /etc/php/7.4/apache2/conf.d/20-xdebug.ini << CONFIG
    zend_extension=xdebug.so

    xdebug.mode=debug
    xdebug.start_with_request=On
    xdebug.discover_client_host=On
    xdebug.client_port=9001
    xdebug.idekey="dev"
CONFIG
systemctl restart apache2
source /etc/apache2/envvars

echo -e "\n\n${GREEN}Downloading and installing composer...${NC}"
echo 'export HOME=/root' > /root/env
source /root/env

cd /tmp
curl --silent https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

echo -e "\n\n${GREEN}Configuring project...${NC}"

cd /var/www/html
rm -f index.html