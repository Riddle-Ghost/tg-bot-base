# tg-bot


Добавить несколько баз
DB_NAME = 'database.db'
DB_USERS_NAME = 'users.db'
DB_LOGS_NAME = 'logs.db'


## Database
Создать дамп

sqlite3 tg_base/db/data/database.db ".dump" | grep "^INSERT" > tg_base/db/dump/data_dump.sql

Накатить дамп

sqlite3 tg_base/db/data/database.db < tg_base/db/dump/data_dump.sql
