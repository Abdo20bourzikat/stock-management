<?php
    $pageTitle = "Les ventes désactivées";
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
            <?php
                if (count($salesOff) > 0):
            ?>
            <div class="card shadow mb-5">
                <div class="card-header">
                    <div class="d-flex">
                        <i class='bx bx-list-ul fs-4'></i>
                        <h5>Les ventes désactivées(<?= $countSalesOff ?>)</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-5">
                        <div class="d-flex">
                            <input type="text" id="searchVente" class="form-control shadow-none mb-2 flex-right" placeholder="rechercher des clients...">
                            <button class="btn btn-primary shadow-none btn-sm mx-1 mb-2">Rechercher</button>
                        </div>
                    </div>
                    <table id="dataTable" class="table table-striped table-hover">
                        <tr>
                            <th>Client</th>
                            <th>Article</th>
                            <th>Quantité</th>
                            <th>Prix</th>
                            <th>Date-vente</th>
                            <th>Action</th>
                        </tr>
                        <?php 
                            foreach($salesOff as $sale): 
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
                                        <a href="./editVente.php?vente_id=<?= $sale['id'] ?>" title="Modifier cette vente">
                                        <i class="bi bi-pencil-square text-primary fs-4"></i>
                                        </a>
                                        <form action="" method="post">
                                            <input type="hidden" name="saleId" value="<?= $sale['id'] ?>">
                                            <button type="submit" name="activeSale" title="Activée cette vente"
                                                style="background:none;border-style: none;"
                                                onClick="return confirm('Êtes-vous sûr de vouloir activée cette vente?');"
                                                >
                                                <i class="bi bi-caret-right-square-fill text-secondary fs-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>

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
                    <a href="./addVente.php" class="btn btn-secondary btn-sm mt-2 shadow-none"><< Précédent</a>
                    
                </div>
            </div>
            <?php  
                else:
            ?>
            <div class="card shadow">
                <h5 class="text-secondary my-5 mx-auto">Aucun vente désactivée encore</h5>
            </div>
            <?php  
                endif;
            ?>
        </div>
    </div>
    <?php include('../../assets/footer.php'); ?>

</div>

<script>
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
