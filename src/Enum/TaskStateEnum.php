<?php

namespace App\Enum;

enum TaskStateEnum: string
{
    use BaseEnum;

    case TODO = 'TODO';
    case DOING = 'DOING';
    case DONE = 'DONE';
}
