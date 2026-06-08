<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Area Personale</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include 'topbar.php';
    include 'menu.php'; ?>

    <div id="content">
        <h1>Storico Acquisti di <?php echo htmlspecialchars($_SESSION['user_nome']); ?></h1>

        <?php
        if (isset($_GET['msg']) && $_GET['msg'] == 'AcquistoCompletato') echo "<p style='color:green; font-weight:bold;'>Acquisto completato con successo!</p>";
        if (isset($_GET['msg']) && $_GET['msg'] == 'Eliminato') echo "<p style='color:red; font-weight:bold;'>Acquisto annullato.</p>";
        ?>

        <table border="1" cellpadding="10" style="border-collapse: collapse; width:100%; background:white;">
            <tr style="background:#2c3e50; color:white;">
                <th>ID Ordine</th>
                <th>Pacchetto Acquistato</th>
                <th>Prezzo</th>
                <th>Azione</th>
            </tr>
            <?php
            $uid = $_SESSION['user_id'];
            $file_xml = 'acquisti.xml';
            $acquisti_trovati = false;

            if (file_exists($file_xml)) {
                $dom = new DOMDocument();
                $dom->load($file_xml);  // Carico l'albero in memoria

                // Ottengo la lista di tutti i nodi <acquisto> presenti nel documento
                $lista_acquisti = $dom->getElementsByTagName('acquisto');

                foreach ($lista_acquisti as $acquisto) {
                    // Per ogni nodo <acquisto>, leggo il valore del figlio <id_cliente>
                    $id_cliente_xml = $acquisto->getElementsByTagName('id_cliente')->item(0)->nodeValue;

                    // Se l'id_cliente del nodo XML corrisponde all'id dell'utente loggato, visualizzo i dettagli dell'acquisto
                    if ($id_cliente_xml == $uid) {
                        $acquisti_trovati = true;

                        // Leggo i valori degli altri figli per mostrarli nella tabella
                        $id_acq = $acquisto->getElementsByTagName('id_acquisto')->item(0)->nodeValue;
                        $nome_p = $acquisto->getElementsByTagName('nome_pacchetto')->item(0)->nodeValue;
                        $prezzo = $acquisto->getElementsByTagName('prezzo')->item(0)->nodeValue;

                        echo "<tr>";
                        echo "<td>#" . htmlspecialchars($id_acq) . "</td>";
                        echo "<td>" . htmlspecialchars($nome_p) . "</td>";
                        echo "<td>€ " . htmlspecialchars($prezzo) . "</td>";
                        echo "<td>
                                <form action='elimina_acquisto.php' method='post'>
                                    <input type='hidden' name='id_da_eliminare' value='$id_acq'>
                                    <input type='submit' value='Annulla Ordine' style='background-color:#e74c3c; color:white; padding:5px; border:none; cursor:pointer;'>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                }
            }

            if (!$acquisti_trovati) {
                echo "<tr><td colspan='4' style='text-align:center;'>Nessun acquisto effettuato.</td></tr>";
            }
            ?>
        </table>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>