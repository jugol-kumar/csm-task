<?php

namespace App\Contract;

class Role
{
    public static string $ADMIN = 'admin';
    public static string $OWNER = 'owner';
    public static string $CUSTOMER = 'customer';
    public static function roles(): array
    {
        return [
            self::$ADMIN,
            self::$OWNER,
            self::$CUSTOMER,
        ];
    }
}
