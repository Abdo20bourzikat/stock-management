<?php 
    
    if (isset($_POST['addUser'])) {
        if ($_SESSION['role'] == 1) {
            $stmt = $cnx->prepare("SELECT login FROM _users_");
            $stmt->execute();
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $filtredUserData = filterData($_POST);
            
            $userExists = false;
            foreach ($records as $record) {
                if ($filtredUserData['login'] == $record['login']) {
                    $userExists = true;
                    break;
                }
            }
    
            if (!$userExists) {
                if ($filtredUserData['password'] === $filtredUserData['passwordConfirmation']) {
                    $hashPassword = password_hash($filtredUserData['password'], PASSWORD_DEFAULT);
                    $insertUser = $cnx->prepare("INSERT INTO _users_(nom, prenom, login, password, role) VALUES(:lastname, :name, :login, :password, 0)");
                    $insertUser->bindParam(':name', $filtredUserData['name'], PDO::PARAM_STR);
                    $insertUser->bindParam(':lastname', $filtredUserData['lastname'], PDO::PARAM_STR);
                    $insertUser->bindParam(':login', $filtredUserData['login'], PDO::PARAM_STR);
                    $insertUser->bindParam(':password', $hashPassword, PDO::PARAM_STR);
                    $insertUser->execute();
                    $_SESSION['success'] = "L'utilisateur a été ajouté avec succès !";
                } else {
                    $_SESSION['error'] = "Le mot de passe et la confirmation du mot de passe ne correspondent pas !";
                }
            } else {
                $_SESSION['error'] = "Identifiant invalide, veuillez le mettre à jour !";
            }
        } else {
            $_SESSION['error'] = "Vous n'avez pas l'autorisation pour ce processus !";
        }
    }
    

    // Change password
    if (isset($_POST['changePassword'])) {
        $filtredData = filterData($_POST);

        if (
            !empty($filtredData["currentPassword"]) || 
            !empty($filtredData["newPassword"]) || 
            !empty($filtredData["passwordConfirmation"])
        ) {
            $query = "SELECT * FROM _users_ WHERE role = 1 ";
            $request = $cnx->prepare($query);
            $request->execute();
            $data = $request->fetch(PDO::FETCH_ASSOC);

            if (password_verify($filtredData["currentPassword"], $data['password'])) {
                if ($filtredData["newPassword"] === $filtredData["passwordConfirmation"]) {
                    $newHashPass = password_hash($filtredData["newPassword"], PASSWORD_DEFAULT);
                    $sql = "UPDATE _users_ SET nom = :lastname, prenom = :name, login = :login, 
                    password = :password WHERE role = 1 ";
                    $req = $cnx->prepare($sql);
                    $req->bindParam(':password', $newHashPass);
                    $req->bindParam(':lastname', $filtredData['lastname'], PDO::PARAM_STR);
                    $req->bindParam(':name', $filtredData['name'], PDO::PARAM_STR);
                    $req->bindParam(':login', $filtredData['login'], PDO::PARAM_STR);
                    $req->execute();
                    $_SESSION['success'] = "Vos informations personnelles ont été modifiées avec succès !";
                    
                } else {
                    $_SESSION['error'] = "Le mot de passe et la confirmation du mot de passe ne correspondent pas !";
                }
            } else {
                $_SESSION['error'] = "Le mot de passe actuel est incorrect !";
            }

        } else {
            $_SESSION['error'] = "Veuillez remplir toutes les informations !";
        }
    }

    // Edit contact information data
    if (isset($_POST['updateContactInfo'])) {
        try {
            $filtredContactData = filterData($_POST);
            $contactQuery = "UPDATE `contactinfo` SET company = :company, phone = :phone, email = :email, address = :address WHERE id = 1 ";
            $contactRequest = $cnx->prepare($contactQuery);
            $contactRequest->bindParam(":company", $filtredContactData['companyName'], PDO::PARAM_STR);
            $contactRequest->bindParam(":phone", $filtredContactData['phone'], PDO::PARAM_STR);
            $contactRequest->bindParam(":email", $filtredContactData['email'], PDO::PARAM_STR);
            $contactRequest->bindParam(":address", $filtredContactData['address'], PDO::PARAM_STR);
            $contactRequest->execute();
            $_SESSION['success'] = "Les informations de contact ont été mises à jour avec succès !";
        } catch (Exception $e) {
            $_SESSION['error'] = "Une erreur s'est produite: " . $e->getMessage();
            return $_SESSION['error'];
        }
    }


    // Get contact informations data
    $contactInfo = getDataById('contactinfo', 1);

?>
