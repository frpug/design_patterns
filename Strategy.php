<?php
interface CompareStrategy
{
    /**
     * Compares two values, return an integer less than, equal to, or greater than zero if the first argument is
     * considered to be respectively less than, equal to, or greater than the second
     * @param $x
     * @param $y
     *
     * @return mixed
     */
    public function compare($x, $y);
}

class BackwardsIntSort implements CompareStrategy
{
    public function compare($x, $y)
    {
        if ($x == $y) {
            return 0;
        }

        return $x < $y;
    }
}

class ForwardsIntSort implements CompareStrategy
{
    public function compare($x, $y)
    {
        if ($x == $y) {
            return 0;
        }
        return $x > $y;
    }
}

class StringLengthSort implements CompareStrategy
{
    public function compare($x, $y)
    {
        if (strlen($x) == strlen($y)) {
            return 0;
        }

        return strlen($x) > strlen($y);
    }
}

class RevStringLengthSort extends StringLengthSort
{
    public function compare($x, $y)
    {
        $result = parent::compare($x, $y);
        return is_bool($result) ? !$result : 0;
    }
}

class SorterThing
{
    /** @var CompareStrategy */
    protected $comparer;

    public function __construct(CompareStrategy $comparer)
    {
        $this->comparer = $comparer;
    }

    public function sortArray($array)
    {
        $myArray = $array;
        usort($myArray, [$this->comparer, 'compare']);
        return $myArray;
    }
}

$numberArray = [5, 4, 12, 1, 0, 13, 22];
$stringArray = ['Front', 'Range', 'PHP', 'User', 'Group', 'FRPUG'];

$backwardSort = new SorterThing(new BackwardsIntSort());
$forwardSort = new SorterThing(new ForwardsIntSort());

$stringLengthSort = new SorterThing(new StringLengthSort());
$reverseStringSorter = new SorterThing(new RevStringLengthSort());

echo join(', ', $backwardSort->sortArray($numberArray)), "\n";
echo join(', ', $forwardSort->sortArray($numberArray)), "\n";

echo join(', ', $stringLengthSort->sortArray($stringArray)), "\n";
echo join(', ', $reverseStringSorter->sortArray($stringArray)), "\n";
