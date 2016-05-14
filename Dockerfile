FROM webdevops/php-nginx

ENV DEBIAN_FRONTEND noninteractive

RUN env

RUN apt-get update
RUN apt-get install lftp
RUN mkdir /app/.logs
RUN mkdir /app/.tmp
RUN chown $APPLICATION_USER /app/.logs
RUN chown $APPLICATION_USER /app/.tmp


ENV GIT_REPO_NAME x
ENV GIT_REPO_SOURCE_PATH x
ENV GIT_REPO_BRANCH master
ENV TARGET_FTP_HOST x
ENV TARGET_FTP_USER x
ENV TARGET_FTP_PASS x
ENV TARGET_FTP_PATH x

COPY ./git_hook.php /app
COPY ./bg.php /app
COPY ./up.sh /app
COPY ./updator.conf /etc/php5/fpm/pool.d
