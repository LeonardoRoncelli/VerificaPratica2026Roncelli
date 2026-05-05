<?php
session_start();

$host = "localhost";
$db = "palestra";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("Errore DB");
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM iscritti WHERE cognome = ?");
    $stmt->execute([$username]);
    $userDB = $stmt->fetch();

    if ($userDB && $password === "verifica") {
        $_SESSION['login'] = true;
    } else {
        $errore = "Credenziali errate";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
}

if (isset($_POST['inserisci'])) {
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $corso = $_POST['corso'];

    $stmt = $pdo->prepare("INSERT INTO iscritti(nome,cognome,id_corso) VALUES(?,?,?)");
    $stmt->execute([$nome, $cognome, $corso]);
}

if (isset($_POST['cambia'])) {
    $id = $_POST['id_iscritto'];
    $nuovo = $_POST['nuovo_corso'];

    $stmt = $pdo->prepare("UPDATE iscritti SET id_corso=? WHERE id=?");
    $stmt->execute([$nuovo, $id]);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestione Corsi</title>
</head>
<body>

<?php if (!isset($_SESSION['login'])): ?>

<h2>Login</h2>
<form method="POST">
    Username (cognome): <input type="text" name="username"><br>
    Password: <input type="password" name="password"><br>
    <button name="login">Login</button>
</form>
<?php if (isset($errore)) echo $errore; ?>

<?php else: ?>

<a href="?logout=1">Logout</a>

<h2>Inserisci nuovo iscritto</h2>
<form method="POST">
    Nome: <input type="text" name="nome"><br>
    Cognome: <input type="text" name="cognome"><br>

    Istruttore:
    <select name="istruttore" id="istruttore">
        <?php
        $istr = $pdo->query("SELECT * FROM istruttori");
        foreach ($istr as $i) {
            echo "<option value='{$i['id']}'>{$i['nome']} {$i['cognome']}</option>";
        }
        ?>
    </select><br>

    Corso:
    <select name="corso">
        <?php
        $corsi = $pdo->query("SELECT * FROM corsi");
        foreach ($corsi as $c) {
            echo "<option value='{$c['id']}'>{$c['nome']}</option>";
        }
        ?>
    </select><br>

    <button name="inserisci">Inserisci</button>
</form>

<h2>Corso con più iscritti per istruttore</h2>
<?php
$query = "
SELECT i.nome, i.cognome, c.nome AS corso, COUNT(iscritti.id) AS totale
FROM istruttori i
JOIN corsi c ON i.id = c.id_istruttore
LEFT JOIN iscritti ON c.id = iscritti.id_corso
GROUP BY c.id
HAVING totale = (
    SELECT MAX(cnt) FROM (
        SELECT COUNT(*) cnt
        FROM iscritti is2
        JOIN corsi c2 ON is2.id_corso = c2.id
        WHERE c2.id_istruttore = i.id
        GROUP BY c2.id
    ) t
)
";

$res = $pdo->query($query);
foreach ($res as $r) {
    echo "{$r['nome']} {$r['cognome']} → {$r['corso']} ({$r['totale']} iscritti)<br>";
}
?>