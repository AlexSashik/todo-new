<?php
namespace Cocktail;

class Milk extends Additions
{
    public function getCocktail()
    {
        return parent::getCocktail().' на молоке';
    }
}