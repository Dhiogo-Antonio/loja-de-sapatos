<?php
class Product {
    public $id;
    public $name;
    public $preco;
    public $description;
    public $image;
    public $stock;

    public function __construct($id, $name, $preco, $description, $image, $stock) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $preco;
        $this->description = $description;
        $this->image = $image;
        $this->stock = $stock;
    }
}
