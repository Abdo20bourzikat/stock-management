<?php 
    $pageTitle = "Modifier un fournisseur";
    include '../../model/loginModel.php';
    accessPermission();
    include '../../model/providerModel.php';
    include '../../assets/sidebar.php'; 
?>

<div class="container">
    <div class="row mt-3">
        <div class="col-md-5">
            <?php include '../../assets/alert.php'; ?>
        </div>
        <div class="col-md-12">
            <div class="card shadow mb-5">
                <div class="card-header">
                    <div class="d-flex">
                        <i class='bx bxs-edit text-secondary fs-4'></i><h5 class="text-secondary">Modifier le fournisseur</h5>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                        if (isset($_GET['provider_id'])) {
                            $providerId = $_GET['provider_id'];
                            $provider = getDataById('fournisseur', $providerId);
                        }
                    ?>
                    <form action="" method="post">
                        <input type="hidden" name="providerId" value="<?= $provider['id'] ?>">
                        <div class="row mb-2">
                            <div class="col-md-6 form-group">
                                <label for="nom" class="col-form-label text-secondary">Nom du fournisseur</label>
                                <input id="nom" name="nom" type="text" class="form-control shadow-none" value="<?= $provider['nom'] ?>" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="prenom" class="col-form-label text-secondary">Prènom du fournisseur</label>
                                <input id="prenom" name="prenom" type="text" class="form-control shadow-none" value="<?= $provider['prenom'] ?>" required>
                            </div>
                            
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6 form-group">
                                <label for="phone" class="col-form-label text-secondary">Téléphone</label>
                                <input id="phone" name="phone" type="text" class="form-control shadow-none" value="<?= $provider['telephone'] ?>">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="email" class="col-form-label text-secondary">Email</label>
                                <input id="email" name="email" type="email" class="form-control shadow-none" value="<?= $provider['email'] ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 form-group">
                                <label for="address" class="col-form-label text-secondary">Adresse</label>
                                <input id="address" name="address" type="text" class="form-control shadow-none" value="<?= $provider['adresse'] ?>">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="expirationDate" class="col-form-label text-secondary">Date de création</label>
                                <input type="datetime-local" id="creationDate" name="creationDate" value="<?= $provider['date_creation'] ?>" class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="./allProviders.php" class="btn btn-secondary btn-sm shadow-none">Annuler</a>
                            <button type="submit" name="editProviderRequest" class="btn btn-primary btn-sm shadow-none">Modifier</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include('../../assets/footer.php'); ?>

</div>

<script src="../../public/js/sidebar.js"></script>
<script src="../../public/js/bootstrap.js"></script>
<script src="../../public/js/jquery.min.js"></script>

</body>
</html>