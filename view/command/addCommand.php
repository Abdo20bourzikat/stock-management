<?php 
    $pageTitle = "Ajouter une commande";
    include '../../model/loginModel.php';
    accessPermission();
    include '../../model/commandModel.php';
    include '../../assets/sidebar.php';
    $limitedCommands = getCommands();
  
    $countProviders = countDataTable('fournisseur');
?>

<div class="container">
    <div class="row">
        <div class="d-flex mt-2">
            <a href="./allCommands.php" class="col-md-3 mx-2" style="text-decoration:none;">
                <div class="card shadow-sm rounded border-0 h-100" style="background-color: #e3f2fd;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div class="d-flex justify-content-between align-items-center w-100 mb-2">
                            <p class="fs-4 mb-0">
                                Liste des commandes <br> <?= $countCommands ?>
                            </p>
                            <i class="bi bi-cart4 fs-1"></i>
                        </div>
                    </div>
                </div>
            </a>
            <a href="../provider/allProviders.php" class="col-md-3" style="text-decoration:none;">
                <div class="card shadow-sm rounded border-0 h-100 " style="background-color: #e3f2fd;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div class="d-flex justify-content-between align-items-center w-100 mb-2">
                            <p class="fs-4 mb-0">
                                Liste des fournisseurs <br> <?= $countProviders ?>
                            </p>
                            <i class="bi bi-person-lines-fill fs-1"></i>
                        </div>
                    </div>
                </div>
            </a>

        </div>

    </div>
    <hr>
    <div class="row mt-3">
        <div class="col-md-4">
            <?php include '../../assets/alert.php'; ?>
            <div class="card shadow mb-5">
                <div class="card-header">
                    <div class="d-flex">
                        <i class='bx bx-list-ol text-secondary fs-4'></i><h5 class="text-secondary">Ajouter une Commande</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="form-group mb-1">
                            <label for="provider" class="col-form-label text-secondary">Fournisseur</label>
                            <select id="provider" name="provider" class="form-select shadow-none" required>
                                <option value="">Sélectionnez un fournisseur</option>
                                <?php
                                    $providers = getData('fournisseur');
                                    foreach ($providers as $key => $value):
                                ?>
                                <option value="<?= $value['id'] ?>"><?= $value['nom'] . ' ' . $value['prenom'] ?></option>
                                <?php
                                    endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="form-group mb-1">
                            <label for="articleSelect" class="col-form-label text-secondary">Les articles</label>
                            <select id="articleSelect" name="article" class="form-select shadow-none" required>
                                <option value="">Sélectionnez un article</option>
                                <?php
                                    $articles = getData('article');
                                    foreach ($articles as $key => $value):
                                ?>
                                <option value="<?= $value['id'] ?>"><?= $value['nom_article'] . ' - ' . $value['quantite'] . ' disponible' ?></option>
                                <?php
                                    endforeach;
                                ?>
                            </select>
                        </div>

                        <div class="form-group mb-1">
                            <label for="quantity" class="col-form-label text-secondary">Quantité</label>
                            <input type="text" id="quantity" name="quantity" class="form-control shadow-none" placeholder="quantité..." required>
                        </div>

                        <div class="form-group mb-1">
                            <label for="price" class="col-form-label text-secondary">Prix</label>
                            <input type="text" id="price" name="price" class="form-control shadow-none" placeholder="prix..." required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="commandDate" class="col-form-label text-secondary">Date de commande</label>
                            <input type="datetime-local" id="commandDate" name="commandDate" class="form-control shadow-none">
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" name="addCommandRequest" class="btn btn-primary btn-sm shadow-none">Valider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <?php
                if (count($limitedCommands) > 0):
            ?>
            <?php include '../../assets/rightAlert.php'; ?>
            <div class="card shadow mb-5">
                <div class="card-header">
                    <div class="d-flex">
                        <i class='bx bx-history text-secondary fs-4'></i> <h5 class="text-secondary">Commandes récentes</h5>
                    </div>                    
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <tr>
                            <th>Article</th>
                            <th>Fournisseur</th>
                            <th>Quantité</th>
                            <th>Prix</th>
                            <th>Date-Commande</th>
                            <!-- <th>Action</th> -->
                        </tr>
                        <tbody>
                        <?php foreach($limitedCommands as $command): ?>
                            <tr>
                                <td><?= $command["nom_article"] ?></td>
                                <td><?= $command['provider'] ?></td>
                                <td><?= $command["quantite"] ?></td>
                                <td><?= $command["prix"] ?></td>
                                <td>
                                    <?php
                                        $dateTime = new DateTime($command['date_commande']);
                                        $formattedDate = $dateTime->format('Y-m-d');
                                        echo $formattedDate;
                                    ?>
                                </td>
                                <!-- <td>
                                    <a href="recuVente.php?vente_id=<?= $sale['id'] ?>" title="Reçu de la vente">
                                        <i class='bx bx-receipt fs-4'></i>
                                    </a>
                                    <a onClick="annulerVente(<?= $sale["id"] ?>, <?= $sale["idArticle"] ?>, <?= $sale["quantity"] ?>)" title="Annuler cette vente">
                                        <i class='bx bx-stop-circle fs-4 text-danger'></i>
                                    </a>
                                </td> -->
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php  
                else:
            ?>
            <div class="card shadow">
                <h5 class="text-secondary my-5 mx-auto">Aucun commande encore</h5>
            </div>
            <?php  
                endif;
            ?>
        </div>
    </div>
    <?php include('../../assets/footer.php'); ?>

</div>

    <script src="../../public/js/sidebar.js"></script>
    <script src="../../public/js/bootstrap.js"></script>
    <script src="../../public/js/jquery.min.js"></script>

</body>
</html>
