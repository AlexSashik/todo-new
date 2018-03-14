<?php
namespace Cocktail;

/**
 * Class Milk расширяет базовый компонент "протеиновый коктейль"
 * @package Cocktail
 */
class Milk extends Additions
{
    /**
     * Делайем коктейль на молоке
     * @return mixed|string
     */
    public function getCocktail()
    {
        return parent::getCocktail().' на молоке';
    }
}