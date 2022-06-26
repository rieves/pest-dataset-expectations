# Pest Dataset Expectations

Set expectations in your Pest datasets.

## Installation

> **Requires [PHP 8.1+](https://php.net/releases/)**

```bash
composer require rieves/pest-dataset-expectations --dev
```

## Usage

Use `DatasetExpectation::expect()` followed by your expectation as one of your dataset arguments.

Then inside your test, just pass the test subject to the DatasetExpectation instance's `on` method.

## Example

```php
use App\Models\Post;
use Carbon\Carbon;
use PestDatasetExpectations\DatasetExpectation;


it('casts attributes', function (string $attribute, mixed $value, DatasetExpectation $datasetExpectation) {
    $instance = new Post([
        $attribute => $value,
    ]);

    $datasetExpectation->on($instance);
})->with([
    'title to a string' => ['title', 1, DatasetExpectation::expect()->title->toBeString()],
    'is_private to a boolean' => ['is_private', 1, DatasetExpectation::expect()->is_private->toBeBool()],
    'published_at to a Carbon instance' => ['published_at', '2022-01-01 00:00:00', DatasetExpectation::expect()->published_at->toBeInstanceOf(Carbon::class)],
]);

it('determines if the post is private or not', function (bool $value, DatasetExpectation $datasetExpectation) {
    $instance = new Post([
        'is_private' => $value,
    ]);

    $datasetExpectation->on($instance);
})->with([
    'false when is_private = false' => [false, DatasetExpectation::expect()->isPrivate()->toBeFalse()],
    'true when is_private = true' => [true, DatasetExpectation::expect()->isPrivate()->toBeTrue()],
]);

it('determines if the post is published or not', function (string $now, ?string $publishedAt, DatasetExpectation $datasetExpectation) {
    Carbon::setTestNow($now);
    
    $instance = new Post([
        'published_at' => $publishedAt,
    ]);

    $datasetExpectation->on($instance);
})->with([
    'false when published_at = null' => ['2020-01-01 12:00:00', null, DatasetExpectation::expect()->isPublished()->toBeFalse()],
    'false when now < published_at' => ['2020-01-01 11:59:59', '2020-01-01 12:00:00', DatasetExpectation::expect()->isPublished()->toBeFalse()],
    'true when now = published_at' => ['2020-01-01 12:00:00', '2020-01-01 12:00:00', DatasetExpectation::expect()->isPublished()->toBeTrue()],
    'true when now > published_at' => ['2020-01-01 12:00:01', '2020-01-01 12:00:00', DatasetExpectation::expect()->isPublished()->toBeTrue()],
]);
```

## License

Pest Dataset Expectations is an open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
