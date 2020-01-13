FROM node:latest as build

WORKDIR /site
COPY package.json ./
COPY yarn.lock ./

RUN yarn install;

ENTRYPOINT yarn build;
