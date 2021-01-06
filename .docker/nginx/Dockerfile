FROM nginx:1.19.3

COPY default.conf /etc/nginx/conf.d/default.conf
COPY nginx.conf /etc/nginx/nginx.conf
COPY GeoIP.dat /etc/nginx/GeoIP.dat
COPY GeoLiteCity.dat /etc/nginx/GeoLiteCity.dat

RUN apt-get update && apt-get install -y \
    certbot \
    python-certbot-nginx \
    vim \
    gnupg \
    wget \
    procps
