FROM phusion/baseimage:0.11
LABEL "name"="LAMP"
LABEL "author"="Ali Baris Ozturk"
LABEL "email"="alibarisozturk@gmail.com"

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

COPY ./dist /var/www/html
#RUN rm -rf /var/www/html/*

RUN mkdir /var/www/upload && \
	chown -R www-data:www-data /var/www/ && \
	chmod -R 777 /var/www/upload


	

EXPOSE 80 22
VOLUME  ["/var/lib/mysql", "/var/www/html", "/var/log/app", "/var/www/upload" ]
CMD ["/scripts/init.sh"]