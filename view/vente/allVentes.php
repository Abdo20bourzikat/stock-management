<?php
    $pageTitle = "Liste des ventes";
    include '../../model/loginModel.php';
    accessPermission();
    include '../../model/venteModel.php';
    include '../../assets/sidebar.php'; 
?>

<div class="container">
    <div class="row mt-3">
        <div class="col-md-4">
            <?php include '../../assets/alert.php'; ?>
        </div>

        <div class="col-md-12">
        <a href="./addVente.php" class="btn btn-default btn my-2 shadow-none" style="background-color: #e3f2fd; color: #000;"><< Précédent</a>
            <?php
                if (count($allSales) > 0):
            ?>
            <div class="card shadow mb-5">
                <?php
                    if (count($allSales) > 0):
                ?>
                <div class="card-header">
                    <div class="d-flex">
                        <i class='bx bx-list-ul fs-4'></i><h5>Liste des Ventes(<?= $countSales ?>)</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-5">
                        <form action="" method="post">
                            <div class="d-flex">
                                <input type="text" id="searchVente" name="saleSearchValue" class="form-control shadow-none mb-2 flex-right" placeholder="rechercher des clients..." required>
                                <button type="submit" name="searchSale" class="btn btn-primary shadow-none btn-sm mx-1 mb-2">Rechercher</button>
                            </div>
                        </form>
                    </div>

                    <div class="table-container">
                        <table id="dataTable" class="table table-hover">
                            <tr>
                                <th>Client</th>
                                <th>Article</th>
                                <th>Quantité</th>
                                <th>Prix</th>
                                <th>Date-vente</th>
                                <th>Action</th>
                            </tr>
                            <tbody>
                            <?php 
                                if (isset($_POST['searchSale'])) {
                                    $allSales = [];
                                    $allSales = searchSales();
                                }
                                foreach($allSales as $sale): 
                            ?>
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
                                        <div class="d-flex">
                                            <a href="recuVente.php?vente_id=<?= $sale['id'] ?>" title="Reçu de la vente">
                                                <i class='bx bx-receipt fs-4'></i>
                                            </a>
                                            <?php if ($_SESSION['role'] == 1): ?>
                                            <a href="./editVente.php?vente_id=<?= $sale['id'] ?>" title="Modifier cette vente">
                                                <i class='bx bxs-edit text-primary fs-4 mx-2'></i>
                                            </a>
                                            <a onClick="annulerVente(<?= $sale["id"] ?>, <?= $sale["idArticle"] ?>, <?= $sale["quantity"] ?>)" title="Désactivé cette vente">
                                                <i class='bx bx-stop-circle fs-4 text-danger'></i>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
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
                    <h5 class="text-secondary my-5 mx-auto">Aucun vente encore</h5>
                <?php endif; ?>
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
            window.location.href = "../../model/offSale.php?idVente=" + idVente + "&idArticle=" + idArticle + "&quantite=" + quantite
        }
    }

      $(document).ready(function() {
        $("#searchVente").on("keyup", function() {
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
