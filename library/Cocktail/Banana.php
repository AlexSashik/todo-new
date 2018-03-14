<?php

namespace Cocktail;

class Banana extends Additions
{
    public function getCocktail()
    {
        return parent::getCocktail().' с бананом';
    }
}