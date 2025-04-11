# LiveCampus_ProjectImageLibrary

Ce qu'il faut installer comme packages pour l'envoi d'un mail:
composer require symfony/messenger
composer require symfony/scheduler
composer require symfony/http-client
composer require symfony/mailer

Ce qu'il faut installer comme packages pour l'envoi d'un mail:
composer require symfony/messenger (Admin)
composer require symfony/scheduler (Admin)
composer require symfony/http-client (Admin)
composer require symfony/mailer (Admin)
composer require --dev doctrine/doctrine-fixtures-bundle (API)
composer require phpoffice/phpspreadsheet (admin)
Ceci est ma branch

Installer le composer require symfony/uid (API/Public)

Sur la page stats d'une image, il faut F5 la page pour que le graph s'affiche
php bin/console messenger:consume async : permet de consume les messenges = envoi les emails
php bin/console messenger:consume scheduler_email : permet
