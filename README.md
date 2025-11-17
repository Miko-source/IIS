Nastavení databáze pro projekt (Laravel)

Tento projekt používá MySQL.
Níže najdete kompletní postup, jak databázi správně připravit a spustit migrace.

Reset databáze

Pokud chceš vše vyčistit a znovu vytvořit tabulky:

php artisan migrate:fresh
- smaže všechny tabulky v databázi a následně spustí všechny migrace od začátku.

Instalace databáze – krok za krokem
1) Vytvořit databázi

V MySQL:

CREATE DATABASE iisproj;

2) Vytvořit MySQL uživatele pro Laravel
CREATE USER 'laravel'@'localhost' IDENTIFIED BY 'secret123';

3) Přiřadit práva uživateli
GRANT ALL PRIVILEGES ON iisproj.* TO 'laravel'@'localhost';
FLUSH PRIVILEGES;

4) Nastavit .env soubor

V souboru .env nastav:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=iisproj
DB_USERNAME=laravel
DB_PASSWORD=secret123

✔ Hotovo

Po dokončení kroků stačí spustit:

php artisan migrate

a projekt je připraven k použití.
Dá se zkontrolovat pomocí
USE iisproj;
SHOW COLUMNS FROM users;