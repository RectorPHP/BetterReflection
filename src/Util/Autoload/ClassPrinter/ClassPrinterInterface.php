<?php

declare(strict_types=1);

namespace Rector\BetterReflection\Util\Autoload\ClassPrinter;

use Rector\BetterReflection\Reflection\ReflectionClass;

interface ClassPrinterInterface
{
    public function __invoke(ReflectionClass $classInfo) : string;
}
