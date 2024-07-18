<?php 
    $pageTitle = "Paramètres";
    include '../../model/loginModel.php';
    accessPermission();
    include '../../model/settingModel.php';
    include '../../assets/sidebar.php';

    $companyName = $contactInfo["company"];
    $phone = $contactInfo["phone"];
    $email = $contactInfo["email"];
    $address = $contactInfo["address"];
    if ($_SESSION['role'] == 0):
            
?>
        
    <div class="col-md-12 mt-5 card shadow">
        <h5 class="text-danger my-5 mx-auto">Vous n'avez pas l'autorisation pour ce processus !</h5>
    </div>

<?php else: ?>


<div class="container">
    
        <div class="modal modal-dialog modal-lg fade" id="contactModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content modal-lg">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" method="post">
                    <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="companyName" class="form-label">Nom de l’entreprise</label>
                                        <input type="text" name="companyName" class="form-control shadow-none" value="<?= $companyName ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone" class="form-label">Téléphone</label>
                                        <input type="text" name="phone" class="form-control shadow-none" value="<?= $phone ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="text" name="email" class="form-control shadow-none" value="<?= $email ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address" class="form-label">Adresse</label>
                                        <input type="text" name="address" class="form-control shadow-none" value="<?= $address ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" name="updateContactInfo" class="btn btn-primary btn-sm">Modifier</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



    <div class="col-md-4">
        <?php include '../../assets/alert.php'; ?>
    </div>
    <div class="card shadow mt-3 mb-4">
        <div class="d-flex justify-content-between mx-4 mt-3">
            <p class="mb-0"><i class="bi bi-info-lg"></i>Informations générales</p>
            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#contactModal"><i class="bi bi-pencil-square mx-1"></i>Modifier</button>
        </div>
        <hr class="mx-5" width="90px">
        <div class="mb-4">
            <p class="mx-5">
                <i class="bi bi-shop mx-1"></i>Nom de l’entreprise: <?= $companyName ?>
            </p>
            <p class="mx-5">
                <i class="bi bi-telephone mx-1"></i>Téléphone: <?= $phone ?>
            </p>
            <p class="mx-5">
                <i class="bi bi-envelope mx-1"></i>Email: <?= $email  ?>
            </p>
            <p class="mx-5">
                <i class="bi bi-geo-alt"></i>Adresse: <?= $address  ?>
            </p>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="d-flex justify-content-between mx-4 mt-3">
            <p class="mb-0"><i class="bi bi-person-fill-lock mx-1"></i>Ajouter un utilisateur</p>
        </div>
        <hr class="mx-5" width="90px">

        <form action="" method="post" class="mt-3 mb-5 mx-5">
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="lastname" class="form-label">Nom</label>
                        <input type="text" name="lastname" class="form-control shadow-none" placeholder="Nom..." required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name" class="form-label">Prènom</label>
                        <input type="text" name="name" class="form-control shadow-none" placeholder="Prènom..." required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="login" class="form-label">Login</label>
                        <input type="text" name="login" class="form-control shadow-none" placeholder="Login..." required>
                    </div>
                </div>
                <div class="col-md-4"> 
                    <div class="form-group">
                        <label for="" class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-control shadow-none" placeholder="mot de passe..." required>
                    </div>
                </div>
                <div class="col-md-4"> 
                    <div class="form-group">
                        <label for="" class="form-label">Confirmation du mot de passe</label>
                        <input type="password" name="passwordConfirmation" class="form-control shadow-none" placeholder="confirmation du mot de passe..." required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mt-3">
                        <button type="submit" name="addUser" class="btn btn-primary btn-sm shadow-none">Enregistrer</button>
                    </div>
                </div>
            </div>
        </form>

    </div>

    <div class="card shadow mb-4">
        <div class="d-flex justify-content-between mx-4 mt-3">
            <p class="mb-0"><i class="bi bi-person-fill mx-1"></i>Informations personnelles</p>
        </div>
        <hr class="mx-5" width="90px">

        <form action="" method="post" class="mt-3 mb-5 mx-5">
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="" class="form-label">Nom</label>
                        <input type="text" name="lastname" class="form-control shadow-none" value="<?= $_SESSION['lastname'] ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="" class="form-label">Prenom</label>
                        <input type="text" name="name" class="form-control shadow-none" value="<?= $_SESSION['lastname'] ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="" class="form-label">Login</label>
                        <input type="text" name="login" class="form-control shadow-none" placeholder="login..." required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="" class="form-label">Mot de passe actuel</label>
                        <input type="password" name="currentPassword" class="form-control shadow-none" placeholder="mot de passe actuel..." required>
                    </div>
                </div>
                <div class="col-md-4"> 
                    <div class="form-group">
                        <label for="" class="form-label">Nouveau mot de passe</label>
                        <input type="password" name="newPassword" class="form-control shadow-none" placeholder="nouveau mot de passe..." required>
                    </div>
                </div>
                <div class="col-md-4"> 
                    <div class="form-group">
                        <label for="" class="form-label">Confirmation du mot de passe</label>
                        <input type="password" name="passwordConfirmation" class="form-control shadow-none" placeholder="confirmation du mot de passe..." required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mt-3">
                        <button type="submit" name="changePassword" class="btn btn-primary btn-sm shadow-none">Enregistrer</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
    <?php include('../../assets/footer.php'); ?>

</div>



        


    <script src="../../public/js/sidebar.js"></script>
    <script src="../../public/js/bootstrap.js"></script>
    <script src="../../public/js/jquery.min.js"></script>

</body>
</html>

<?php endif; ?>
