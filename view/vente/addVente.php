<?php 
    $pageTitle = "Ajouter une vente";
    include '../../model/loginModel.php';
    accessPermission();
    include '../../model/venteModel.php';
    include '../../assets/sidebar.php';
    $countArticles = countDataTable('article');
    $countClients = countDataTable('client');
?>

<div class="container">
    <div class="row">
        <div class="d-flex mt-2">
            <a href="../vente/allVentes.php" class="col-md-3 mx-2" style="text-decoration:none;">
                <div class="card shadow-sm rounded border-0 h-100" style="background-color: #e3f2fd;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div class="d-flex justify-content-between align-items-center w-100 mb-2">
                            <p class="fs-4 mb-0">
                                Liste des ventes <br> <?= $countSales ?>
                            </p>
                            <i class="bi bi-cart-fill fs-1"></i>
                        </div>
                    </div>
                </div>
            </a>

            <a href="./salesOff.php" class="col-md-3" style="text-decoration:none;">
                <div class="card shadow-sm rounded border-0 h-100" style="background-color: #e3f2fd;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div class="d-flex justify-content-between align-items-center w-100 mb-2">
                            <p class="fs-4 mb-0">
                                Ventes désactivées <br> <?= $countSalesOff ?>
                            </p>
                            <i class='bx bx-stop-circle fs-1'></i>
                        </div>
                    </div>
                </div>
            </a>

            <a href="../article/allArticles.php" class="col-md-3 mx-2" style="text-decoration:none;">
                <div class="card shadow-sm rounded border-0 h-100" style="background-color: #e3f2fd;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div class="d-flex justify-content-between align-items-center w-100 mb-2">
                            <p class="fs-4 mb-0">
                                Liste des articles <br> <?= $countArticles ?>
                            </p>
                            <i class="bi bi-archive-fill fs-1"></i>
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
                        <i class='bx bx-shopping-bag text-secondary fs-4'></i> <h5 class="text-secondary">Ajouter une Vente</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="form-group mb-1">
                            <label for="clientSelect" class="col-form-label text-secondary">Les clients</label>
                            <select id="clientSelect" name="client" class="form-select shadow-none" required>
                                <option value="">Sélectionnez un client</option>
                                <?php
                                    foreach ($allClients as $key => $value):
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
                                    foreach ($allArticles as $key => $value):
                                ?>
                                <option data-price="<?= $value['prix_unitaire'] ?>" value="<?= $value['id'] ?>"><?= $value['nom_article'] . ' - ' . $value['quantite'] . ' disponible' ?></option>
                                <?php
                                    endforeach;
                                ?>
                            </select>
                        </div>

                        <div class="form-group mb-1">
                            <label for="quantity" class="col-form-label text-secondary">Quantité</label>
                            <input type="text" onKeyup="setPrice()" id="quantity" name="quantity" class="form-control shadow-none" placeholder="quantité..." required>
                        </div>

                        <div class="form-group mb-2">
                            <label for="price" class="col-form-label text-secondary">Prix</label>
                            <input type="text" id="price" name="price" class="form-control shadow-none" placeholder="prix..." required>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" name="addVenteRequest" class="btn btn-primary btn-sm shadow-none">Valider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <?php
                if (count($limitedSales) > 0):
            ?>
            <?php include '../../assets/rightAlert.php'; ?>
            <div class="card shadow mb-5">
                <div class="card-header">
                    <div class="d-flex">
                        <i class='bx bx-history text-secondary fs-4'></i> <h5 class="text-secondary">Ventes Récentes</h5>
                    </div>                    
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <tr>
                            <th>Client</th>
                            <th>Article</th>
                            <th>Quantité</th>
                            <th>Prix</th>
                            <th>Date vente</th>
                            <th>Action</th>
                        </tr>
                        <tbody>
                        <?php foreach($limitedSales as $sale): ?>
                            <tr>
                                <td><?= $sale['clientName'] ?></td>
                                <td><?= $sale["article"] ?></td>
                                <td><?= $sale["quantity"] ?></td>
                                <td><?= $sale["price"] ?></td>
                                <td>
                                    <?php
                                        $dateTime = new DateTime($sale['saleDate']);
                                        $formattedDate = $dateTime->format('Y-m-d');
                                        echo $formattedDate;
                                    ?>
                                </td>
                                <td>
                                    <a href="recuVente.php?vente_id=<?= $sale['id'] ?>" title="Reçu de la vente">
                                        <i class='bx bx-receipt fs-4'></i>
                                    </a>
                                    <?php if ($_SESSION['role'] == 1): ?>
                                    
                                    <a onClick="annulerVente(<?= $sale["id"] ?>, <?= $sale["idArticle"] ?>, <?= $sale["quantity"] ?>)" title="Désactivé cette vente">
                                        <i class='bx bx-stop-circle fs-4 text-danger'></i>
                                    </a>
                                    <?php endif; ?>
                                </td>
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
                <h5 class="text-secondary my-5 mx-auto">Aucun vente encore</h5>
            </div>
            <?php  
                endif;
            ?>
        </div>
    </div>
    <?php include('../../assets/footer.php'); ?>

</div>

    <script>
        function annulerVente(idVente, idArticle, quantite) {
            if (confirm("Voulez-vous vraiment désactivé cetter vente ?")) {
                window.location.href = "../../model/annulerVente.php?idVente=" + idVente + "&idArticle=" + idArticle + "&quantite=" + quantite
            }
        }

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
