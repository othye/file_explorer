<?php

    $doc = !empty($_GET['dir']) ? trim($_GET['dir']) : '';

    if(!empty($doc) && file_exists($doc)) {

        $mime = 'application/octet-stream';
        $taille = filesize($doc);

        // Téléchargement du fichier
        header("Content-disposition: attachment; filename=\"$doc\"");
        header("Content-Type: $mime");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: $taille");
        header("Pragma: no-cache");
        header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
        header("Expires: 0");
        readfile($doc);
    }

?>