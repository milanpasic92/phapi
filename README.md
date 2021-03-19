Phapi is a open-source skeleton app built for fast bootstraping lightweight Phalcon PHP Rest APIs.

In its core it uses \Phalcon\Mvc\Micro() and is heavily dependent on Phalcons dependency injection container, it is dockerized and comes with mysql server and portainer app for monitoring services. Uses a .env for handling all app configurations.

It provides skeleton project structure and few common needed modules and components for APIs:
 - Rest wrapper around request and response objects
 - Routing component
 - Exceptions and error handling
 - Logger (currently supports slack and loggly agents)
 - *Profiler
 - *Repository layer
 - *JWT auth implementation for issuing and/or verifying tokens
 - *Model caching layer via annotations
 
more details to come.
