Phapi is a open-source skeleton app built for fast bootstraping lightweight Phalcon PHP Rest APIs.

In its core it uses \Phalcon\Mvc\Micro() and is heavily dependent on Phalcons dependency injection container, it is dockerized and comes with mysql server and portainer app for monitoring services. Uses a .env for handling all app configurations.

It provides skeleton project structure and few common needed modules and components for APIs:
 - Rest wrapper around request and response objects
 - Routing component
 - Exceptions and error handling
 - Logger (currently supports slack and loggly agents)
 - Profiler
 - ACL
 - Repository layer (with some limited in-memory caching layer)
 - JWT auth implementation for issuing and/or verifying tokens
 - EventsManager
 - Registry
 - *Model caching layer via annotations


As it relies heavily on phalcon4, please check the docs: https://docs.phalcon.io/4.0/en/introduction

The project uses custom Dockerfile for building the app and managing all sys dependencies. The base image is debian 10, and php and phalcon versions are customizable.
 
The project implements open-source JSON:API specification and all response/requests formats have been structured per:
[https://jsonapi.org/format/](https://jsonapi.org/format/)
 
more details to come.

### Dev Info:
Remember to run `composer-update.sh` (before going `docker-compose up`) if you use default volumes mapping that is already done in `docker-compose.yml` file.