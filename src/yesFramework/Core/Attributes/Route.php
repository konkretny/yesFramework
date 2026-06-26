<?php

declare(strict_types=1);

namespace yesFramework\Core\Attributes;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Route
{
    public function __construct(
        public string $path,
        public string $method = 'GET'
    ) {
    }
}
