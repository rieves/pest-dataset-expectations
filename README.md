# Pest Dataset Expectations

Set expectations in your Pest datasets.

## Installation

> **Requires [PHP 8.1+](https://php.net/releases/)**

```bash
composer require rieves/pest-dataset-expectations --dev
```

## Usage

Use `DatasetExpectation::expect()` followed by your expectation as one of your dataset arguments.

Then inside your test, just pass the test subject to the `on` method of the passed DatasetExpectation instance.

## Examples

```php
use App\Models\Post;
use PestDatasetExpectations\DatasetExpectation;

it('casts attributes', function ($name, $value, DatasetExpectation $datasetExpectation) {
    $instance = new Post([
        $name => $value,
    ]);

    $datasetExpectation->on($instance);
})->with([
    'title to a string' => [
        'title', 1, DatasetExpectation::expect()->title->toBeString()
    ],
    'is_private to a boolean' => [
        'is_private', 1, DatasetExpectation::expect()->is_private->toBeBool()
    ],
    'comment_count to an integer' => [
        'comment_count', '1', DatasetExpectation::expect()->comment_count->toBeInt()
    ],
]);

it('determines if the post is not private', function (bool $value, DatasetExpectation $datasetExpectation) {
    $instance = new Post([
        'is_private' => $value,
    ]);

    $datasetExpectation->on($instance);
})->with([
    'false when is_private = true' => [
        true, DatasetExpectation::expect()->notPrivate()->toBeFalse()
    ],
    'true when is_private = false' => [
        false, DatasetExpectation::expect()->notPrivate()->toBeTrue()
    ],
]);
```

## License

Pest Dataset Expectations is an open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
