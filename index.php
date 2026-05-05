<?php
include "accesso.php"
session_start();
$conn = new mysqli("localhost", "root", "", "roncelli_gym");
if ($conn->connect_error) {
    die("Errore connessione");
}

if(isset($_POST['azione']) && $_POST['azione'] == 'login'){
    $user = $_POST['user'];
    $password = $_POST['password'];

    $res = $conn->query("SELECT * FROM membri WHERE password = '$password'");

    if($res->num_rows > 0){
        $user = $res->fetch_assoc();

        if(password_verify($password, $user['password'])){
            $_SESSION['user'] = $user['user'];
        }
    }
}

if(isset($_POST['aggiungi_iscritto'])){
    $id_membro=$POST['membro'];
    $id_corso=$_POST['corso'];
    $conn->query("INSERT INTO iscrizioni_corsi(membro,corso) VALUES ('$id_membro', $id_corso")
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Palestra</title>
</head>
<body>
    <h2>Aggiungi iscritto</h2>
    <form method="POST">
        <?php
        $id_membro:<input type="text" name="membro" required>
        $id_corso:$conn-> query("SELECT * from membri");
        while($a=$membri->fetch_assoc()){
            echo <option value="'$a['id']}>($a['id_membro')}"</option>
        }
    ?>

    <h2>Visualizza corso con più iscritti</h2>
    <button name="visualizza corso">Visualizza</button>

</body>
</html>




