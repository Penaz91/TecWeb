<?php
        // Importa i toolkit per poter usare le funzioni di costruzione pagina
        require_once __DIR__ . DIRECTORY_SEPARATOR . "toolkit.php";

        // Avvio della sessione, per poter usare le superglobali $_SESSION[] e ricordare i dati dell'utente
        session_start();
        // Importa la struttura generale del sito, differenziato per inglese ed italiano
        $content = file_get_contents(__("struttura.html"));

        /* Setta il Tag <Title> del sito (Interno alla <head>)
         * @param $content: La variabile contenente la struttura del sito
         * @param $title: La stringa contenenete il titolo della pagina.
         * In questo esempio il titolo visualizzerà: "Home - Audiogram Lab"
         * Differenziato per lingua inglese ed italiana
         */
        if ($_SESSION['language']=='en'){
                //Lingua Inglese
                setTitle($content, "Home");
        }else{
                //Italiano
                setTitle($content, "Home");
        }
        /* Inizializza le breadcrumb
         * @param $content: La variabile contenente il codice del sito
         * @param $linktext: Il Testo che sarà visualizzato nell'ancora
         * @param $linkanchor: La pagina a cui l'ancora porterà, se vuota l'ancora sarà sostituita con del testo normale
         */
        initBreadcrumbs($content, "Home", "index.php");
        /* Aggiungi un elemento alle breadcrumb
         * @param $content: La variabile contenente il codice del sito
         * @param $linktext: Il Testo che sarà visualizzato nell'ancora
         * @param $linkanchor: La pagina a cui l'ancora porterà, se vuota l'ancora sarà sostituita con del testo normale
         * Questo comando può essere richiamato più volte di fila per aggiungere ulteriori breadcrumbs, le freccette ">>" saranno aggiunte automaticamente
         * Differenziato per Inglese/Italiano
         */
        if ($_SESSION['language']=='en'){
                addBreadcrumb($content, "Test Template", "template.php");
        }else{
                addBreadcrumb($content, "Template Di Prova", "template.php");
        }
        /* Setta la parte del menu dedicata allo stato dell'utente (loggato o meno)
         * @param $content: La variabile contenente il codice del sito
         */
        setUserStatus($content);
        /* Setta la parte del menu dedicata alla navigazione
         * @param $content: La variabile contenente il codice del sito
         * @param $menuid: Il codice numerico identificativo della voce di menu da evidenziare e disattivare
         * -1 - Tutte le voci di menu sono attive
         * 0 - Blocca ed evidenzia "Home"
         * 1 - Blocca ed evidenzia "Cosa Offriamo"
         * 2 - Blocca ed evidenzia "Noleggio Strumentazione"
         * 3 - Blocca ed evidenzia "Prenotazione Sale"
         * 4 - Blocca ed evidenzia "Contattaci"
         */
        setupMenu($content, 0);
        /* Setta la parte del menu dedicata all'area amministrazione
         * @param $content: La variabile contenente il codice del sito
         */
        setAdminArea($content);
        /* Imposta l'area di cambio Lingua*/
        setLangArea($content, $_SERVER['PHP_SELF']);
        /* Importa il contenuto centrale della pagina da un file esterno
         * @param $content: La variabile contenente il codice del sito
         * @param $filename: Stringa contenente il nome del file da cui caricare il contenuto
         */
        setContentFromFile($content, __("contenuto_home.html"));
        // Visualizza la pagina generata
        echo($content);
?>
