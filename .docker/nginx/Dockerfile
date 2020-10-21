FROM nginx:1.19.3

COPY default.conf /etc/nginx/conf.d/default.conf

RUN apt-get update && apt-get install -y \
    certbot \
    python-certbot-nginx \
    vim
