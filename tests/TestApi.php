<?php

declare(strict_types=1);

namespace Tests;

abstract  class TestApi extends TestCase
{
    /**
     * @var string|null
     */
    protected  ?string $routeName = null;

    /**
     * @var string $method
     */
    protected string $method = 'GET';

    /**
     * @return mixed
     */
    protected abstract function getTestData(): mixed;
}
