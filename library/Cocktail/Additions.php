<?php

namespace Cocktail;
/**
 * Class Additions декорирует класс Protein
 * @package Cocktail
 */
abstract class Additions implements Cocktail
{
    /**
     * @var Cocktail
     */
    private $obj;

    /**
     * Additions constructor. Записывает в свойство $obj объект интерфейса Coctail
     * @param Cocktail $obj
     */
    public function __construct(Cocktail $obj)
    {
        $this->obj = $obj;
    }

    /**
     * Вызываемт аналогичный метод класса, который содержится в переменной $obj
     * @return mixed
     */
    public function getCocktail()
    {
        return $this->obj->getCocktail();
    }
}