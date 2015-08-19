FROM php

RUN mkdir -p /usr/src/app
WORKDIR /usr/src/app
COPY . /usr/src/app


EXPOSE 3000
RUN export FLASK_CONFIG=daocloud

CMD [ "php", "-S", "0.0.0.0:3000"]
