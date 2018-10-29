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
$result = pg_query($query);
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
    }
    while ($row = pg_fetch_row($result)) {
      echo $row[2];
      echo "<br />\n";
    }
    ?>
    </p>

    <p> Acá va el query </p>
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
