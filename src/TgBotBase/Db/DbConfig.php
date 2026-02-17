<?php

namespace Riddle\TgBotBase\Db;

class DbConfig
{
    public function __construct(
        public readonly string $dbDir,
        public private(set) array $migrations = [],
        public private(set) array $seedFiles = [],
        public private(set) array $sqlExecutions = [],
    ) {}

    /**
     * Можно добавлять новые таблицы и индексы
     */
    public function addMigration(MigrationDto $dto): self
    {
        $this->migrations[] = $dto;

        return $this;
    }

    /**
     * Можно заполнять таблицы
     */
    public function addSeedDirectory(string $directory): self
    {
        if (!str_ends_with($directory, '/')) {
            $directory .= '/';
        }

        if (!is_dir($directory)) {
            throw new \InvalidArgumentException("Директория не найдена: {$directory}");
        }

        foreach (scandir($directory) as $file) {
            if (is_file($directory . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                $this->addSeedFile($directory . $file);
            }
        }

        return $this;
    }
    
    /**
     * Можно заполнять таблицы
     */
    public function addSeedFile(string $filePath): self
    {
        if (!is_file($filePath) || pathinfo($filePath, PATHINFO_EXTENSION) !== 'sql') {
            throw new \InvalidArgumentException("Файл не найден или не является SQL файлом: {$filePath}");
        }
        $this->seedFiles[] = $filePath;

        return $this;
    }

    /**
     * SQL выполняется при запуске скрипта.
     */
    public function addExecution(string $sql): self
    {
        $this->sqlExecutions[] = $sql;

        return $this;
    }    
}
