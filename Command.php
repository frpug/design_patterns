<?php

interface ExecutableCommand
{
    public function execute();
}

interface UndoableCommand extends ExecutableCommand
{
    public function undo();
}

class Book
{
    protected $title;
    protected $publisher;
    protected $year;
    protected $author;

    public function __construct($title = null, $publisher = null, $year = null, $author = null)
    {
        $this->title = $title;
        $this->publisher = $publisher;
        $this->year = $year;
        $this->author = $author;
    }

    /**
     * @return mixed|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title value for the object
     *
     * @param mixed $title
     *
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed|null
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * Sets the publisher value for the object
     *
     * @param mixed $publisher
     *
     * @return void
     */
    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;
    }

    /**
     * @return mixed|null
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Sets the year value for the object
     *
     * @param mixed $year
     *
     * @return void
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return mixed|null
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Sets the author value for the object
     *
     * @param mixed $author
     *
     * @return void
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }
}

class BookSetter implements UndoableCommand
{
    protected $book;

    protected $originalValues;
    protected $newValues;

    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    public function registerNewValues(array $values)
    {
        $this->newValues = $values;
    }

    public function execute()
    {
        // If original values is an array then we know the command has been run
        $this->originalValues = [];

        foreach ($this->newValues as $key => $value) {
            $this->originalValues[$key] = $this->getBookValue($key);
            $this->setBookValue($key, $value);
        }
    }

    public function undo()
    {
        if (!is_array($this->originalValues)) {
            throw new RuntimeException('Cannot undo command, it hasn not been executed.');
        }

        foreach ($this->originalValues as $key => $value) {
            $this->setBookValue($key, $value);
        }

        $this->originalValues = null;
    }

    protected function getBookValue($key)
    {
        $getter = 'get' . ucfirst($key);
        if (method_exists($this->book, $getter)) {
            return $this->book->$getter();
        }

        return null;
    }

    protected function setBookValue($key, $value)
    {
        $setter = 'set' . ucfirst($key);
        if (method_exists($this->book, $setter)) {
            $this->book->$setter($value);
        }
    }
}

$blankBook = new Book();

echo "Blank Book\n";
var_dump($blankBook);

$sidewalkBook = new Book('Where The Sidewalk Ends', 'HarperCollins', 2014, 'Shel Silverstein');

echo "Sidewalk Book\n";
var_dump($sidewalkBook);

$newValues = [
    'title' => '50 Shades of Beige',
    'author' => 'Reid Mockery',
    'publisher' => 'Murmuring Press',
    'year' => 2013
];

$blankSetter = new BookSetter($blankBook);
$blankSetter->registerNewValues($newValues);

$sidewalkSetter = new BookSetter($sidewalkBook);

$sidewalkSetter->registerNewValues($newValues);

$blankSetter->execute();
$sidewalkSetter->execute();

echo "Blank Book after Execute\n";
var_dump($blankBook);

echo "Sidewalk Book after Execute\n";
var_dump($sidewalkBook);

$sidewalkSetter->undo();

echo "Sidewalk Book after Undo\n";
var_dump($sidewalkBook);
