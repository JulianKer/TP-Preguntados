<?php

class PokedexModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getPokemons()
    {
        $pokemons = $this->database->query("SELECT * FROM pokemon order by number");

        return $this->trasnformImagePaths($pokemons);
    }

    public function filter($filter)
    {
        $sql = "SELECT * 
                FROM pokemon 
                WHERE name LIKE '%" . $filter . "%'
                OR number  LIKE '" . $filter . "'
                OR type_image  LIKE '%" . $filter . "%'
                order by number";

        $pokemons = $this->database->query($sql);

        if ( sizeof($pokemons) == 0 ) {
            $pokemons=  $this->getPokemons();
            $message = "No results found for $filter";
        }

        $data["pokemons"] = $this->trasnformImagePaths($pokemons);
        $data["message"] = $message ?? "";

        return $data;
    }


    public function trasnformImagePaths($pokemons) {
        foreach ($pokemons as $key => $pokemon) {
            // Modificar solo si la clave 'type' existe
            if (isset($pokemons[$key]['type'])) {
                $pokemons[$key]['type_image'] = "/public/images/types/" . $pokemons[$key]['type_image'];
            }

            // Modificar solo si la clave 'image' existe
            if (isset($pokemons[$key]['image'])) {
                $pokemons[$key]['image'] = "/public/images/pokemon/" . $pokemons[$key]['image'];
            }
        }
        return $pokemons;
    }


    public function delete()
    {
        $sql = "DELETE FROM pokemon WHERE id = " . $_GET['id'];
        $this->database->execute($sql);
    }

    public function add($number, $name, $image, $type_image)
    {
        $sql = "INSERT INTO pokemon (number, name, image, type_image) VALUES ('" . $number . "', '" . $name . "', '" . $image . "', '" . $type_image . "');";
        $this->database->execute($sql);
    }

    public function getPokemon($id){
        $sql = "SELECT * FROM pokemon WHERE id = " . $id;
        return $this->database->query($sql);
    }
}