# Benelli.Alessandro.XML-DOM

PROGETTO: Applicazione Web Dinamica "Palestra 648" (Integrazione XML/DOM)
CORSO: Sviluppo Web - Homework XML con DOM

NOME DELL'AUTORE E COMPONENTI:
- Alessandro Benelli (Matricola: 1983399)

INDIRIZZO REPOSITORY GITHUB:
- https://github.com/Tiltaments/Benelli.Alessandro.XML-DOM.git

DESCRIZIONE DELL'APPLICAZIONE:
L'applicazione è l'estensione finale del precedente Homework (Palestra 648). Seguendo le indicazioni, il sistema di e-commerce è stato disaccoppiato da MySQL.
Ora, l'acquisto degli abbonamenti, lo storico personale e la cancellazione degli ordini sono gestiti interamente su file "acquisti.xml". Il database MySQL, è stato ridotto al minimo, gestendo solo l'anagrafica essenziale (utenti per l'autenticazione e istruttori).

PRINCIPI UTILIZZATI E SOLUZIONI ADOTTATE:
1. Validazione Strutturale: Per la grammatica dell'XML ho deciso di adottare sia il DTD (incorporato direttamente nel file "acquisti.xml") sia un XML Schema esterno ("acquisti.xsd"), garantendo così la massima solidità formale dei dati immessi.
2. Interazione DOM in PHP:
   - Inserimento: In "checkout.php" ho utilizzato i metodi createElement() e appendChild() per generare e inserire la nuova struttura di nodi al momento dell'acquisto.
   - Parsing: In "area_personale.php" ho sfruttato getElementsByTagName() per iterare ciclicamente i nodi e prelevare i nodeValue, filtrando gli acquisti in base all'ID dell'utente loggato in sessione.
   - Eliminazione: In "elimina_acquisto.php" il nodo bersaglio viene individuato e rimosso tramite la chiamata "$nodo->parentNode->removeChild($nodo)".

DIFFICOLTÀ INCONTRATE E RISOLTE:
- Gestione degli spazi bianchi (Whitespace Nodes): Durante la lettura e manipolazione dell'XML con DOM, i ritorni a capo e le formattazioni del file venivano inizialmente letti come "TextNodes" vuoti, sballando l'albero e creando difficoltà nell'eliminazione.
- Soluzione adottata: Ho impostato "$dom->preserveWhiteSpace = false;" e "$dom->formatOutput = true;" in fase di istanziazione dell'oggetto DOMDocument in PHP. Questo ha risolto definitivamente il problema e mantenuto l'output XML pulito e indentato.

ISTRUZIONI PER IL WEB SERVER E DISTRIBUZIONE FILE:
- L'applicativo utilizza solo percorsi relativi, quindi può essere installato in una directory a scelta del web server.
- Lanciare prima "install.php" per creare l'anagrafica DB ridotta.

ORGANIZZAZZIONE DEL LAVORO
Il progetto è diviso in 4 macro-gruppi che oltre a semplificare la fase di testing e debugging, rende il progetto più ottimizzato.

- CONFIGURAZIONE
   - dati_generali.php: È il "libretto di istruzioni" per il database. Nome del DB (benelli.alessandro.XML-DOM) e i nomi delle due tabelle (clienti e istruttori) sono salvati in questo file.
   - install.php: È il file di installazione del DB su MySQL.
   - connection.php: Usa i dati di dati_generali.php per stabilire la connessione con MySQL. Tutti i file che devono parlare col database "includono" questo file all'inizio.
   - acquisti.xml & acquisti.xsd: Sono il tuo "magazzino" degli ordini. L'XML è dove vengono materialmente scritti i dati. Il DTD (scritto in cima all'XML) e il file XSD sono le regole grammaticali per impedire che vengano inseriti tag inventati.

- XML e DOM
   - carrello.php: Mostra semplicemente cosa c'è dentro la variabile $_SESSION['carrello']. Non salva niente su file, è solo una memoria temporanea.
   - checkout.php: Quando si preme "Acquista", lui apre acquisti.xml usando la libreria DOMDocument. Prende i dati dal carrello, crea i nuovi "nodi" (usando createElement) e li incolla dentro l'albero XML (usando appendChild). Poi salva il file.
   - area_personale.php: Quando si vuole vedere lo storico, lui apre l'XML, aspira tutti gli acquisti (getElementsByTagName), controlla se l'id_cliente corrisponde, ed estrae i testi (nodeValue) per stamparli in una tabella HTML.
   - elimina_acquisto.php: Riceve l'ID del pacchetto che si vogliono annullare, apre l'XML, cerca quel nodo specifico e lo "stacca" dall'albero chiedendo al nodo padre di rimuoverlo (removeChild). Infine salva le modifiche.

- DATABASE MySQL
   - registrazione.php & reg_action.php: Il primo è il modulo grafico. Il secondo prende i dati scritti dall'utente, usa lo scudo magico $conn->real_escape_string() per pulirli dai tentativi di hackeraggio (SQL Injection), e fa una query INSERT INTO per salvare l'utente nel DB.
   - login.php & login_action.php: Simile a prima, ma fa una query SELECT. Chiede al DB: "Esiste un utente con questa mail e password?". Se sì, prende l'ID dell'utente e lo inseriesce nella $_SESSION, così il sito si ricorda che si è loggati.
   - logout.php: "Distrugge" il contenuto di $_SESSION, facendo appunto il logout dal sito e reiderizzando alla home l'utente.

- PAGINE STATICHE e GRAFICA
   - home.php, servizi.php, pacchetti.php: Sono pagine web classiche. Mostrano testi, prezzi e immagini.
   - trainer.php: Fa una query al DB (SELECT * FROM istruttori) e stampa le immaagini in automatico grazie a un ciclo while per presentare appunto ogni trainer disponibile nella palestra.
   - topbar.php, menu.php, footer.php: Parti di sito "fisse" che vengono inclusi in ogni pagina attraverso la formula <include>. La topbar.php controlla se si è loggati (isset($_SESSION['user_id'])); se sì, mostra "Area Personale", altrimenti mostra "Login".
