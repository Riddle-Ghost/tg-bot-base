<?php

namespace Riddle\TgBotBase\Db;

class DbConfig
{
    public readonly string $dbDir;
    private(set) array $sqlExecutions = [];
    private(set) array $sqlExecutionFiles = [];
    
    public function __construct(
        string $dbDir
    )
    {
        $this->dbDir = $dbDir;
    }

    /**
     * SQL выполняется при запуске скрипта. Можно добавлять новые таблицы и индексы
     */
    public function addExecution(string $sql): self
    {
        $this->sqlExecutions[] = $sql;

        return $this;
    }

    /**
     * Файл SQL выполняется при запуске скрипта. Можно добавлять новые таблицы, индексы или заполнять таблицы данными
     */
    public function addExecutionFile(string $filePath): self
    {
        $this->sqlExecutions[] = $filePath;

        return $this;
    }
}
