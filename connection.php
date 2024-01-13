<?php

function returnConnection(){
	$server = "localhost";
	$user = "root";
	$password = "";
	$database = "calendar";

	$connection = mysqli_connect($server, $user, $password, $database) or die ("Connection to the database has failed");
	mysqli_set_charset($connection, 'utf8');
	return $connection;
}



/*
function returnConnection(){
	$server = "localhost";
	$user = "root";
	$password = "";
	$database = "calendar";

	// Intenta establecer la conexión
	$connection = @mysqli_connect($server, $user, $password, $database);

	// Verifica si la conexión fue exitosa
	if (!$connection) {
	    die("Connection to the database has failed: " . mysqli_connect_error());
	}

	// Configura la codificación de caracteres
	mysqli_set_charset($connection, 'utf8');

	return $connection;
}*/
