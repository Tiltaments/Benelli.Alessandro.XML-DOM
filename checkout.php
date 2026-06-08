<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?errore=DeviLoggarti");
    exit;
}

if (!empty($_SESSION['carrello'])) {
    $id_cliente = $_SESSION['user_id'];
    $file_xml = 'acquisti.xml';

    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;

    if (file_exists($file_xml)) {
        $dom->load($file_xml);
        $root = $dom->documentElement;
    } else {
        die("Errore: Il file acquisti.xml non esiste o non ha i permessi.");
    }

    foreach ($_SESSION['carrello'] as $item) {
        $pacc = $item['nome'];
        $prezzo = $item['prezzo'];
        $id_acquisto = time() . rand(10, 99);

        // Creazione del nodo contenitore (Padre)
        $nodo_acquisto = $dom->createElement('acquisto');

        // Creazione dei nodi foglia (Figli) con i relativi dati testuali
        $nodo_id_acq = $dom->createElement('id_acquisto', $id_acquisto);
        $nodo_id_cli = $dom->createElement('id_cliente', $id_cliente);
        $nodo_nome = $dom->createElement('nome_pacchetto', htmlspecialchars($pacc));
        $nodo_prezzo = $dom->createElement('prezzo', $prezzo);

        // Assemblaggio dell'albero: aggancio le foglie al nodo padre
        $nodo_acquisto->appendChild($nodo_id_acq);
        $nodo_acquisto->appendChild($nodo_id_cli);
        $nodo_acquisto->appendChild($nodo_nome);
        $nodo_acquisto->appendChild($nodo_prezzo);

        // Aggancio il blocco acquisto completo alla radice del documento XML
        $root->appendChild($nodo_acquisto);
    }

    $dom->save($file_xml);
    unset($_SESSION['carrello']);
}

header("Location: area_personale.php?msg=AcquistoCompletato");
exit;
