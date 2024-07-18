<?php 
    $pageTitle = "Ajouter un Article";
    include '../../model/loginModel.php';
    include '../../model/articleModel.php';
    include '../../assets/sidebar.php';
    accessPermission();
    $countArticles = countDataTable('article');
    $countSales= countSalesFunction(); 
?>

<div class="container">
    <div class="row mt-3">
        <div class="col-md-4">
            <?php include '../../assets/alert.php'; ?>
            <div class="card shadow mb-5">
                <div class="card-header">
                    <div class="d-flex">
                    <i class='bx bxs-box text-secondary fs-4'></i> <h5 class="text-secondary">Ajouter un Article</h5>
                    </div>                    
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="form-group mb-1">
                            <label for="articleName" class="col-form-label text-secondary">Nom de l'article</label>
                            <input id="articleName" name="articleName" type="text" class="form-control shadow-none" placeholder="nom de l'article..." required>
                        </div>
                        <div class="form-group mb-1">
                            <label for="categorie" class="col-form-label text-secondary">Catégorie</label>
                            <select id="categorie" name="category" class="form-select shadow-none" id="">
                                <option value="">Veuillez choisir un article</option>
                                <?php
                                    $categories = getData('categorie');
                                    foreach ($categories as $category):
                                ?>
                                    <option value="<?= $category['id'] ?>"><?= $category['categorie'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mb-1">
                            <label for="quantity" class="col-form-label text-secondary">Quantité</label>
                            <input type="number" id="quantity" name="quantity" class="form-control shadow-none" placeholder="quantité..." required>
                        </div>
                        <div class="form-group mb-1">
                            <label for="price" class="col-form-label text-secondary">Prix unitaire</label>
                            <input type="number" id="price" name="unitPrice" class="form-control shadow-none" placeholder="prix unitaire..." required>
                        </div>
                        <div class="form-group mb-1">
                            <label for="manufactureDate" class="col-form-label text-secondary">Date de fabrication</label>
                            <input type="datetime-local" id="manufactureDate" name="manufactureDate" class="form-control shadow-none">
                        </div>
                        <div class="form-group mb-3">
                            <label for="expirationDate" class="col-form-label text-secondary">Date d'éxpirarion</label>
                            <input type="datetime-local" id="expirationDate" name="expirationDate" class="form-control shadow-none">
                        </div>
                        <div class="form-group">
                            <button type="submit" name="addArticleRequest" class="btn btn-primary btn-sm shadow-none">Valider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php
            $getArticles = getData('article');
        ?>
        <div class="col-md-8">
            <div class="d-flex mb-3">
                <a href="./allArticles.php" class="col-md-4" style="text-decoration:none;">
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

                <a href="../vente/allVentes.php" class="col-md-4 mx-2" style="text-decoration:none;">
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
            </div>
            <hr>
            <?php include '../../assets/rightAlert.php'; ?>
            <!-- <div class="card shadow">
                <div class="card-header">
                    <div class="d-flex">
                    <i class='bx bxs-up-arrow-square text-secondary fs-4'></i> <h5 class="text-secondary">Augmenter la quantité</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="article" class="col-form-label text-secondary">Article</label>
                                <select id="article" name="articleId" class="form-select shadow-none" required>
                                    <option value="">Veuillez choisir un article</option>
                                    <?php 
                                        foreach ($getArticles as $article):
                                    ?>
                                        <option value="<?= $article['id'] ?>"><?= $article['nom_article'] . ' - ' .$article['quantite'] . ' disponible'?></option>
                                    <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="categorie" class="col-form-label text-secondary">Quantité</label>
                                <input type="text" name="quantity" class="form-control shadow-none" placeholder="quantité..." required>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" name="addQuantity" class="btn btn-primary btn-sm shadow-none">Valider</button>
                        </div>

                    </form>
                </div>
            </div> -->

            <div class="card shadow mt-4">
                <div class="card-header">
                    <div class="d-flex">
                        <i class='bx bx-category-alt text-secondary fs-4'></i> <h5 class="text-secondary">Ajouter une catégorie</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="categorie" class="col-form-label text-secondary">Catégorie</label>
                                <input type="text" name="category" class="form-control shadow-none" placeholder="catégorie..." required>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" name="addCategory" class="btn btn-primary btn-sm shadow-none">Valider</button>
                        </div>

                    </form>
                </div>
            </div>

        <!-- <div class="col-md-8">


            <?php
                if (count($limitedArticles) > 0):
            ?>
            <div class="card shadow mb-5">
                <div class="card-header">
                    <div class="d-flex">
                        <i class='bx bx-list-ul fs-4'></i> <h5>Liste des Articles</h5>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <tr>
                            <th>Nom article</th>
                            <th>Catégorie</th>
                            <th>Quantité</th>
                            <th>Prix unitaire</th>
                            <th>Date fabrication</th>
                            <th>Date expiration</th>
                        </tr>
                        <?php foreach($limitedArticles as $article): ?>
                            <tr>
                                <td><?= $article["nom_article"] ?></td>
                                <td><?= $article["categorie"] ?></td>
                                <td><?= $article["quantite"] ?></td>
                                <td><?= $article["prix_unitaire"] ?></td>
                                <td>
                                    <?php
                                         $dateTime = new DateTime($article["date_fabrication"]);
                                         $formattedDate1 = $dateTime->format('Y-m-d');
                                         echo $formattedDate1;
                                     ?>
                                </td>
                                <td>
                                    <?php
                                         $dateTime = new DateTime($article["date_expiration"]);
                                         $formattedDate2 = $dateTime->format('Y-m-d');
                                         echo $formattedDate2;
                                     ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    <a href="./allArticles.php" class="btn btn-secondary btn-sm mt-2 shadow-none">Voir tous les articles</a>
                </div>
            </div>
            <?php  
                else:
            ?>
            <div class="card shadow">
                <h5 class="text-secondary my-5 mx-auto">Aucun article encore</h5>
            </div>
            <?php  
                endif;
            ?>
        </div> -->
    </div>
    <?php include('../../assets/footer.php'); ?>

</div>

    <script src="../../public/js/sidebar.js"></script>
    <script src="../../public/js/bootstrap.js"></script>
    <script src="../../public/js/jquery.min.js"></script>

</body>
</html>
