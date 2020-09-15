## prerequisits:

   install XAMPP or WAMPP
   connect to mysql database using phpmyadmin
   create a database called data in phpmyadmin.You can rename it in .env file
   to generate database tables run php artisan migrate

## To use the project you should first run the following commands:

   composer install
   php artisan passport:client

## For sending email use:

   www.mailtrap.io
   && create account and use the credentials in .env file

## To change the app used for login with facebook or to change the app url used in redirecting edit the facebook field in the following directory:

   config/services

## To view the api documentation go to the following directory:

   /public/docs/index.html

## You will find our suggested ui of the mobile application in the following link:

   https://wireframepro.mockflow.com/view/M2617a53bba39b9d7c78bb260289c3be31595425324666#/page/6fa943511b3a4033b512010f63ab86a6