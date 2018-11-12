<!DOCTYPE html>
<html>
<head>
    <title>TP2 - Query</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="img/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link type='text/css' href='style.css' rel='stylesheet' />

</head>

<?php
$db = pg_connect("host=localhost port=5434 dbname=tp2 user=postgres password=postgres")
or die('No se ha podido conectar.');

$query = "SELECT * FROM persona WHERE usuario = '$_POST[usuario]' AND clave = md5('$_POST[password]')";

$result = pg_query($query) or die('Error: ' . pg_last_error());
?>

<body>

<div class="login-page">
<div class="form">
    
    <form class="login-form">

    <p class="message">
    <?php
    
    if(pg_num_rows($result) != 1) {
        echo ("Usuario o password incorrectos");
    } else {
	    echo ("Bienvenido/a, ");
    
	    while ($row = pg_fetch_row($result)) {
	      echo $row[2];
	      echo "<br />\n";
            }
	    
            echo ("<br> Estas son las personas que vieron las mismas películas: <br>");

	    $query = "SELECT per1.Nombre, per2.Nombre
			FROM persona per1
			JOIN persona per2
			 ON per1.id < per2.id
			WHERE 1=1 
			AND NOT EXISTS (SELECT p3.pelicula_id 
					  FROM pelis_que_vio p3 
					  WHERE p3.usuario_id = per1.id 
					    OR p3.usuario_id = per2.id
					EXCEPT (
						SELECT t1.pelicula_id 
						FROM
						     (SELECT p1.pelicula_id 
						      FROM pelis_que_vio p1 
						     WHERE p1.usuario_id = per1.id) t1
						JOIN (SELECT p2.pelicula_id 
							FROM pelis_que_vio p2 
							WHERE p2.usuario_id = per2.id) t2
						 ON t1.pelicula_id = t2.pelicula_id
						)
		  );";			
	
	    $result = pg_query($query);


		echo "<table>\n";
		while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
			echo "\t<tr>\n";
			foreach ($line as $col_value) {
				echo "\t\t<td>$col_value</td>\n";
			}
			echo "\t</tr>\n";
		}
		echo "</table>\n";	   
   }
   ?>
    
    <p class="message"><a href="login.html">Volver</a></p>
    </form>

</div>
</div>

</body>


<?php
// Liberando el conjunto de resultados
pg_free_result($result);
// Cerrando la conexión
pg_close($dbc);
?>

</html>
