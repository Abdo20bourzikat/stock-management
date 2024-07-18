<?php

    // Limited providers
    function getLimitedProviders() {
        global $cnx;
        
        try {
            $query = "SELECT * FROM fournisseur
            ORDER BY `id` DESC
            LIMIT 6";
            $stmt = $cnx->prepare($query);
            $stmt->execute();
            
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $records ?: [];
            
        } catch (Exception $e) {
            return [];
        }
    }

    // Add provider
    if (isset($_POST['providerName'])) {
        $providerData = [
            "nom" => $_POST["providerName"],
            "prenom" => $_POST["lastName"],
            "telephone" => $_POST["phone"],
            "email" => $_POST["email"],
            "adresse" => $_POST["address"]
        ];
        insertData($cnx, "fournisseur", $providerData, "Le fournisseur a été ajouté avec succès !", "Une erreur s'est produite lors de l'ajout du fournisseur");

    }


    // Edit provider
    if (isset($_POST['editProviderRequest'])) {
        $providerId = $_POST['providerId'];
        $request = filterData($_POST);

        $data = [
            'nom' => $request['nom'],
            'prenom' => $request['prenom'],
            'telephone' => $request['phone'],
            'email' => $request['email'],
            'adresse' => $request['address'],
            'date_creation' => $request['creationDate']
        ];

        $condition = [
            'column' => 'id',
            'value' => $providerId
        ];

        editData('fournisseur', $data, $condition, "Le fournisseur a été Modifié avec succès !", "Une erreur s'est produite lors de la modification du fournisseur");
    }



    // Delete provider
    if (isset($_POST['deleteProvider'])) {
        $providerId = intval($_POST['providerId']);
        deleteData(
            'fournisseur',
            $providerId, 
            "Le fournisseur a été supprimé avec succès!", 
            "Aucun fournisseur trouvé avec l'ID donné!"
        );
    }


    // Search providers
    function searchProvider() {
        if (isset($_POST['searchProvider'])) {
            global $cnx;
            $filteredData = filterData($_POST);

            if (!isset($filteredData['providerSearchValue']) || empty($filteredData['providerSearchValue'])) {
                return [];
            }

            $inputValueLike = '%' . $filteredData['providerSearchValue'] . '%';

            $query = "SELECT * 
                    FROM fournisseur
                    WHERE nom LIKE :inputValueLike OR 
                            prenom LIKE :inputValueLike OR 
                            email LIKE :inputValueLike OR 
                            telephone LIKE :inputValueLike OR 
                            adresse LIKE :inputValueLike OR 
                            date_creation LIKE :inputValueLike
                    ORDER BY id DESC";

            $request = $cnx->prepare($query);
            $request->bindParam(':inputValueLike', $inputValueLike, PDO::PARAM_STR);

            $request->execute();

            $records = $request->fetchAll(PDO::FETCH_ASSOC);
            return $records;
        }
        return [];
    }


    function getAllProviders($limit, $offset) {
        $query = "SELECT * FROM fournisseur
        ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $request = $GLOBALS['cnx']->prepare($query);
        $request->bindValue(':limit', $limit, PDO::PARAM_INT);
        $request->bindValue(':offset', $offset, PDO::PARAM_INT);
        $request->execute();
        return $request->fetchAll();
    }

    function getTotalProvidersCount() {
        $query = "SELECT COUNT(*) as total FROM fournisseur";
        $request = $GLOBALS['cnx']->prepare($query);
        $request->execute();
        return $request->fetch()['total'];
    }

    $limit = 20;

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $providers = getAllProviders($limit, $offset);

    $totalProviders = getTotalProvidersCount();
    $totalPages = ceil($totalProviders / $limit);


    // Limited providers
    $limitedProviders = getLimitedProviders();

    // Count providers
    $countProviders = countDataTable('fournisseur');

?>