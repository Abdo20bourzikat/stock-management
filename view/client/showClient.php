<?php 
    $pageTitle = "Affichage du client";
    include '../../model/loginModel.php';
    accessPermission();
    include '../../model/clientModel.php';
    include '../../assets/sidebar.php'; 
    $countSales = countSalesFunction();

?>

   

<div class="container">
    <div class="row">
        <div class="d-flex mt-2">
            <a href="./allClients.php" class="col-md-3" style="text-decoration:none;">
                <div class="card shadow-sm rounded border-0 h-100 bg-primary text-white">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div class="d-flex justify-content-between align-items-center w-100 mb-2">
                            <p class="fs-4 mb-0">
                                Clients <br> <?= $countClients ?>
                            </p>
                            <i class="bi bi-people-fill text-white fs-1"></i>
                        </div>
                    </div>
                </div>
            </a>

            <a href="../vente/allVentes.php" class="col-md-3 mx-2" style="text-decoration:none;">
                <div class="card shadow-sm rounded border-0 h-100 bg-info text-white">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div class="d-flex justify-content-between align-items-center w-100 mb-2">
                            <p class="fs-4 mb-0">
                                Ventes <br> <?= $countSales ?>
                            </p>
                            <i class="bi bi-cart-fill text-white fs-1"></i>
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
               
                <?php
                    if (isset($_GET['client_id'])) {
                        $clientId = $_GET['client_id'];
                        $client = getDataById('client', $clientId);
                        $limit2 = 10;

                        $page2 = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $offset2 = ($page2 - 1) * $limit2;

                        $clientSales = getClientSales($clientId, $limit2, $offset2);

                        $totalClients2 = getClientSalesCount($clientId);
                        $totalPages2 = ceil($totalClients2 / $limit2);

                        $dateTime = new DateTime($client["date_creation"]);
                        $formattedDate = $dateTime->format('Y-m-d');
                    }
                ?>
                <div class="card-header bg-light">
                    <h5> <?= $client['nom'] . ' ' . $client['prenom'] ?></h5>
                </div>
                <div class="card-body">
                    <p><i class="bi bi-envelope-fill" style="margin-right: 3px;"></i>Email: <?= $client['email'] ?></p>
                    <p><i class="bi bi-telephone-fill"></i> Téléphone: <?= $client['telephone'] ?></p>
                    <p class="text-dark"><i class="bi bi-geo-alt-fill"></i> Adresse: <?= $client['adresse'] ?></p>
                    <p><i class="bi bi-calendar-check-fill" style="margin-right: 3px;"></i>Date de création: <?= $formattedDate ?></p>
                    
                    <hr>
                    <div class="d-flex">
                        <a href="./editClient.php?client_id=<?= $client['id'] ?>" class="btn btn-primary btn-sm">
                            <i class='bx bxs-edit'></i>Modifier
                        </a>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="col-md-8">
            <?php
                if (count($clientSales) > 0):
            ?>
            <div class="card shadow mb-5">
                <div class="card-header">
                    <div class="d-flex">
                        <i class='bx bx-list-ul fs-4'></i> <h5>Liste des Ventes</h5>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                        $aggregatedSales = [];

                        foreach ($clientSales as $item) {
                            $key = $item["nom_article"] . '-' . (new DateTime($item["date_vente"]))->format('Y-m-d');
                            
                            if (!isset($aggregatedSales[$key])) {
                                $aggregatedSales[$key] = [
                                    "nom_article" => $item["nom_article"],
                                    "quantite" => $item["quantite"],
                                    "prix" => $item["prix"],
                                    "date_vente" => (new DateTime($item["date_vente"]))->format('Y-m-d'),
                                    "sale_id" => [$item["sale_id"]]
                                ];
                            } else {
                                $aggregatedSales[$key]["quantite"] += $item["quantite"];
                                $aggregatedSales[$key]["prix"] += $item["prix"];
                                $aggregatedSales[$key]["sale_id"][] = $item["sale_id"];
                            }
                        }
                        ?>
                    <table class="table table-striped table-hover">
                        <tr>
                            <th>Article</th>
                            <th>Quantité</th>
                            <th>Prix</th>
                            <th>Date-vente</th>
                            <th>Action</th>
                        </tr>
                        
                        <?php 
                           
                            foreach($aggregatedSales as $item): ?>
                            <tr>
                                <td><?= $item["nom_article"] ?></td>
                                <td><?= $item["quantite"] ?></td>
                                <td><?= $item["prix"] ?></td>
                                <td>
                                    <?php
                                         $dateTime = new DateTime($item["date_vente"]);
                                         $formattedDate = $dateTime->format('Y-m-d');
                                         echo $formattedDate;
                                     ?>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="../vente/editVente.php?vente_id=<?= $item['sale_id'] ?>">
                                            <i class='bx bxs-edit text-primary fs-4'></i>
                                        </a>
                                        <form action="" method="post">
                                            <input type="hidden" name="saleId" value="<?= $item['sale_id'] ?>">
                                            <button type="submit" name="deleteSale" 
                                                style="background:none;border-style: none;"
                                                onClick="return confirm('Êtes-vous sûr de vouloir supprimer cette vente?');"
                                                >
                                                <i class='bx bxs-trash text-danger fs-4'></i>
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
                            <?php if($page2 > 1): ?>
                                <li class="page-item"><a class="page-link shadow-none" href="?client_id=<?= $clientId ?>&page=<?= $page2 - 1 ?>">Précédent</a></li>
                            <?php endif; ?>
                            <?php for($i = 1; $i <= $totalPages2; $i++): ?>
                                <li class="page-item <?= ($page2 == $i) ? 'active' : '' ?>"><a class="page-link shadow-none" href="?client_id=<?= $clientId ?>&page=<?= $i ?>"><?= $i ?></a></li>
                            <?php endfor; ?>
                            <?php if($page2 < $totalPages2): ?>
                                <li class="page-item"><a class="page-link shadow-none" href="?client_id=<?= $clientId ?>&page=<?= $page2 + 1 ?>">Suivant</a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
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

    <script src="../../public/js/sidebar.js"></script>
    <script src="../../public/js/bootstrap.js"></script>
    <script src="../../public/js/jquery.min.js"></script>

</body>
</html>
