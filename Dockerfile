FROM nimmis/apache-php5
MAINTAINER Mathias Neerup (mane@mmmi.sdu.dk)

RUN rm /var/www/html/index.html
COPY www/ /var/www/html/
