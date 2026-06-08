<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['id_da_eliminare'])) {
    $id_bersaglio = $_POST['id_da_eliminare'];
    $file_xml = 'acquisti.xml';

    if (file_exists($file_xml)) {
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->load($file_xml);

        $lista_acquisti = $dom->getElementsByTagName('acquisto');

        // Scorro tutti i nodi <acquisto> alla ricerca di quello con id_acquisto uguale a $id_bersaglio
        foreach ($lista_acquisti as $acquisto) {
            $id_corrente = $acquisto->getElementsByTagName('id_acquisto')->item(0)->nodeValue;

            // Se trovo il nodo da eliminare, lo rimuovo dall'albero e salvo il documento XML
            if ($id_corrente == $id_bersaglio) {
                $acquisto->parentNode->removeChild($acquisto);

                // Dopo la rimozione, salvo il documento XML per rendere effettiva la modifica
                $dom->save($file_xml);
                break;
            }
        }
    }
}

header("Location: area_personale.php?msg=Eliminato");
exit;
