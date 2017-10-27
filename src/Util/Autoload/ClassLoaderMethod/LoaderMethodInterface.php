<?php

declare(strict_types=1);

namespace Rector\BetterReflection\Util\Autoload\ClassLoaderMethod;

use Rector\BetterReflection\Reflection\ReflectionClass;

interface LoaderMethodInterface
{
    public function __invoke(ReflectionClass $classInfo) : void;
}
