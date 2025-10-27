<?php
class Product {
    public $id;
    public $name;
    public $price;
    public $description;
    public $image;
    public $stock;

    public function __construct($id, $name, $price, $description, $image, $stock) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->image = $image;
        $this->stock = $stock;
    }
}
