# tg-bot-base


## Database
Создать дамп

sqlite3 tg_base/db/data/database.db ".dump" | grep "^INSERT" > tg_base/db/dump/data_dump.sql

Накатить дамп

sqlite3 tg_base/db/data/database.db < tg_base/db/dump/data_dump.sql
