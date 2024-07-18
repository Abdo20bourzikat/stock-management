<?php

    $allArticles = getData('article');
    $allClients = getData('client');

    // Add sale
    if (isset($_POST['addVenteRequest'])) {
        $venteData = [
            "id_article" => $_POST["article"],
            "id_client" => $_POST["client"],
            "quantite" => $_POST["quantity"],
            "prix" => $_POST["price"]
        ];

        $checkQuantity = $cnx->prepare("SELECT COUNT(*) as `quantity` FROM article WHERE id = :id AND quantite < :quantite ");
        $checkQuantity->bindParam(':id', $venteData['id_article'], PDO::PARAM_INT);
        $checkQuantity->bindParam(':quantite', $venteData['quantite'], PDO::PARAM_INT);
        $checkQuantity->execute();
        $record = $checkQuantity->fetch(PDO::FETCH_ASSOC);

        if ($record['quantity'] > 0) {
            $_SESSION['error'] = "La quantité vendue dépasse la quantité en stock !";
        } else {
            insertData($cnx, "vente", $venteData, "La vente a été ajouté avec succès!", "Une erreur s'est produite lors de l'ajout du vente");
    
            // Update quantity of article
            $query = "UPDATE `article` SET quantite = (quantite - :quantite) WHERE id = :id_article";
            $request = $cnx->prepare($query);
            $request->bindParam(':quantite', $venteData['quantite'], PDO::PARAM_INT);
            $request->bindParam(':id_article', $venteData['id_article'], PDO::PARAM_INT);
            $request->execute();
        }


    }

    // Update sale
    if (isset($_POST['editVenteRequest'])) {
        $saleId = $_POST['sale_id'];
        $request = filterData($_POST);

        $data = [
            'id_article' => $request['article'],
            'id_client' => $request['client'],
            'quantite' => $request['quantity'],
            'prix' => $request['price'],
            'date_vente' => $request['saleDate'],
        ];

        $condition = [
            'column' => 'id',
            'value' => $saleId
        ];

        // Edit the article quantity
        $mainVente = getDataById('vente', $saleId);

        if ($mainVente['quantite'] != $request['quantity']) {
            if ($request['quantity'] > $mainVente['quantite']) {
                $newQuantity = $request['quantity'] - $mainVente['quantite'];
                $query = "UPDATE `article` SET quantite = (quantite - :quantite) WHERE id = :id_article";
            } else if ($request['quantity'] < $mainVente['quantite']) {
                $newQuantity = $mainVente['quantite'] - $request['quantity'];
                $query = "UPDATE `article` SET quantite = (quantite + :quantite) WHERE id = :id_article";
            }
            $request = $cnx->prepare($query);
            $request->bindParam(':quantite', $newQuantity, PDO::PARAM_INT);
            $request->bindParam(':id_article', $data['id_article'], PDO::PARAM_INT);
            $request->execute();
        }


        editData('vente', $data, $condition, "La vente a été Modifié avec succès !", "Une erreur s'est produite lors de la modification de la vente !");

    }

    // Delete sale
    if (isset($_POST['deleteSale'])) {
        $saleId = intval($_POST['saleId']);
        deleteData(
            'vente',
            $saleId, 
            "La vente a été supprimé avec succès !", 
            "Aucun vente trouvé avec l'ID donné !"
        );
    }


    // Search sales
    function searchSales() {
        if (isset($_POST['searchSale'])) {
            global $cnx;
            $filteredData = filterData($_POST);

            if (!isset($filteredData['saleSearchValue']) || empty($filteredData['saleSearchValue'])) {
                return [];
            }

            $inputValueLike = '%' . $filteredData['saleSearchValue'] . '%';

            $query = "SELECT vente.id as `id`, 
                            vente.quantite as `quantity`, 
                            vente.prix as `price`, 
                            vente.date_vente as `saleDate`, 
                            vente.etat,
                            article.id as `idArticle`, 
                            article.nom_article as `article`, 
                            client.id as `idClient`, 
                            CONCAT(client.nom, ' ', client.prenom) as `clientName`
                    FROM vente
                    JOIN article ON vente.id_article = article.id
                    JOIN client ON vente.id_client = client.id
                    WHERE vente.quantite LIKE :inputValueLike OR 
                            vente.prix LIKE :inputValueLike OR 
                            vente.date_vente LIKE :inputValueLike OR 
                            vente.etat LIKE :inputValueLike OR 
                            article.nom_article LIKE :inputValueLike OR 
                            CONCAT(client.nom, ' ', client.prenom) LIKE :inputValueLike
                    ORDER BY vente.date_vente DESC";

            $request = $cnx->prepare($query);
            $request->bindParam(':inputValueLike', $inputValueLike, PDO::PARAM_STR);

            $request->execute();

            $records = $request->fetchAll(PDO::FETCH_ASSOC);
            return $records;
        }
        return [];
    }




    // Get limited sales
    $limitedSales = getSales(6);
    
    function getTotalSaleCount() {
        $query = "SELECT COUNT(*) as total FROM vente";
        $request = $GLOBALS['cnx']->prepare($query);
        $request->execute();
        return $request->fetch()['total'];
    }

    $limit = 40;

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $allSales = getSales($limit, $offset);

    $totalSales = getTotalSaleCount();
    $totalPages = ceil($totalSales / $limit);

    $countQuery = "SELECT COUNT(*) FROM vente WHERE etat='1'";
    $countSales = countSalesFunction();

    // Active sales
    if (isset($_POST['activeSale'])) {
        global $cnx;
        $saleId = $_POST['saleId'];
        $query = "UPDATE `vente` SET etat = '1' WHERE id = :id ";
        $request = $cnx->prepare($query);
        $request->bindParam(':id', $saleId, PDO::PARAM_INT);
        $request->execute();
        if ($request->rowCount() > 0) {
            $_SESSION['success'] = "La vente a été avtivée avec succès !"; 
        } else {
            $_SESSION['error'] = "Une erreur s'est produite lors de l'activation de la vente !";
        }
    }

    function getSaleOffCount() {
        $query = "SELECT COUNT(*) as total FROM vente WHERE etat='0'";
        $request = $GLOBALS['cnx']->prepare($query);
        $request->execute();
        return $request->fetch()['total'];
    }
    $countSalesOff = getSaleOffCount();
    
    $salesOff = getSales($limit, $offset, '0');

?>