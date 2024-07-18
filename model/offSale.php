<?php
    include 'services.php';

    if (
        !empty($_GET['idVente']) &&
        !empty($_GET['idArticle']) &&
        !empty($_GET['quantite'])
    ) {
        $query = "UPDATE `vente` SET etat=? WHERE id=? ";
        $request = $cnx->prepare($query);
        $request->execute(array(0, $_GET['idVente']));
        
        if ($request->rowCount() != 0) {
            $sql = "UPDATE `article` SET quantite = quantite +? WHERE id=? ";
            $req = $cnx->prepare($sql);
            $req->execute(array($_GET['quantite'], $_GET['idArticle']));
            $_SESSION['success'] = "La vente a été désactivée avec succès !";
        }
    }
    header('Location: ../view/vente/allVentes.php');

?>