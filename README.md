# Matrix Iterator

### Matrix Iterator

`Matrix\MatrixIterator` allows to iterate over several iterators
in all possible combinations.

```php
$ducks = new ArrayIterator(['duck1', 'duck2']);
$dogs = new ArrayIterator(['dog1', 'dog2', 'dog3']);
$cats = new ArrayIterator(['cat1', 'cat2', 'cat3', 'cat4']);

$iterator = new MatrixIterator();
$iterator->attachIterator($ducks);
$iterator->attachIterator($dogs);
$iterator->attachIterator($cats);

foreach($iterator as $key => $value)
{
    echo (implode(' | ', $value));
    echo PHP_EOL;
}

/**
 * Values will output:
 *     duck1 | dog1 | cat1
 *     duck1 | dog1 | cat2
 *     duck1 | dog1 | cat3
 *     duck1 | dog1 | cat4
 *     duck1 | dog2 | cat1
 *     duck1 | dog2 | cat2
 *     duck1 | dog2 | cat3
 *     duck1 | dog2 | cat4
 *     duck1 | dog3 | cat1
 *     duck1 | dog3 | cat2
 *     duck1 | dog3 | cat3
 *     duck1 | dog3 | cat4
 *     duck2 | dog1 | cat1
 *     duck2 | dog1 | cat2
 *     duck2 | dog1 | cat3
 *     duck2 | dog1 | cat4
 *     duck2 | dog2 | cat1
 *     duck2 | dog2 | cat2
 *     duck2 | dog2 | cat3
 *     duck2 | dog2 | cat4
 *     duck2 | dog3 | cat1
 *     duck2 | dog3 | cat2
 *     duck2 | dog3 | cat3
 *     duck2 | dog3 | cat4
 */

foreach($iterator as $key => $value)
{
    echo (implode(' | ', $key));
    echo PHP_EOL;
}

/**
 * Keys will output:
 *     0 | 0 | 0
 *     0 | 0 | 1
 *     0 | 0 | 2
 *     0 | 0 | 3
 *     0 | 1 | 0
 *     0 | 1 | 1
 *     0 | 1 | 2
 *     0 | 1 | 3
 *     0 | 2 | 0
 *     0 | 2 | 1
 *     0 | 2 | 2
 *     0 | 2 | 3
 *     1 | 0 | 0
 *     1 | 0 | 1
 *     1 | 0 | 2
 *     1 | 0 | 3
 *     1 | 1 | 0
 *     1 | 1 | 1
 *     1 | 1 | 2
 *     1 | 1 | 3
 *     1 | 2 | 0
 *     1 | 2 | 1
 *     1 | 2 | 2
 *     1 | 2 | 3
 */
```
