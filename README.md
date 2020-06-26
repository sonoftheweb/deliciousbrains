### Setup

This is an SPA implementation of the test. I decided to go with Vuetify as the front-end lib for speed and for the fact that it's built on Bootstrap 4.
This gives me all the component I would need in the frontend plus some flexibility to alter data and props.

Backend is simple, No Repo, just some UseCases (Concerns), kept the controller simple and short, used fluent method calls for easy to read actions. 

In the root folder, do the following to install:

1. Make sure you are serving the application from a virtual host with a URL, Take note of the URL.
2. Run `composer install`
3. Run `npm install`
4. Run `php artisan setup` to migrate, seed, install passport keys and link storage files. This time seeding is not done on activities, so you may test the implementation of import.
5. Install and start redis (if you do not already have it installed and running)
6. If you wish to, run `install -g laravel-echo-server` to install Laravel Echo Server globally, but it's already part of the package so it would be installed locally.
7. Update `./laravel-echo-server.json` file, changing "authHost" to the host name of your app... Remember that URL?
8. Run `laravel-echo-server start` to start the echo server
9. Run `php artisan queue:work` to start the queue driver
10. View the app. Pick any email in the DB. Password === `secret`. Upload a file and enjoy!

~~Note: I decided not to use Observers to update Balance as that invites some complexities which would require some more time to debug.~~
Removed balance table as it was not needed. 


#### Things I think should be done

1. Make repository interfaces to further abstract the database layer.
2. Work on Login.vue (make it a bit appealing).
3. Capitalize observers for account balance.
4. Pagination for events (or at least a load more or eager on scroll pagination).
