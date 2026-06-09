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

        <table style="border-collapse: collapse; width:100%; background:white; border: 1px solid #ccc;">
            <tr style="background:#2c3e50; color:white;">
                <th style="padding: 10px; border: 1px solid #ccc;">ID Ordine</th>
                <th style="padding: 10px; border: 1px solid #ccc;">Pacchetto Acquistato</th>
                <th style="padding: 10px; border: 1px solid #ccc;">Prezzo</th>
                <th style="padding: 10px; border: 1px solid #ccc;">Azione</th>
            </tr>
            <?php
            $uid = $_SESSION['user_id'];
            $file_xml = 'acquisti.xml';
            $acquisti_trovati = false;

            if (file_exists($file_xml)) {
                $dom = new DOMDocument();
                $dom->load($file_xml);
                $lista_acquisti = $dom->getElementsByTagName('acquisto');

                foreach ($lista_acquisti as $acquisto) {
                    $id_cliente_xml = $acquisto->getElementsByTagName('id_cliente')->item(0)->nodeValue;

                    if ($id_cliente_xml == $uid) {
                        $acquisti_trovati = true;

                        $id_acq = $acquisto->getElementsByTagName('id_acquisto')->item(0)->nodeValue;
                        $nome_p = $acquisto->getElementsByTagName('nome_pacchetto')->item(0)->nodeValue;
                        $prezzo = $acquisto->getElementsByTagName('prezzo')->item(0)->nodeValue;

                        echo "<tr>";
                        echo "<td style='padding: 10px; border: 1px solid #ccc;'>#" . htmlspecialchars($id_acq) . "</td>";
                        echo "<td style='padding: 10px; border: 1px solid #ccc;'>" . htmlspecialchars($nome_p) . "</td>";
                        echo "<td style='padding: 10px; border: 1px solid #ccc;'>€ " . htmlspecialchars($prezzo) . "</td>";
                        echo "<td style='padding: 10px; border: 1px solid #ccc; text-align:center;'>
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
                echo "<tr><td colspan='4' style='padding: 10px; border: 1px solid #ccc; text-align:center;'>Nessun acquisto effettuato.</td></tr>";
            }
            ?>
        </table>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>
