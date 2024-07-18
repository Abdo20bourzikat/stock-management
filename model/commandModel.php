<?php

    // Add provider
    if (isset($_POST['addCommandRequest'])) {
        $commandData = [
            "id_article" => $_POST["article"],
            "id_fournisseur" => $_POST["provider"],
            "quantite" => $_POST["quantity"],
            "prix" => $_POST["price"],
            "date_commande" => $_POST["commandDate"]
        ];
        insertData($cnx, "commande", $commandData, "La commande a été ajouté avec succès !", "Une erreur s'est produite lors de l'ajout du commande");

        // Update quantity of article
        $query = "UPDATE `article` SET quantite = (quantite + :quantite) WHERE id = :id_article";
        $request = $cnx->prepare($query);
        $request->bindParam(':quantite', $commandData['quantite'], PDO::PARAM_INT);
        $request->bindParam(':id_article', $commandData['id_article'], PDO::PARAM_INT);
        $request->execute();

    }


     // Update command
    if (isset($_POST['editCommandRequest'])) {
        $commandId = $_POST['commandId'];
        $request = filterData($_POST);

        $data = [
            'id_article' => $request['article'],
            'id_fournisseur' => $request['provider'],
            'quantite' => $request['quantity'],
            'prix' => $request['price'],
            'date_commande' => $request['commandDate'],
        ];

        $condition = [
            'column' => 'id',
            'value' => $commandId
        ];

        // // Edit the article quantity
        // $mainCommand = getDataById('commande', $commandId);

        // if ($mainCommand['quantite'] != $request['quantity']) {
        //     if ($request['quantity'] > $mainCommand['quantite']) {
        //         $newQuantity = $request['quantity'] - $mainCommand['quantite'];
        //         $query = "UPDATE `article` SET quantite = (quantite + :quantite) WHERE id = :id_article";

        //     } 
        // else if ($request['quantity'] < $mainCommand['quantite']) {
        //         $newQuantity = $mainCommand['quantite'] - $request['quantity'];
        //         $query = "UPDATE `article` SET quantite = (quantite + :quantite) WHERE id = :id_article";
        //     }
        //     $request = $cnx->prepare($query);
        //     $request->bindParam(':quantite', $newQuantity, PDO::PARAM_INT);
        //     $request->bindParam(':id_article', $data['id_article'], PDO::PARAM_INT);
        //     $request->execute();
        // }


        editData('commande', $data, $condition, "La commande a été Modifié avec succès !", "Une erreur s'est produite lors de la modification de la commande !");

    }

    // Delete commande
    if (isset($_POST['deleteCommand'])) {
        $commandId = intval($_POST['commandId']);
        deleteData(
            'commande',
            $commandId, 
            "La commande a été supprimé avec succès !", 
            "Aucun commande trouvé avec l'ID donné !"
        );
    }

    // Search commands
    function searchCommands() {
        if (isset($_POST['searchCommand'])) {
            global $cnx;
            $filteredData = filterData($_POST);

            if (!isset($filteredData['searchCommandValue']) || empty($filteredData['searchCommandValue'])) {
                return [];
            }

            $inputValueLike = '%' . $filteredData['searchCommandValue'] . '%';

            $query = "SELECT 
                        commande.id, 
                        commande.quantite,
                        commande.prix,
                        commande.date_commande,
                        article.nom_article,
                        CONCAT(fournisseur.nom, ' ', fournisseur.prenom) as `provider`
                    FROM commande
                    INNER JOIN article ON article.id = commande.id_article
                    INNER JOIN fournisseur ON fournisseur.id = commande.id_fournisseur
                    WHERE commande.quantite LIKE :inputValueLike OR 
                            commande.prix LIKE :inputValueLike OR 
                            commande.date_commande LIKE :inputValueLike OR 
                            article.nom_article LIKE :inputValueLike OR 
                            CONCAT(fournisseur.nom, ' ', fournisseur.prenom) LIKE :inputValueLike
                    ORDER BY commande.id DESC";

            $request = $cnx->prepare($query);
            $request->bindParam(':inputValueLike', $inputValueLike, PDO::PARAM_STR);

            $request->execute();

            $records = $request->fetchAll(PDO::FETCH_ASSOC);
            return $records;
        }
        return [];
    }


    function getCommands($limit = null, $offset = null) {
        global $cnx;
        try {
            $query = "SELECT 
                commande.id, 
                commande.quantite,
                commande.prix,
                commande.date_commande,
                article.nom_article,
                CONCAT(fournisseur.nom, ' ', fournisseur.prenom) as `provider`
                FROM commande
                INNER JOIN article ON article.id = commande.id_article
                INNER JOIN fournisseur ON fournisseur.id = commande.id_fournisseur
                ORDER BY commande.id DESC";
            
            if ($limit != null || $offset != null) {
                $query .= " LIMIT :limit OFFSET :offset";
            }
    
            $request = $cnx->prepare($query);
            
            if ($limit != null || $offset != null) {
                $request->bindParam(':limit', $limit, PDO::PARAM_INT);
                $request->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            
            $request->execute();
            
            $records = $request->fetchAll(PDO::FETCH_ASSOC);
            return $records ?: [];
        } catch (Exception $e) {
            return [];
        }
    }


    $limit = 40;

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $commands = getCommands($limit, $offset);

    $totalCommands = countDataTable('commande');
    $totalPages = ceil($totalCommands / $limit);

    // Count commands
    $countCommands = countDataTable('commande');

?>