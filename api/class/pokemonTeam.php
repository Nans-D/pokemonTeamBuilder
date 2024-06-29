<?php

require_once '../config.php';


class PokemonTeam
{
    private $id;
    private $id_user;
    private $firstPokemon;
    private $secondPokemon;
    private $thirdPokemon;
    private $fourthPokemon;
    private $fifthPokemon;
    private $sixthPokemon;

    public function __construct($id = null, $id_user = null, $firstPokemon = null, $secondPokemon = null, $thirdPokemon = null, $fourthPokemon = null, $fifthPokemon = null, $sixthPokemon = null)
    {
        $this->id = $id;
        $this->id_user = $id_user;
        $this->firstPokemon = $firstPokemon;
        $this->secondPokemon = $secondPokemon;
        $this->thirdPokemon = $thirdPokemon;
        $this->fourthPokemon = $fourthPokemon;
        $this->fifthPokemon = $fifthPokemon;
        $this->sixthPokemon = $sixthPokemon;
    }

    public function create()
    {

        if (is_null($this->id_user)) {
            throw new Exception('Please, choose a user');
        }

        $link = connexion();
        $sql = "INSERT INTO team 
        (`id`, 
        `id_user`, 
        `first_pokemon`, 
        `second_pokemon`, 
        `third_pokemon`, 
        `fourth_pokemon`, 
        `fifth_pokemon`, 
        `sixth_pokemon`,
        `created_at`, 
        `updated_at`)
        VALUES (
        NULL,
        " . (!is_null($this->id_user) ? "'" . mysqli_real_escape_string($link, $this->id_user) . "'" : "NULL") . ",
        " . (!is_null($this->firstPokemon) ? "'" . mysqli_real_escape_string($link, $this->firstPokemon) . "'" : "NULL") . ",
        " . (!is_null($this->secondPokemon) ? "'" . mysqli_real_escape_string($link, $this->secondPokemon) . "'" : "NULL") . ",
        " . (!is_null($this->thirdPokemon) ? "'" . mysqli_real_escape_string($link, $this->thirdPokemon) . "'" : "NULL") . ",
        " . (!is_null($this->fourthPokemon) ? "'" . mysqli_real_escape_string($link, $this->fourthPokemon) . "'" : "NULL") . ",
        " . (!is_null($this->fifthPokemon) ? "'" . mysqli_real_escape_string($link, $this->fifthPokemon) . "'" : "NULL") . ",
        " . (!is_null($this->sixthPokemon) ? "'" . mysqli_real_escape_string($link, $this->sixthPokemon) . "'" : "NULL") . ",
        CURRENT_TIMESTAMP,
        CURRENT_TIMESTAMP
        )";



        $result = mysqli_query($link, $sql);
        if ($result) {
            $this->id = mysqli_insert_id($link);
            return true;
        }
        return false;
    }

    public function delete()
    {
        $link = connexion();
        $sql = "DELETE FROM pokemon_team WHERE id = '$this->id'";
        $result = mysqli_query($link, $sql);
        if ($result) {
            return true;
        }
        return false;
    }

    public function update()
    {
        $link = connexion();
        $sql = "UPDATE pokemon_team SET firstPokemon = '$this->firstPokemon', secondPokemon = '$this->secondPokemon', thirdPokemon = '$this->thirdPokemon', fourthPokemon = '$this->fourthPokemon', fifthPokemon = '$this->fifthPokemon', sixthPokemon = '$this->sixthPokemon' WHERE id = '$this->id'";
        $result = mysqli_query($link, $sql);
        if ($result) {
            return true;
        }
        return false;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getId_user()
    {
        return $this->id_user;
    }

    public function getFirstPokemon()
    {
        return $this->firstPokemon;
    }

    public function getSecondPokemon()
    {
        return $this->secondPokemon;
    }

    public function getThirdPokemon()
    {
        return $this->thirdPokemon;
    }

    public function getFourthPokemon()
    {
        return $this->fourthPokemon;
    }

    public function getFifthPokemon()
    {
        return $this->fifthPokemon;
    }

    public function getSixthPokemon()
    {
        return $this->sixthPokemon;
    }
}
