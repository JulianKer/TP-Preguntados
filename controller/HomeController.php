<?php

class HomeController
{
    private $presenter;
    private $model;

    public function __construct($model, $presenter) // ver pq deberia recibir tmb el model ya q necesito logica je
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }
}