<?php

namespace Cocktail;

abstract class Additions implements Cocktail
{
    private $obj;

    public function __construct(Cocktail $obj)
    {
        $this->obj = $obj;
    }

    public function getCocktail()
    {
        return $this->obj->getCocktail();
    }
}