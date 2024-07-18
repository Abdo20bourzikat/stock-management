<?php 
    $pageTitle = "Modifier une commande";
    include '../../model/loginModel.php';
    accessPermission();
    include '../../model/commandModel.php';
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
                        <i class='bx bxs-edit text-secondary fs-4'></i><h5 class="text-secondary">Modifier la commande</h5>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                        if (isset($_GET['command_id'])) {
                            $commandId = $_GET['command_id'];
                            $command = getDataById('commande', $commandId);
                        }
                    ?>
                    <form action="" method="post">
                        <input type="hidden" name="commandId" value="<?= $command['id'] ?>">
                        <div class="row mb-2">
                            <div class="col-md-6 form-group">
                                <label for="articleSelect" class="col-form-label text-secondary">L'article</label>
                                <select id="articleSelect" name="article" class="form-select shadow-none" required>
                                    <?php
                                        $idArticle = $command['id_article'];
                                        $article = getDataById('article', $idArticle);
                                    ?>
                                    <option data-price="<?= $article['prix_unitaire'] ?>" value="<?= $idArticle ?>">
                                        <?= $article['nom_article'] . ' - ' . $article['quantite'] . ' disponible' ?>
                                    </option>
                                    <?php
                                        $allArticles = getData('article');
                                        foreach ($allArticles as $key => $value):
                                    ?>
                                    <option data-price="<?= $value['prix_unitaire'] ?>" value="<?= $value['id'] ?>"><?= $value['nom_article'] . ' - ' . $value['quantite'] . ' disponible' ?></option>
                                    <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>


                            <div class="col-md-6 form-group">
                                <label for="provider" class="col-form-label text-secondary">Le fournisseur</label>
                                <select id="provider" name="provider" class="form-select shadow-none" required>
                                    <?php
                                        $providerId = $command['id_fournisseur'];
                                        $provider = getDataById('fournisseur', $providerId);
                                        $allProviders = getData('fournisseur');
                                    ?>
                                    <option value="<?= $providerId ?>"><?= $provider['nom'] . ' ' . $provider['prenom'] ?></option>
                                    <?php
                                        foreach ($allProviders as $key => $value):
                                    ?>
                                    <option value="<?= $value['id'] ?>"><?= $value['nom'] . ' ' . $value['prenom'] ?></option>
                                    <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>

                            
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6 form-group">
                                <label for="quantity" class="col-form-label text-secondary">Quantité</label>
                                <input type="text" id="quantity" name="quantity" value="<?= $command['quantite'] ?>" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="price" class="col-form-label text-secondary">Prix</label>
                                <input type="text" id="price" name="price" value="<?= $command['prix'] ?>" class="form-control shadow-none" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 form-group">
                                <?php
                                    $dateTime = new DateTime($command['date_commande']);
                                    $formattedDate = $dateTime->format('Y-m-d H:i');
                                ?>
                                <label for="saleDate" class="col-form-label text-secondary">Date vente</label>
                                <input type="datetime-local" id="commandDate" name="commandDate"  value="<?= $formattedDate ?>" class="form-control shadow-none">
                            </div>
                        </div>

                        <div class="form-group">
                            <d-flex>
                                <a href="./allCommands.php" class="btn btn-secondary btn-sm shadow-none">Annuler</a>
                                <button type="submit" name="editCommandRequest" class="btn btn-primary btn-sm shadow-none">Modifier</button>
                            </d-flex>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include('../../assets/footer.php'); ?>

</div>

    <script>
        function setPrice() {
            var article = document.querySelector('#articleSelect');
            var quantity = document.querySelector('#quantity');
            var price = document.querySelector('#price');

            var prixUnitaire = article.options[article.selectedIndex].getAttribute('data-price');
            price.value = Number(quantity.value) * Number(prixUnitaire);
        }
    </script>

    <script src="../../public/js/sidebar.js"></script>
    <script src="../../public/js/bootstrap.js"></script>
    <script src="../../public/js/jquery.min.js"></script>

</body>
</html>