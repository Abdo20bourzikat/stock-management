<?php 
    $pageTitle = "Liste des fournisseurs";
    include '../../model/loginModel.php';
    accessPermission();
    include '../../model/providerModel.php';
    include '../../assets/sidebar.php'; 

?>

<div class="container">
    <div class="row mt-3">
        <div class="col-md-4">
            <?php include '../../assets/alert.php'; ?>
        </div>

        <div class="col-md-12">
        <a href="./addProvider.php" class="btn btn-default btn my-2 shadow-none" style="background-color: #e3f2fd; color: #000;"><< Précédent</a>
            <div class="card shadow mb-5">
                <?php
                    if (count($providers) > 0):
                ?>
                <div class="card-header">
                    <div class="d-flex">
                        <i class='bx bx-list-ul text-secondary fs-4'></i><h5 class="text-secondary">Liste des Fournisseurs(<?= $countProviders ?>)</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-5">
                        <form action="" method="post">
                            <div class="d-flex">
                                <input type="text" id="searchProvider" name="providerSearchValue" class="form-control shadow-none mb-2 flex-right" placeholder="rechercher des fournisseuses..." required>
                                <button type="submit" name="searchProvider" class="btn btn-primary shadow-none btn-sm mx-1 mb-2">Rechercher</button>
                            </div>
                        </form>
                    </div>

                    <div class="table-container">
                        <table id="dataTable" class="table table-hover">
                            <tr>
                                <th>Fournisseur</th>
                                <th>Téléphone</th>
                                <th>Email</th>
                                <th>Adresse</th>
                                <th>Date-création</th>
                                <?php if ($_SESSION['role'] == 1): ?>
                                    <th>Action</th>
                                <?php endif; ?>
                            </tr>
                            <tbody>
                            <?php 
                                if (isset($_POST['searchProvider'])) {
                                    $providers = [];
                                    $providers = searchProvider();
                                }
                                foreach($providers as $provider): 
                            ?>
                                <tr>
                                    <td><?= $provider['nom'] . ' ' . $provider["prenom"]  ?></td>
                                    <td><?= $provider["telephone"] ?></td>
                                    <td><?= $provider["email"] ?></td>
                                    <td><?= $provider["adresse"] ?></td>
                                    <td>
                                        <?php
                                            $dateTime = new DateTime($provider["date_creation"]);
                                            $formattedDate = $dateTime->format('Y-m-d');
                                            echo $formattedDate;
                                        ?>
                                    </td>
                                    <?php if ($_SESSION['role'] == 1): ?>
                                        <td>
                                            <div class="d-flex">
                                                <!-- <a href="recuClient.php?fournisseur_id=<?= $provider["id"] ?>"><i class='bx bx-receipt fs-4'></i></a>
                                                <a href="./showClient.php?fournisseur_id=<?= $provider['id'] ?>">
                                                    <i class='bx bx-desktop text-info mx-1 fs-4'></i>
                                                </a> -->
                                                <a href="./editProvider.php?provider_id=<?= $provider['id'] ?>" title="Modifier ce fournisseur">
                                                    <i class='bx bxs-edit text-primary fs-4'></i>
                                                </a>
                                                <form action="" method="post">
                                                    <input type="hidden" name="providerId" value="<?= $provider['id'] ?>">
                                                    <button type="submit" name="deleteProvider" title="Supprimer ce fournisseur"
                                                    style="background:none;border-style: none;"
                                                    onClick="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?');"
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
                    <h5 class="text-secondary my-5 mx-auto">Aucun fournisseur encore</h5>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php include('../../assets/footer.php'); ?>
</div>

<script>
      $(document).ready(function() {
        $("#searchProvider").on("keyup", function() {
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
