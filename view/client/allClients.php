<?php 
    $pageTitle = "Liste des clients";
    include '../../model/loginModel.php';
    accessPermission();
    include '../../model/clientModel.php';
    include '../../assets/sidebar.php';
    $searchData = searchFunction();
?>

<div class="container">
    <div class="row mt-3">
        <div class="col-md-4">
            <?php include '../../assets/alert.php'; ?>
        </div>

        <div class="col-md-12">
            <a href="./addClient.php" class="btn btn-default btn my-2 shadow-none" style="background-color: #e3f2fd; color: #000;"><< Précédent</a>
            <div class="card shadow mb-5">
                <?php if (count($clients) > 0): ?>
                <div class="card-header">
                    <div class="d-flex">
                        <i class='bx bx-list-ul text-secondary fs-4'></i><h5 class="text-secondary">Liste des Clients(<?= $countClients ?>)</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-5">
                        <form action="" method="post">
                            <div class="d-flex">
                                <input type="text" id="searchClient" name="inputValue" class="form-control shadow-none mb-2 flex-right" placeholder="rechercher des clients..." required>
                                <button type="submit" name="searchClient" class="btn btn-primary shadow-none btn-sm mx-1 mb-2">Rechercher</button>
                            </div>
                        </form>
                    </div>
                    <div class="table-container">
                        <table id="dataTable" class="table table-hover">
                            <tr>
                                <th>Client</th>
                                <th>Téléphone</th>
                                <th>Email</th>
                                <th>Adresse</th>
                                <th>Nombre-vente</th>
                                <th>Date-création</th>
                                <th>Action</th>
                            </tr>
                            <tbody>
                            <?php
                                if (isset($_POST['searchClient'])) {
                                    $clients = [];
                                    $clients = $searchData;
                                }
                                foreach($clients as $client): 
                            ?>
                                <tr>
                                    <td><?= $client['nom'] . ' ' . $client["prenom"]  ?></td>
                                    <td><?= $client["telephone"] ?></td>
                                    <td><?= $client["email"] ?></td>
                                    <td><?= $client["adresse"] ?></td>
                                    <th class="text-center"><?= $client["totalSales"] ?></th>
                                    <td>
                                        <?php
                                            $dateTime = new DateTime($client["date_creation"]);
                                            $formattedDate = $dateTime->format('Y-m-d');
                                            echo $formattedDate;
                                        ?>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="recuClient.php?client_id=<?= $client["client_id"] ?>" title="Reçu de ce client">
                                                <i class='bx bx-receipt fs-4'></i>
                                            </a>
                                            <a href="./showClient.php?client_id=<?= $client['client_id'] ?>" title="Afficher ce client">
                                                <i class='bx bx-desktop text-info mx-1 fs-4'></i>
                                            </a>
                                            <?php if ($_SESSION['role'] == 1): ?>
                                            <a href="./editClient.php?client_id=<?= $client['client_id'] ?>" title="Modifier ce client">
                                                <i class='bx bxs-edit text-primary fs-4'></i>
                                            </a>
                                            <form action="" method="post">
                                                <input type="hidden" name="clientId" value="<?= $client['client_id'] ?>">
                                                <button type="submit" name="deleteClient" title="Supprimer ce client"
                                                    style="background:none;border-style: none;"
                                                    onClick="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?');"
                                                    >
                                                    <i class='bx bxs-trash text-danger fs-4'></i>
                                                </button>
                                            </form>
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
                    <h5 class="text-secondary my-5 mx-auto">Aucun client encore</h5>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php include('../../assets/footer.php'); ?>
</div>

<script>
      $(document).ready(function() {
        $("#searchClient").on("keyup", function() {
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
