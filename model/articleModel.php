<?php
    
    // Add article
    if (isset($_POST['addArticleRequest'])) {
        $articleData = [
            "nom_article" => $_POST["articleName"],
            "id_categorie" => $_POST["category"],
            "quantite" => $_POST["quantity"],
            "prix_unitaire" => $_POST["unitPrice"],
            "date_fabrication" => $_POST["manufactureDate"],
            "date_expiration" => $_POST["expirationDate"]
        ];

        $checkQuery = "SELECT COUNT(*) FROM `article` WHERE nom_article = :articleName AND categorie = :category";
        $checkRequest = $cnx->prepare($checkQuery);
        $checkRequest->bindValue(':articleName', $articleData['nom_article'], PDO::PARAM_STR);
        $checkRequest->bindValue(':category', $articleData['categorie'], PDO::PARAM_STR);
        $checkRequest->execute();
        $articleExists = $checkRequest->fetchColumn();

        if ($articleExists == 0) {
            insertData($cnx, "article", $articleData, "L'article a été ajouté avec succès!", "Une erreur s'est produite lors de l'ajout de l'article");
        } else {
            $_SESSION['error'] = "L'article existe déjà!";
        }

    }

    // Update article
    if (isset($_POST['editArticleRequest'])) {
        $articleId = $_POST['article_id'];
        $request = filterData($_POST);

        $data = [
            'nom_article' => $request['articleName'],
            'id_categorie' => $request['category'],
            'quantite' => $request['quantity'],
            'prix_unitaire' => $request['unitPrice'],
            'date_fabrication' => $request['manufactureDate'],
            'date_expiration' => $request['expirationDate']
        ];

        $condition = [
            'column' => 'id',
            'value' => $articleId
        ];

        editData('article', $data, $condition, "L'article a été Modifié avec succès!", "Une erreur s'est produite lors de la modification de l'article!");

    }

    // Delete article
    if (isset($_POST['deleteArticle'])) {
        $articleId = intval($_POST['articleId']);
        deleteData(
            'article', 
            $articleId,
            "L'article a été supprimé avec succès!", 
            "Aucun article trouvé avec l'ID donné!"
        );
    }

        // Search articles
    function searchArticles() {
        if (isset($_POST['searchArticle'])) {
            global $cnx;
            $filteredData = filterData($_POST);

            if (!isset($filteredData['articleSearchValue']) || empty($filteredData['articleSearchValue'])) {
                return [];
            }

            $inputValueLike = '%' . $filteredData['articleSearchValue'] . '%';

            $query = "SELECT article.id, 
                            article.nom_article, 
                            article.quantite, 
                            article.prix_unitaire, 
                            article.date_fabrication, 
                            article.date_expiration, 
                            categorie.categorie as `categorie`
                    FROM article
                    JOIN categorie ON article.id_categorie = categorie.id
                    WHERE article.nom_article LIKE :inputValueLike OR 
                            article.quantite LIKE :inputValueLike OR 
                            article.prix_unitaire LIKE :inputValueLike OR 
                            article.date_fabrication LIKE :inputValueLike OR 
                            article.date_expiration LIKE :inputValueLike OR 
                            categorie.categorie LIKE :inputValueLike
                    ORDER BY article.id DESC";

            $request = $cnx->prepare($query);
            $request->bindParam(':inputValueLike', $inputValueLike, PDO::PARAM_STR);

            $request->execute();

            $records = $request->fetchAll(PDO::FETCH_ASSOC);
            return $records;
        }
        return [];
    }


    // Get limited article records
    $limitedArticles = getLimitedRecords('article', 6);

    function getAllArticles($limit, $offset) {
        $query = "SELECT article.id, nom_article, quantite, prix_unitaire, date_fabrication, date_expiration, categorie.categorie as `categorie`
        FROM article, categorie WHERE article.id_categorie = categorie.id
        ORDER BY article.id DESC LIMIT :limit OFFSET :offset";
        $request = $GLOBALS['cnx']->prepare($query);
        $request->bindValue(':limit', $limit, PDO::PARAM_INT);
        $request->bindValue(':offset', $offset, PDO::PARAM_INT);
        $request->execute();
        return $request->fetchAll();
    }
    
    function getTotalArticleCount() {
        $query = "SELECT COUNT(*) as total FROM article";
        $request = $GLOBALS['cnx']->prepare($query);
        $request->execute();
        return $request->fetch()['total'];
    }

    $limit = 40;

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $articles = getAllArticles($limit, $offset);

    $totalArticles = getTotalArticleCount();
    $totalPages = ceil($totalArticles / $limit);

    $countArticles = countDataTable('article');


    // Add quantity
    if (isset($_POST['addQuantity'])) {
        $filterData = filterData($_POST);
        $query = "UPDATE `article` SET quantite = (quantite + :quantity) WHERE id = :id";
        $request = $cnx->prepare($query);
        $request->bindValue(':quantity', $filterData['quantity'], PDO::PARAM_INT);
        $request->bindValue(':id', $filterData['articleId'], PDO::PARAM_INT);
        $request->execute();
        if ($request->rowCount() != 0) {
            $_SESSION['rightSuccess'] = "Quantité ajoutée avec succès!";
        } else {
            $_SESSION['rightError'] = "Une erreur s'est produite lors de l'ajout de la qunatité!";
        }
    }

    if (isset($_POST['addCategory'])) {
        $filterData = filterData($_POST);
    
        // Check if the category already exists
        $checkQuery = "SELECT COUNT(*) FROM `categorie` WHERE categorie = :category";
        $checkRequest = $cnx->prepare($checkQuery);
        $checkRequest->bindValue(':category', $filterData['category'], PDO::PARAM_STR);
        $checkRequest->execute();
        $categoryExists = $checkRequest->fetchColumn();
    
        if ($categoryExists == 0) {
            $query = "INSERT INTO `categorie`(categorie) VALUES(:category)";
            $request = $cnx->prepare($query);
            $request->bindValue(':category', $filterData['category'], PDO::PARAM_STR);
            $request->execute();
            
            if ($request->rowCount() != 0) {
                $_SESSION['rightSuccess'] = "Catégorie ajoutée avec succès!";
            } else {
                $_SESSION['rightError'] = "Une erreur s'est produite lors de l'ajout de la catégorie!";
            }
        } else {
            $_SESSION['rightError'] = "La catégorie existe déjà!";
        }
    }



?>
