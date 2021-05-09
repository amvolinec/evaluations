<?php


namespace App;


class Version
{
    public static function resolveFacade($name)
    {
        return app()[$name];
    }

    public static function __callStatic(string $method, array $arguments)
    {
        return self::resolveFacade('Version')
            ->$method(...$arguments);
    }
}
