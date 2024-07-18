<?php 
    $pageTitle = "Analyse - Principale";
    include '../../model/loginModel.php';
    accessPermission();
    include '../../model/statusModel.php';
    include '../../assets/sidebar.php';

    $stockData = getStockDataWithLimit();
    
    if ($_SESSION['role'] == 0):
            
?>
                
    <div class="col-md-12 mt-5 card shadow">
        <h5 class="text-danger my-5 mx-auto">Vous n'avez pas l'autorisation pour ce processus !</h5>
    </div>

<?php else: ?>

<div class="container">

    <div class="row mt-2 mb-3">
        <div class="d-flex mt-2">
            <div class="card shadow-sm col-md-3 mx-2 rounded border-0 h-100" style="background-color: #e3f2fd; color: #000;">
                <div class="card-body d-flex flex-column justify-content-center">
                    <p class="fs-5" style="color: #1e88e5;">
                        <?php if (isset($_POST['filterStatus'])):?>
                            <?= $calculateProfit['startDate'] ?> - <?= $calculateProfit['endDate'] ?> <i class="bi bi-arrow-up-right"></i>
                        <?php else:?>
                        Mois dernier <i class="bi bi-arrow-up-right"></i>
                        <?php endif ?>
                    </p>
                    <div class="d-flex justify-content-between align-items-center w-100 mb-2">
                        <?php if (intval($calculateProfit['profit']) < 0): ?>
                            <p class="text-danger fs-5 mb-0">
                                Bénéfice <br><?= $calculateProfit['profit'] ?> MAD
                            </p>
                            <i class="bi bi-graph-up text-danger fs-1"></i>

                        <?php else: ?>
                            <p class="text-success fs-5 mb-0">
                                Bénéfice <br><?= $calculateProfit['profit'] ?> MAD
                            </p>
                            <i class="bi bi-graph-up text-success fs-1"></i>
                        <?php endif; ?>
                    </div>
                </div>
            </div>


            <div class="card shadow-sm col-md-3 rounded border-0 h-100" style="background-color: #e3f2fd; color: #000;">
                <div class="card-body d-flex flex-column justify-content-center">
                    <p class="fs-5" style="color: #1e88e5;">
                        <?php if (isset($_POST['filterStatus'])):?>
                            <?= $calculateProfit['startDate'] ?> - <?= $calculateProfit['endDate'] ?> <i class="bi bi-arrow-up-right"></i>
                        <?php else:?>
                        Mois dernier <i class="bi bi-arrow-up-right"></i>
                        <?php endif ?>
                    </p>
                    <div class="d-flex justify-content-between align-items-center w-100 mb-2">
                        <?php if (intval($calculateProfit['totalSales']) < 0): ?>
                            <p class="text-danger fs-5 mb-0">
                                Chiffre d'affaires total <br><?= $calculateProfit['totalSales'] ?> MAD
                            </p>
                            <i class="bi bi-cart3 text-danger fs-1"></i>
                        <?php else: ?>
                            <p class="text-success fs-5 mb-0">
                                Chiffre d'affaires total <br><?= $calculateProfit['totalSales'] ?> MAD
                            </p>
                            <i class="bi bi-cart3 text-success fs-1"></i>

                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm col-md-3 mx-2 rounded border-0 h-100" style="background-color: #e3f2fd; color: #000;">
                <div class="card-body d-flex flex-column justify-content-center">
                    <p class="fs-5" style="color: #1e88e5;">
                        <?php if (isset($_POST['filterStatus'])):?>
                            <?= $calculateProfit['startDate'] ?> - <?= $calculateProfit['endDate'] ?> <i class="bi bi-arrow-up-right"></i>
                        <?php else:?>
                        Mois dernier <i class="bi bi-arrow-up-right"></i>
                        <?php endif ?>
                    </p>
                    <div class="d-flex justify-content-between align-items-center w-100 mb-2">
                        <?php if (intval($calculateProfit['totalCost']) < 0): ?>
                            <p class="text-danger fs-5 mb-0">
                            Coût Total <br><?= $calculateProfit['totalCost'] ?> MAD
                            </p>
                            <i class="bi bi-cash-stack text-danger fs-1"></i>

                        <?php else: ?>
                            <p class="text-success fs-5 mb-0">
                            Coût Total <br><?= $calculateProfit['totalCost'] ?> MAD
                            </p>
                            <i class="bi bi-cash-stack text-success fs-1"></i>

                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <a href="./chart.php" class="col-md-3 mx-2" style="text-decoration:none;">
                <div class="card shadow-sm rounded border-0 h-100" style="background-color: #e3f2fd; color: #000;">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <p class="fs-5" style="color: #1e88e5; margin-bottom: 0.5rem;">
                            Graphique des Ventes
                        </p>
                        <p class="text-center mb-0">
                            <i class="bi bi-graph-up-arrow text-success fs-1"></i>
                        </p>
                    </div>
                </div>
            </a>


        </div>

    </div>
    <hr>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header">
                <i class="bi bi-filter-square mx-1"></i>Filtrer
                </div>
                <div class="card-body">
                    <div style="min-height: 300px; max-height: 300px;">
                        <form action="" method="post">
                            <div class="col-md-12 mb-3 form-group">
                                <label for="statusDate1" class="form-label">Date 1</label>
                                <input type="date" name="statusDate1" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-12 mb-3 form-group">
                                <label for="statusDate2" class="form-label">Date 2</label>
                                <input type="date" name="statusDate2" class="form-control shadow-none" required>
                            </div>
                            <button type="submit" name="filterStatus" class="btn btn-primary btn-sm shadow-none">Filtrer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header">
                    <i class="bi bi-box-seam mx-1"></i>Stock Actuel des Articles
                </div>
                <div class="card-body">
                    <div style="min-height: 300px; max-height: 300px; overflow-y: auto;">
                        <table class="table table-hover">
                            <tr>
                                <th>Nom Article</th>
                                <th>Stock Actuel</th>
                            </tr>
                            <?php foreach ($stockData as $stock): ?>
                                <tr>
                                    <td><?= $stock['nom_article']; ?></td>
                                    <td><?= $stock['current_stock']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>

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