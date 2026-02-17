<?php

namespace Riddle\TgBotBase\Db\Seed;

class SeedDto
{
    public function __construct(
        public private(set) array $pathes = [],
    ) {}

    public function addDirectoryOrFile(string $path): void
    {
        if (!is_dir($path) && !is_file($path)) {
            throw new \InvalidArgumentException("Указан невалидный путь: {$path}");
        }

        $this->pathes[] = $path;
    }
}
