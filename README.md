### Setup

This is an SPA implementation of the test. I decided to go with Vuetify as the front-end lib for speed and for the fact that it's built on Bootstrap 4.
This gives me all the component I would need in the frontend plus some flexibility to alter data and props.

Backend is simple, No Repo, just some UseCases (Concerns), kept the controller simple and short, used fluent method calls for easy to read actions. 

In the root folder, do the following to install:

1. Run `composer install`
2. Run `npm install`
3. Run `php artisan setup` to migrate, seed, install passport keys and link storage files.
