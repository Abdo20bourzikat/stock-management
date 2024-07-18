<?php 
    $pageTitle = "Ajouter un client";
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
                <div class="card shadow-sm rounded border-0 h-100" style="background-color: #e3f2fd;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div class="d-flex justify-content-between align-items-center w-100 mb-2">
                            <p class="fs-4 mb-0">
                                Liste des clients <br> <?= $countClients ?>
                            </p>
                            <i class="bi bi-people-fill fs-1"></i>
                        </div>
                    </div>
                </div>
            </a>

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
        </div>

    </div>
    <hr>
    <div class="row mt-3">
        <div class="col-md-4">
            <?php include '../../assets/alert.php'; ?>
            <div class="card shadow mb-5">
                <div class="card-header">
                    <div class="d-flex">
                        <i class='bx bx-user-plus text-secondary fs-4'></i> <h5 class="text-secondary">Ajouter un Client</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="form-group mb-1">
                            <label for="clientName" class="col-form-label text-secondary">Nom</label>
                            <input id="clientName" name="clientName" type="text" class="form-control shadow-none" placeholder="nom de client..." required>
                        </div>
                        <div class="form-group mb-1">
                            <label for="lastName" class="col-form-label text-secondary">Prénom</label>
                            <input id="lastName" name="lastName" type="text" class="form-control shadow-none" placeholder="prénom de client..." required>
                        </div>
                        <div class="form-group mb-1">
                            <label for="email" class="col-form-label text-secondary">Email</label>
                            <input type="email" id="email" name="email" class="form-control shadow-none" placeholder="email...">
                        </div>
                        <div class="form-group mb-1">
                            <label for="phone" class="col-form-label text-secondary">Téléphone</label>
                            <input type="text" id="phone" name="phone" class="form-control shadow-none" placeholder="téléphone...">
                        </div>
                        <div class="form-group mb-2">
                            <label for="address" class="col-form-label text-secondary">adresse</label>
                            <input type="text" id="address" name="address" class="form-control shadow-none" placeholder="adresse...">
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm shadow-none">Valider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
           
            <?php
                if (count($limitedClients) > 0):
            ?>
            <div class="card shadow mb-5">
                <div class="card-header">
                    <div class="d-flex">
                        <i class='bx bx-history text-secondary fs-4'></i> <h5 class="text-secondary">Clients récents</h5>
                    </div>
                </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <tr>
                                <th>Client</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Adresse</th>
                                <th>Nombre de ventes</th>
                                <th>Date-Création</th>
                            </tr>
                            <tbody>
                            <?php foreach($limitedClients as $client): ?>
                                <tr>
                                    <td><?= $client['nom'] . ' ' . $client["prenom"] ?></td>
                                    <td><?= $client["email"] ?></td>
                                    <td><?= $client["telephone"] ?></td>
                                    <td><?= $client["adresse"] ?></td>
                                    <td class="text-center"><?= $client["totalSales"] ?></td>
                                    <td>
                                        <?php
                                            $dateTime = new DateTime($client["date_creation"]);
                                            $formattedDate = $dateTime->format('Y-m-d');
                                            echo $formattedDate;
                                        ?>
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
                <h5 class="text-secondary my-5 mx-auto">Aucun client encore</h5>
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
