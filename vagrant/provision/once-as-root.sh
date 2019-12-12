#!/usr/bin/env bash

#== Import script args ==

timezone=$(echo "$1")

#== Bash helpers ==

function info {
  echo " "
  echo "--> $1"
  echo " "
}

#== Provision script ==

info "Provision-script user: `whoami`"

export DEBIAN_FRONTEND=noninteractive

info "Configure timezone"
timedatectl set-timezone ${timezone} --no-ask-password

info "Update OS software"
apt-get update
apt-get upgrade -y

info "Install additional software"
apt-get install -y php7.0-curl php7.0-cli php7.0-intl php7.0-pgsql php7.0-gd php7.0-fpm php7.0-mbstring php7.0-xml unzip nginx php.xdebug
apt-get install -y postgresql postgresql-contrib

info "Install and Configure PgSQL"
ver="$(sudo psql --version)"
ver_number="$(echo ${ver} | cut -d' ' -f3)"
ver_major="$(echo ${ver_number} | cut -d'.' -f1)"
ver_minor="$(echo ${ver_number} | cut -d'.' -f2)"
# config db
pconf_path=""
pconf_path_m="$(echo /etc/postgresql/${ver_major}/main/postgresql.conf)"
pconf_path_mm="$(echo /etc/postgresql/${ver_major}.${ver_minor}/main/postgresql.conf)"
# config db
if [[ -f ${pconf_path_m} ]]; then
	ver=${ver_major}
	pconf_path=${pconf_path_m}
else
	ver=${ver_major}.${ver_minor}
	pconf_path=${pconf_path_mm}
fi
# config db
sudo sed -i "s/#listen_address.*/listen_addresses '*'/" ${pconf_path}
var="cat >> /etc/postgresql/$ver/main/pg_hba.conf <<EOF
# Accept all IPv4 connections - FOR DEVELOPMENT ONLY!!!
host    all         all         0.0.0.0/0             md5
EOF"
echo $var
bash -c "$var"
# create user + db
su postgres -c "psql -c \"CREATE ROLE vagrant SUPERUSER LOGIN PASSWORD 'vagrant'\" "
su postgres -c "createdb -E UTF8 -T template0 --locale=en_US.utf8 -O vagrant vagrant"
# restart
/etc/init.d/postgresql restart
echo "Done!"

info "Configure PHP-FPM"
sed -i 's/user = www-data/user = vagrant/g' /etc/php/7.0/fpm/pool.d/www.conf
sed -i 's/group = www-data/group = vagrant/g' /etc/php/7.0/fpm/pool.d/www.conf
sed -i 's/owner = www-data/owner = vagrant/g' /etc/php/7.0/fpm/pool.d/www.conf
cat << EOF > /etc/php/7.0/mods-available/xdebug.ini
zend_extension=xdebug.so
xdebug.remote_enable=1
xdebug.remote_connect_back=1
xdebug.remote_port=9000
xdebug.remote_autostart=1
EOF
echo "Done!"

info "Configure NGINX"
sed -i 's/user www-data/user vagrant/g' /etc/nginx/nginx.conf
echo "Done!"

info "Enabling site configuration"
ln -s /app/vagrant/nginx/app.conf /etc/nginx/sites-enabled/app.conf
echo "Done!"

info "Removing default site configuration"
rm /etc/nginx/sites-enabled/default
echo "Done!"

info "Install composer"
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer