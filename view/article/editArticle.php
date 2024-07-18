<?php 
    $pageTitle = "Modifier un article";
    include '../../model/loginModel.php';
    accessPermission();
    include '../../model/articleModel.php';
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
                        <i class='bx bxs-edit text-secondary fs-4'></i><h5 class="text-secondary">Modifier l'article</h5>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                        if (isset($_GET['article_id']) && is_numeric($_GET['article_id'])) { // Ensure article_id is numeric
                            $articleId = (int)$_GET['article_id']; // Cast to integer to avoid injection risks
                            $query = "SELECT 
                                        article.id, 
                                        article.nom_article, 
                                        article.quantite, 
                                        article.prix_unitaire,
                                        article.date_fabrication, 
                                        article.date_expiration,
                                        categorie.id AS id_categorie, 
                                        categorie.categorie
                                      FROM article
                                      INNER JOIN categorie ON article.id_categorie = categorie.id
                                      WHERE article.id = :id";
                            $request = $cnx->prepare($query);
                            $request->bindParam(':id', $articleId, PDO::PARAM_INT);
                            $request->execute();
                            $article = $request->fetch(PDO::FETCH_ASSOC);
                      
                        }
                        

                            // $article = getDataById('article', $articleId);
                        
                    ?>
                    <form action="" method="post">
                        <input type="hidden" name="article_id" value="<?= $article['id'] ?>">
                        <div class="row mb-2">
                            <div class="col-md-6 form-group">
                                <label for="articleName" class="col-form-label text-secondary">Nom de l'article</label>
                                <input id="articleName" name="articleName" type="text" class="form-control shadow-none" value="<?= $article['nom_article'] ?>" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="categorie" class="col-form-label text-secondary">Catégorie</label>
                                <select id="categorie" name="category" class="form-select shadow-none" id="">
                                    <option value="<?= $article['id_categorie'] ?>"><?= $article['categorie'] ?></option>
                                    <?php
                                    $categories = getData('categorie');
                                        foreach ($categories as $category):
                                    ?>
                                        <option value="<?= $category['id'] ?>"><?= $category['categorie'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6 form-group">
                                <label for="quantity" class="col-form-label text-secondary">Quantité</label>
                                <input type="number" id="quantity" name="quantity" class="form-control shadow-none" value="<?= $article['quantite'] ?>" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="price" class="col-form-label text-secondary">Prix unitaire</label>
                                <input type="number" id="price" name="unitPrice" class="form-control shadow-none" value="<?= $article['prix_unitaire'] ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 form-group">
                                <label for="manufactureDate" class="col-form-label text-secondary">Date de fabrication</label>
                                <input type="datetime-local" id="manufactureDate" name="manufactureDate" value="<?= $article['date_fabrication'] ?>" class="form-control shadow-none">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="expirationDate" class="col-form-label text-secondary">Date d'éxpirarion</label>
                                <input type="datetime-local" id="expirationDate" name="expirationDate" value="<?= $article['date_expiration'] ?>" class="form-control shadow-none">
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="./allArticles.php" class="btn btn-secondary btn-sm shadow-none">Annuler</a>
                            <button type="submit" name="editArticleRequest" class="btn btn-primary btn-sm shadow-none">Modifier</button>
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