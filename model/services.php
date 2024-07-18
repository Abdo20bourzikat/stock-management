<?php
    include 'config.php';

    function accessPermission() {
        if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
            header('Location: ../login.php');
            exit();
        }
    }


    function filterData($data) {
        foreach ($data as $key => $value) {
            $data[$key] = trim($value);
            $data[$key] = stripcslashes($value);
            $data[$key] = htmlspecialchars($value);
            $data[$key] = strip_tags($value);
        }
        return $data;
    }


    function insertData($cnx, $table, $data, $successMsg, $errorMsg) {
        try {
            $filtredData = filterData($data);

            $columns = implode(", ", array_keys($filtredData));
            $placeholders = implode(", ", array_fill(0, count($filtredData), "?"));
            
            $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
            
            $stmt = $cnx->prepare($query);
            $stmt->execute(array_values($filtredData));

            if ($stmt->rowCount() != 0) {
                $_SESSION['success'] = $successMsg;
            } else {
                $_SESSION['error'] = $errorMsg;
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Une erreur s'est produite: " . $e->getMessage();
        }

    }
    
    function editData($table, $data, $condition, $successMsg, $errorMsg) {
        global $cnx;
    
        try {
            $setParts = [];
            $values = [];
            
            foreach ($data as $column => $value) {
                $setParts[] = "`$column` = ?";
                $values[] = $value;
            }
            
            $values[] = $condition['value'];
            $setQuery = implode(", ", $setParts);
    
            $query = "UPDATE `$table` SET $setQuery WHERE `{$condition['column']}` = ?";
            
            $req = $cnx->prepare($query);
            $req->execute($values);
    
            if ($req->rowCount() != 0) {
                $_SESSION['success'] = $successMsg;
            } else {
                $_SESSION['error'] = $errorMsg;
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Une erreur s'est produite: " . $e->getMessage();
        }
    }


    function deleteData($table, $clientId, $successMsg, $errorMsg) 
    {
        global $cnx;
    
        try {
            $query = "DELETE FROM $table WHERE id = ?";
            $req = $cnx->prepare($query);
    
            $req->execute([$clientId]);
    
            if ($req->rowCount() > 0) {
                $_SESSION['success'] = $successMsg;
            } else {
                $_SESSION['error'] = $errorMsg;
            }
        } catch(Exception $e) {
            $_SESSION['error'] = "Une erreur s'est produite: " . $e->getMessage();
            return $_SESSION['error'];
        }
        // header("Location: allClients.php");
    }

    function getData($table) 
    {
        global $cnx;
        $query = "SELECT * FROM $table ORDER BY id DESC";
        $request = $cnx->prepare($query);
        $request->execute();
        return $request->fetchAll();
    }
    
    // Get article by id
    function getDataById($table, $id) {
        global $cnx;
    
        $stmt = $cnx->prepare("SELECT * FROM $table WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $data;
    }

    function getLimitedRecords($table, $limit) {
        global $cnx;
        
        try {
            $query = "SELECT * FROM `$table` ORDER BY id DESC LIMIT ?";
            $stmt = $cnx->prepare($query);
            $stmt->bindParam(1, $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $records ?: [];
        } catch (Exception $e) {
            return [];
        }
    }

    function countDataTable($table) {
        global $cnx;
        $query = "SELECT COUNT(*) FROM `$table`";
        $request = $cnx->prepare($query);
        $request->execute();
        $data = $request->fetch(PDO::FETCH_NUM);
        return $data[0];
    }
 

    // Limited clients
    function getLimitedClients() {
        global $cnx;
        
        try {
            $query = "SELECT COUNT(vente.id) AS `totalSales`, 
            client.nom, 
            client.prenom, 
            client.email, 
            client.telephone, 
            client.adresse, 
            client.date_creation,
            article.nom_article as `article`,
            article.quantite as `stockQuantity`
            FROM vente
            JOIN client ON vente.id_client = client.id
            JOIN article ON vente.id_article = article.id
            GROUP BY client.id, client.nom, client.prenom, client.email, client.telephone, client.adresse, client.date_creation
            ORDER BY `totalSales` DESC
            LIMIT 6";
            $stmt = $cnx->prepare($query);
            $stmt->execute();
            
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $records ?: [];
            
        } catch (Exception $e) {
            return [];
        }
    }


    // Get sales
    function getSales($limit = null, $offset = null, $etat= null) {
        global $cnx;
        $query = "SELECT vente.id as `id`, vente.quantite as `quantity`, vente.prix as `price`, vente.date_vente as `saleDate`, vente.etat,
            article.id as `idArticle`, article.nom_article as `article`, client.id as `idClient`, 
            CONCAT(client.nom, ' ', client.prenom) as `clientName`
            FROM vente
            JOIN article ON vente.id_article = article.id
            JOIN client ON vente.id_client = client.id ";
        if (isset($etat) && $etat=='1') {
            $query .= " AND vente.etat = '1' ";
        }
        if (isset($etat) && $etat=='0') {
            $query .= " AND etat = '0' ";
        }
        $query .= " ORDER BY vente.date_vente DESC";
        
        if ($limit !== null) {
            $query .= " LIMIT :limit";
            if ($offset !== null) {
                $query .= " OFFSET :offset";
            }
        }
        
        $stmt = $cnx->prepare($query);
        
        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            if ($offset !== null) {
                $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            }
        }
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $records ?: [];
    }

    function countSalesFunction() {
        global $cnx;
        $query = "SELECT COUNT(*) FROM vente WHERE etat='1'";
        $request = $cnx->prepare($query);
        $request->execute();
        $data = $request->fetch(PDO::FETCH_NUM);
        return $data[0];
    }

    function getOutOfStockItems() {
        global $cnx;
        $query = "SELECT nom_article FROM article WHERE quantite = 0 ";
        $stmt = $cnx->prepare($query);
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $records;
    }

    
?>