<?php

declare(strict_types=1);

namespace Rieves\PestDatasetExpectations;

use Closure;
use Illuminate\Pipeline\Pipeline;
use Pest\Expectation;
use Pest\HigherOrderExpectation;

/**
 * @mixin Expectation
 */
class DatasetExpectation
{
    /**
     * Holds the expectations.
     *
     * @var array<int, Closure>
     */
    private array $expectations = [];

    /**
     * Run the expectations on the given value.
     *
     * @param mixed $value
     * @return void
     */
    public function on(mixed $value): void
    {
        (new Pipeline())
            ->send(new Expectation($value))
            ->through($this->expectations)
            ->thenReturn();
    }

    /**
     * Begin adding expectations.
     *
     * @return static
     */
    public static function expect(): static
    {
        return new static;
    }

    /**
     * Proxy calls to the expectation.
     *
     * @param string $name
     * @param array<int, mixed> $arguments
     *
     * @return self
     */
    public function __call(string $name, array $arguments): self
    {
        $this->expectations[] = function (Expectation|HigherOrderExpectation $expectation, Closure $next) use ($name, $arguments) {
            $next(call_user_func_array([$expectation, $name], $arguments));
        };

        return $this;
    }

    /**
     * Proxy properties to the expectation.
     *
     * @param string $name
     *
     * @return self
     */
    public function __get(string $name): self
    {
        $this->expectations[] = function (Expectation|HigherOrderExpectation $expectation, Closure $next) use ($name) {
            return $next($expectation->{$name});
        };

        return $this;
    }
}
