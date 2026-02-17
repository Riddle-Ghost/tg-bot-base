<?php

namespace Riddle\TgBotBase\Db\Seed;

use Riddle\TgBotBase\Db\DbConfig;

require_once __DIR__ . '/../rb-sqlite.php';

class SeedService
{
    public function __construct(
        private DbConfig $config,
        private SeedRepository $seedRepository,
    ) {}

    public function seedAll(): void
    {
        foreach ($this->config->seeds as $seed) {
            foreach ($seed->pathes as $path) {
                if (is_dir($path)) {
                    $this->fromDirectory($seed->dbName, $path);
                } else {
                    $this->fromFile($seed->dbName, $path);
                }
            }
        }
    }

    public function fromDirectory(string $dbName, string $directory): void
    {
        if (!str_ends_with($directory, '/')) {
            $directory .= '/';
        }

        if (!is_dir($directory)) {
            throw new \InvalidArgumentException("Директория не найдена: {$directory}");
        }

        foreach (scandir($directory) as $file) {
            if (is_file($directory . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                $this->fromFile($dbName, $directory . $file);
            }
        }
    }

    public function fromFile(string $dbName, string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("SQL файл не найден: {$filePath}");
        }

        \R::selectDatabase($dbName);

        $count = $this->seedRepository->getCount($filePath);

        if ($count > 0) {
            return;
        }

        $sql = file_get_contents($filePath);
        if ($sql === false) {
            throw new \RuntimeException("Не удалось прочитать SQL файл: {$filePath}");
        }

        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                \R::exec($statement);
            }
        }

        $this->seedRepository->insert($filePath);
        echo $filePath . ' seed done' . PHP_EOL;
    }
}
