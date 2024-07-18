<?php 
    $pageTitle = "Liste des articles";
    include '../../model/loginModel.php';
    accessPermission();
    include '../../model/articleModel.php';
    include '../../assets/sidebar.php'; 

?>

<div class="container">
    <div class="row mt-3">
        <div class="col-md-4">
            <?php include '../../assets/alert.php'; ?>
        </div>
        <div class="col-md-12">
            <a href="./addArticle.php" class="btn btn-default btn my-2 shadow-none" style="background-color: #e3f2fd; color: #000;"><< Précédent</a>

            <div class="card shadow mb-5">
                <?php 
                    if (count($articles) > 0): 
                ?>
                <div class="card-header"><div class="d-flex">
                        <i class='bx bx-list-ul text-secondary fs-4'></i><h5 class="text-secondary">Liste des Articles(<?= $countArticles ?>)</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-5">
                        <form action="" method="post">
                            <div class="d-flex">
                                <input type="text" id="searchArticle" name="articleSearchValue" class="form-control shadow-none mb-2 flex-right" placeholder="rechercher des articles..." required>
                                <button type="submit" name="searchArticle" class="btn btn-primary shadow-none btn-sm mx-1 mb-2">Rechercher</button>
                            </div>
                        </form>
                    </div>

                    <div class="table-container">
                        <table id="dataTable" class="table table-hover">
                            <tr>
                                <th>Nom article</th>
                                <th>Catégorie</th>
                                <th>Quantité</th>
                                <th>Prix unitaire</th>
                                <th>Date fabrication</th>
                                <th>Date expiration</th>
                                <?php if ($_SESSION['role'] == 1): ?>
                                    <th>Action</th>
                                <?php endif; ?>
                            </tr>
                            <tbody>
                                <?php 
                                    if (isset($_POST['searchArticle']) && !empty($_POST['articleSearchValue'])) {
                                        $articles = [];
                                        $articles = searchArticles();
                                    }
                                    foreach($articles as $article): 
                                ?>
                                <tr>
                                    <td><?= $article["nom_article"] ?></td>
                                    <td><?= $article["categorie"] ?></td>
                                    <td><?= $article["quantite"] ?></td>
                                    <td><?= $article["prix_unitaire"] ?></td>
                                    <td>
                                        <?php
                                            $dateTime = new DateTime($article["date_fabrication"]);
                                            $formattedDate = $dateTime->format('Y-m-d');
                                            echo $formattedDate;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $dateTime = new DateTime($article["date_expiration"]);
                                            $formattedDate = $dateTime->format('Y-m-d');
                                            echo $formattedDate;
                                        ?>
                                    </td>
                                    <?php if ($_SESSION['role'] == 1): ?>
                                    <td>
                                        <div class="d-flex">
                                            <a href="./editArticle.php?article_id=<?= $article['id'] ?>" title="Modifier cet article">
                                                <i class='bx bxs-edit text-primary fs-4'></i>
                                            </a>
                                            <form action="" method="post">
                                                <input type="hidden" name="articleId" value="<?= $article['id'] ?>">
                                                <button type="submit" name="deleteArticle" title="Supprimer cet article"
                                                    style="background:none;border-style: none;"
                                                    onClick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');"
                                                    >
                                                    <i class='bx bxs-trash text-danger fs-4'></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <?php endif; ?>

                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination controls -->
                    <nav class="d-flex justify-content-end">
                        <ul class="pagination">
                            <?php if($page > 1): ?>
                                <li class="page-item"><a class="page-link shadow-none" href="?page=<?= $page - 1 ?>">Précédent</a></li>
                            <?php endif; ?>
                            <?php for($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>"><a class="page-link shadow-none" href="?page=<?= $i ?>"><?= $i ?></a></li>
                            <?php endfor; ?>
                            <?php if($page < $totalPages): ?>
                                <li class="page-item"><a class="page-link shadow-none" href="?page=<?= $page + 1 ?>">Suivant</a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
                <?php else: ?>
                    <h5 class="text-secondary my-5 mx-auto">Aucun article encore</h5>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php include('../../assets/footer.php'); ?>
</div>

<script>
      $(document).ready(function() {
        $("#searchArticle").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#dataTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>

<script src="../../public/js/sidebar.js"></script>
<script src="../../public/js/bootstrap.js"></script>
<script src="../../public/js/jquery.min.js"></script>

</body>
</html>
