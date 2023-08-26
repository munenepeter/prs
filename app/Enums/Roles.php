<?php

namespace App\Enums;

use App\Enums\Concerns\HasValues;

enum Roles: string
{
    use HasValues;

    case ADMIN = 'admin';
    case PROJECT_MANAGER = 'project_manager';
    case USER = 'user';
}
