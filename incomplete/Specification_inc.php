<?php
/**
 * Business rules can be recombined by chaining the rules together using boolean logic
 */
interface Specification
{
    public function isSatisfiedBy($candidate);

    public function plus(Specification $specification);

    public function either(Specification $specification);

    public function boolNot();
}

class Specification_inc
{

}
