FROM webdevops/php-nginx

ENV DEBIAN_FRONTEND noninteractive


RUN apt-get update
RUN apt-get install lftp
RUN mkdir /app/.logs

COPY ./git_hook.php /app
COPY ./git_pull.sh /app
