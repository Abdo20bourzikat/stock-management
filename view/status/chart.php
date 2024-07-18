<?php 
    $pageTitle = "Analyse - Graphiques";
    include '../../model/loginModel.php';
    accessPermission();
    include '../../model/chartModel.php';
    include '../../assets/sidebar.php';

    if (isset($_POST['date1']) && isset($_POST['date2'])) {
        $filtredData = filterData($_POST);
        $startDate = $filtredData['date1'];
        $endDate = $filtredData['date2'];
        $salesData = getSalesDataWithLimit($startDate, $endDate);
    } else {
        $salesData = getSalesDataWithLimit();
    }


    if ($_SESSION['role'] == 0):
            
?>
                
    <div class="col-md-12 mt-5 card shadow">
        <h5 class="text-danger my-5 mx-auto">Vous n'avez pas l'autorisation pour ce processus !</h5>
    </div>

<?php else: ?>

<div class="container">
    <div class="row mt-2">
        <div class="col-md-6">
            <div class="d-flex">
                <a href="./status.php" class="col-md-6" style="text-decoration:none;">
                    <div class="card shadow-sm rounded border-0 h-100" style="background-color: #e3f2fd; color: #000;">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <p class="fs-5" style="color: #1e88e5; margin-bottom: 0.5rem;">
                                Retour au statut
                            </p>
                            <p class="text-center mb-0">
                                <i class="bi bi-bar-chart-line text-success fs-1"></i>
                            </p>
                        </div>
                    </div>
                </a>
                <a href="../dashboard.php" class="col-md-6 mx-1" style="text-decoration:none;">
                    <div class="card shadow-sm rounded border-0 h-100" style="background-color: #e3f2fd; color: #000;">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <p class="fs-5" style="color: #1e88e5; margin-bottom: 0.5rem;">
                                Tableau de bord
                            </p>
                            <p class="text-center mb-0">
                            <i class="bi bi-house-door text-success fs-1"></i>
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            <hr>

            <div class="card shadow">
                <div class="card-header">
                <i class="bi bi-filter-square mx-1"></i>Filtrer
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="col-md-12 mb-2 form-group">
                            <label for="date1" class="form-label">Date 1</label>
                            <input type="date" name="date1" class="form-control shadow-none" required>
                        </div>
                        <div class="col-md-12 mb-3 form-group">
                            <label for="date2" class="form-label">Date 2</label>
                            <input type="date" name="date2" class="form-control shadow-none" required>
                        </div>
                        <button type="submit" name="filterSalesChart" class="btn btn-primary btn-sm shadow-none">Filtrer</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow">
                <?php if (count($salesData) > 0): ?>
                <div class="card-header">
                    <i class="bi bi-box-seam mx-1"></i>Ventes Récentes
                </div>
                <div class="card-body">
                    <div style="min-height: 355px; max-height: 355px; overflow-y: auto;">
                        <table class="table table-streped table-hover">
                            <tr>
                                <th>Nom Article</th>
                                <th>Quantité</th>
                                <th>Prix</th>
                                <th>Date de Vente</th>
                            </tr>
                            <?php foreach ($salesData as $sale): ?>
                                <tr>
                                    <td><?= htmlspecialchars($sale['nom_article']); ?></td>
                                    <td class="text-center"><?= htmlspecialchars($sale['quantite']); ?></td>
                                    <td><?= htmlspecialchars($sale['prix']); ?></td>
                                    <td>
                                        <?php
                                            $dateTime = new DateTime($sale["date_vente"]);
                                            $formattedDate = $dateTime->format('Y-m-d');
                                            echo $formattedDate;
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
                <?php else: ?>
                    <h5 class="text-secondary my-5 mx-auto">Aucun vente encore</h5>
                <?php endif; ?>
            </div>
        </div>


    </div>
    <hr>

    <div class="row mt-3 mb-5">
        <h3 class="text-secondary">Ventes Quotidiennes</h3>
        <hr style="width: 200px">
        <?php
            renderChart($dailySales, 'day', 'Daily Sales', 'dailySalesChart');
        ?>
    </div>
    <hr>
    <div class="row mt-5 mb-5">
        <h3 class="text-secondary">Ventes Hebdomadaires</h3>
        <hr style="width: 200px">
        <?php
            renderChart($weeklySales, 'week', 'Weekly Sales', 'weeklySalesChart');
        ?>

    </div>
    <hr>
    <div class="row mt-5 mb-5">
        <h3 class="text-secondary">Ventes Mensuelles</h3>
        <hr style="width: 200px">
        <?php
            renderChart($monthlySales, 'month', 'Monthly Sales', 'monthlySalesChart');
        ?>
    </div>
    <?php include('../../assets/footer.php'); ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../../public/js/sidebar.js"></script>
<script src="../../public/js/bootstrap.js"></script>
<script src="../../public/js/jquery.min.js"></script>

</body>
</html>

<?php endif; ?>