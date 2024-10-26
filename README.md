# Aplikace adresář
## Aktivace aplikace
- aplikace je napsána v **PHP 7.4**
- do oblužného zařízení instalujte **XAMPP** pro verzi PHP 7.4 - Apache server s podporou databáze MySQL.
- při defaultním nastavení instalace vytvoří na disku **C:/** adresářovou strukturu `C:/xampp/htdocs`
- v adresáři `htdocs`vytvořte složku například `addressbook`, do které nahrajte obsah repozitáře
- v internetovém prohlížeči na adrese `http://localhost/phpmyadmin/` vytvořte databázi se jménem `book`
- v adresářové složce s uloženým repozitážem najdete adresář `migrations`
- vloženou migraci spusťte jako SQL dotaz ve vytvořené databázi
- vznikne databázová tabulka `book`, ve které budou aplikací spravované kontakty
## Spuštění aplikace ve Windows
- spustíme aplikace `XAMPP`
- kliknutím na ikonu **Windows** napíšeme do vyhledávacího řádku příkaz `cmd` a spustíme příkazový řádek
- v příkazové řádku se pomocí příkazů `cd..`vracíme a jednu adresářovou hladinu zpět
- v příkazovém řádku se pomocí příkazu `cd <název adresáře>` otevíráme existující adresář ve struktuře
- pomocí příkazů nastavíme adresář na `C:/xampp/htdocs/addressbook`
- příkazem `php -S localhost:8000 -t public/` spustíme aplikace na adrese `localhost` resp. `127.0.0.1` a portu `8000`
- v internetovém prohlížečí nalezneme na adrese `http://localhost:8000` aplikaci
- stisknutím kombinace kláves `Ctrl + C` v **cmd** aplikaci ukončíme
## Shrnutí projektu
- aplikace je plně funkční pro model **CRUD** záznamů adresáře, připravena bez ohledu na finální vzhled resposivitu, CSS apod.