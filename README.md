Transpile to PHP 5.6
- www\server\vendor\bin\php7to5 convert www\server\src7.2 www\server\src
- Throwable  => Exception

---

Zip release
- zip -r main.zip ./dev/ ./release/

---

Migrations
- .\vendor\bin\phinx migrate -c .\resources\db\phinx.yml
- .\vendor\bin\phinx seed:run -c .\resources\db\phinx.yml
