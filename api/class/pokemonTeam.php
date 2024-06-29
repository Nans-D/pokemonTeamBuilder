<?php


require_once __DIR__ . '/../../config.php';



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
        $sql = "DELETE FROM team WHERE id = '$this->id'";
        $result = mysqli_query($link, $sql);
        if ($result) {
            return true;
        }
        return false;
    }

    public function update()
    {
        $link = connexion();
        $sql = "UPDATE team SET first_pokemon = '$this->firstPokemon', second_pokemon = '$this->secondPokemon', third_pokemon = '$this->thirdPokemon', fourth_pokemon = '$this->fourthPokemon', fifth_pokemon = '$this->fifthPokemon', sixth_pokemon = '$this->sixthPokemon' WHERE id = '$this->id'";
        $result = mysqli_query($link, $sql);
        if ($result) {
            return true;
        }
        return false;
    }

    public function getTeam()
    {
        $link = connexion();
        $sql = "SELECT * FROM team WHERE id_user = '$this->id_user'";
        $result = mysqli_query($link, $sql);
        if (mysqli_num_rows($result) <= 0) {
            return 'No team found';
        }

        $responses = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $response['id'] = $row['id'];
            $response['id_user'] = $row['id_user'];
            $response['first_pokemon'] = $row['first_pokemon'];
            $response['second_pokemon'] = $row['second_pokemon'];
            $response['third_pokemon'] = $row['third_pokemon'];
            $response['fourth_pokemon'] = $row['fourth_pokemon'];
            $response['fifth_pokemon'] = $row['fifth_pokemon'];
            $response['sixth_pokemon'] = $row['sixth_pokemon'];

            $responses[] = $response;
        }

        return $responses;
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
