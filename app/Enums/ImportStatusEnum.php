<?php

namespace App\Enums;

enum ImportStatusEnum: int {
    case pending = 0;
    case processing = 1;
    case failed = 2;
    case completed = 3;
}
