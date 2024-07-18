<?php

    $limitedClients = getLimitedClients();

    // Add client
    if (isset($_POST['clientName'])) {
        $clientData = [
            "nom" => $_POST["clientName"],
            "prenom" => $_POST["lastName"],
            "email" => $_POST["email"],
            "telephone" => $_POST["phone"],
            "adresse" => $_POST["address"]
        ];
        insertData($cnx, "client", $clientData, "Le client a été ajouté avec succès !", "Une erreur s'est produite lors de l'ajout du client");

    }

    // Edit client
    if (isset($_POST['editClientRequest'])) {
        $clientId = $_POST['clientId'];
        $request = filterData($_POST);

        $data = [
            'nom' => $request['nom'],
            'prenom' => $request['prenom'],
            'email' => $request['email'],
            'telephone' => $request['phone'],
            'adresse' => $request['address'],
            'date_creation' => $request['creationDate']
        ];

        $condition = [
            'column' => 'id',
            'value' => $clientId
        ];

        editData('client', $data, $condition, "Le client a été Modifié avec succès !", "Une erreur s'est produite lors de la modification du client");
    }

    // Delete client
    if (isset($_POST['deleteClient'])) {
        $clientId = intval($_POST['clientId']);
        deleteData(
            'client',
            $clientId, 
            "Le client a été supprimé avec succès!", 
            "Aucun client trouvé avec l'ID donné!"
        );
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

    // Search client
    function searchFunction() {
        if (isset($_POST['searchClient'])) {
            global $cnx;
            $filtredData = filterData($_POST);

            if (!isset($filtredData['inputValue']) || empty($filtredData['inputValue'])) {
                return [];
            }

            $inputValueLike = '%' . $filtredData['inputValue'] . '%';

            $query = "SELECT COUNT(vente.id) AS `totalSales`,
                            client.id as `client_id`,
                            client.nom, 
                            client.prenom, 
                            client.email,
                            client.telephone,
                            client.adresse, 
                            client.date_creation
                    FROM client
                    LEFT JOIN vente ON vente.id_client = client.id
                    WHERE 
                            client.nom LIKE :inputValueLike OR 
                            client.prenom LIKE :inputValueLike OR 
                            client.email LIKE :inputValueLike OR 
                            client.telephone LIKE :inputValueLike OR 
                            client.adresse LIKE :inputValueLike OR 
                            client.date_creation LIKE :inputValueLike
                    GROUP BY client.id, client.nom, client.prenom, client.email, 
                            client.telephone, client.adresse, client.date_creation";

            $request = $cnx->prepare($query);
            $request->bindParam(':inputValueLike', $inputValueLike, PDO::PARAM_STR);

            $request->execute();

            $records = $request->fetchAll(PDO::FETCH_ASSOC);
            return $records;
        }
        return [];
    }

    function getClientSales($clientId, $limit, $offset) {
        global $cnx;
        try {
            $query = "SELECT article.nom_article, vente.quantite, vente.prix, vente.date_vente, vente.id as `sale_id`
                      FROM article
                      INNER JOIN vente ON vente.id_article = article.id
                      INNER JOIN client ON vente.id_client = client.id
                      WHERE client.id = :id
                      LIMIT :limit OFFSET :offset";
            $request = $cnx->prepare($query);
            $request->bindParam(':id', $clientId, PDO::PARAM_INT);
            $request->bindValue(':limit', $limit, PDO::PARAM_INT);
            $request->bindValue(':offset', $offset, PDO::PARAM_INT);
            $request->execute();
            $records = $request->fetchAll(PDO::FETCH_ASSOC);
            return $records ?: [];
        } catch (Exception $e) {
            return [];
        }
    }


    function getAllClients($limit, $offset) {
        $query = "SELECT COUNT(vente.id) AS `totalSales`,
                client.id as `client_id`,
                client.nom, 
                client.prenom, 
                client.email,
                client.telephone, 
                client.adresse, 
                client.date_creation
                FROM client
                LEFT JOIN vente ON vente.id_client = client.id
                GROUP BY client.id, client.nom, client.prenom, client.email, client.telephone, client.adresse, client.date_creation
                ORDER BY `totalSales` DESC
                LIMIT :limit OFFSET :offset";
        $request = $GLOBALS['cnx']->prepare($query);
        $request->bindValue(':limit', $limit, PDO::PARAM_INT);
        $request->bindValue(':offset', $offset, PDO::PARAM_INT);
        $request->execute();
        return $request->fetchAll();
    }
    
    
    function getTotalClientsCount() {
        $query = "SELECT COUNT(*) as total FROM client";
        $request = $GLOBALS['cnx']->prepare($query);
        $request->execute();
        return $request->fetch()['total'];
    }

    $limit = 40;

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $clients = getAllClients($limit, $offset);

    $totalClients = getTotalClientsCount();
    $totalPages = ceil($totalClients / $limit);



    // For client sales pagination
    function getClientSalesCount($clientId) {
        $query = "SELECT COUNT(*) as total FROM vente WHERE id_client = :id";
        $request = $GLOBALS['cnx']->prepare($query);
        $request->bindValue(':id', $clientId, PDO::PARAM_INT);
        $request->execute();
        return $request->fetch()['total'];
    }


    // Count clients
    $countClients = countDataTable('client');


?>