<?php

namespace App\Enum;

enum EmploymentContractEnum: string
{
    use BaseEnum;

    case CDI = 'CDI';
    case CDD = 'CDD';
    case FREELANCE = 'Freelance';
}