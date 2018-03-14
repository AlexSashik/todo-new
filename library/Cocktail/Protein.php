<?php

namespace Cocktail;

/**
 * Class Protein реализует базовый компонент "протеиновый коктейль"
 * @package Cocktail
 */
class Protein implements Cocktail
{
    /**
     * "Делаем" протеиновый коктейль
     * @return string
     */
    public function getCocktail()
    {
        return 'Протеиновый коктейль';
    }
}