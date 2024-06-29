<?php


function connexion()
{
    $serverName = 'localhost';
    $userName = 'root';
    $password = 'root';
    $dbName = 'pokemonteambuilder';
    $connexion = mysqli_connect($serverName, $userName, $password, $dbName);

    return $connexion;
}
