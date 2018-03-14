<?php

namespace Cocktail;
/**
 * Class Banana расширяет базовый компонент "протеиновый коктейль"
 * @package Cocktail
 */
class Banana extends Additions
{
    /**
     * Добавляем в коктейль банан
     * @return mixed|string
     */
    public function getCocktail()
    {
        return parent::getCocktail().' с бананом';
    }
}