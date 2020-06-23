FROM phusion/baseimage:0.11
MAINTAINER Ali Baris Ozturk <alibarisozturk@gmail.com>

# Install packages
ENV DEBIAN_FRONTEND noninteractive
RUN add-apt-repository -y ppa:ondrej/php && \
  apt-get update && \
  apt-get -y upgrade && \
  apt-get -y install postfix supervisor wget git apache2 php-xdebug libapache2-mod-php mysql-server php-mysql pwgen php-apcu php-gd php-xml php-mbstring php-gettext zip unzip php-zip curl php-curl && \
  apt-get -y autoremove
  
ENV TZ=Turkey

RUN rm -rf /var/lib/mysql/* && \
	usermod -d /var/lib/mysql/ mysql

#SSH key integration
ADD ./server/keys/authorized_keys /root/.ssh/authorized_keys
ADD ./server/scripts/ /scripts
RUN chmod -R 755 /scripts/*.sh

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
	rm -rf /etc/apache2/sites-enabled/*
	
COPY ./server/apache/*.conf /etc/apache2/sites-enabled/

RUN mkdir /var/log/app && \
	chmod -R 777 /var/log/app

RUN rm -rf /var/www/html/* && \
	ls -lh /var/www/ && \
	mkdir /var/www/html/upload && \
	mkdir /var/www/html/protected && \
	chown -R www-data:www-data /var/www/html && \
	chmod -R 777 /var/www/html/upload
	

EXPOSE 80 22
VOLUME  ["appsrv_mysql:/var/lib/mysql:rw", "appsrv_html:/var/www/html", "appsrv_log:/var/log/app" ]
CMD ["/scripts/init.sh"]