<?php declare(strict_types=1);

spl_autoload_register(function (string $pathClass): void {
    $pathClass = __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $pathClass);
    require "{$pathClass}.php";
});
