<?php
    include '../model/loginModel.php';
    if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        header('Location: login.php');
        exit();
    }

    $countCommands = countDataTable('commande');
    $countSales = countSalesFunction();
    $countArticles = countDataTable('article');
    $countProviders = countDataTable('fournisseur');

    $recentSales = getSales(6);
    $mostSales = getLimitedClients();

    $getOutOfStockItems = getOutOfStockItems();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    
    <link rel="stylesheet" href="../public/css/sidebar.css">
    <link rel="stylesheet" href="../public/css/bootstrap.css">
    <link rel="stylesheet" href="../public/css/boxicons.min.css">
    <link rel="stylesheet" href="../public/css/main.css">

    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Include Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


</head>

<body id="body-pd" class="bg-light">
    <header class="header d-flex justify-space-between" id="header">
        <div class="header_toggle"> 
            <i class='bx bx-menu' id="header-toggle"></i> 
        </div>
        <?= $_SESSION['name']. ' ' . $_SESSION['lastname'];  ?>
    </header>
    <div class="d-flex">

        <!-- <div class="header_img"> <img src="https://i.imgur.com/hczKIze.jpg" alt=""> </div> -->
    <div class="l-navbar" id="nav-bar">
    <nav class="nav">
    <div>
        <a href="#" class="nav_logo">
            <i class='bx bx-layer nav_logo-icon'></i>
            <span class="nav_logo-name">BBBootstrap</span>
        </a>
        <div class="nav_list">
            <a href="../dashboard.php" class="nav_link active">
                <i class='bx bxs-dashboard nav_logo-icon'></i>            
                <span class="nav_name">Dashboard</span>
            </a>
            <a href="./client/addClient.php" class="nav_link">
                <i class='bx bx-user nav_icon'></i>
                <span class="nav_name">Client</span>
            </a>
            <a href="./article/addArticle.php" class="nav_link">
                <i class='bx bxs-box nav_icon'></i>
                <span class="nav_name">Article</span>
            </a>
            <a href="./vente/addVente.php" class="nav_link">
                <i class='bx bxs-cart-download nav_icon'></i>
                <span class="nav_name">Vente</span>
            </a>
            <a href="./provider/addProvider.php" class="nav_link">
                <i class='bx bxs-user-detail nav_icon'></i>
                <span class="nav_name">Fournisseur</span>
            </a>
            <a href="./command/addCommand.php" class="nav_link">
            <i class='bx bxs-cart-add nav_icon'></i>
            <span class="nav_name">Commande</span>
            </a>
            <?php if ($_SESSION['role'] == 1): ?>
            <a href="./status/status.php" class="nav_link">
                <i class='bx bx-bar-chart-alt-2 nav_icon'></i>
                <span class="nav_name">Stats</span>
            </a>
            <a href="./settings/setting.php" class="nav_link">
                <i class='bx bx-cog nav_icon'></i>
                <span class="nav_name">Paramètre</span>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <a href="./logout.php" class="nav_link">
        <i class='bx bx-log-out nav_icon'></i>
        <span class="nav_name">SignOut</span>
    </a>
    </nav>

    </div>
    <!-- Container Main start-->
    <!-- <div class="height-100 bg-light">
        <h4>Main Components</h4>
    </div>  -->





    <div class="container">
    <div class="row mb-3">
        <div class="d-flex mt-2">

            <a href="./allCommands.php" class="col-md-3 mx-2" style="text-decoration:none;">
                <div class="card shadow-sm rounded border-0 h-100" style="background-color: #e3f2fd;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div class="d-flex justify-content-between align-items-center w-100 mb-2">
                            <p class="fs-4 mb-0">
                                Commandes <br><?= $countCommands ?>
                            </p>
                            <i class="bi bi-cart4 fs-1"></i>
                        </div>
                    </div>
                </div>
            </a>
            <a href="../provider/allProviders.php" class="col-md-3" style="text-decoration:none;">
                <div class="card shadow-sm rounded border-0 h-100" style="background-color: #e3f2fd;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div class="d-flex justify-content-between align-items-center w-100 mb-2">
                            <p class="fs-4 mb-0">
                                Ventes <br><?= $countSales ?>
                            </p>
                            <i class="bi bi-cart-fill fs-1"></i>
                        </div>
                    </div>
                </div>
            </a>
            <a href="../provider/allProviders.php" class="col-md-3 mx-2" style="text-decoration:none;">
                <div class="card shadow-sm rounded border-0 h-100" style="background-color: #e3f2fd;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div class="d-flex justify-content-between align-items-center w-100 mb-2">
                            <p class="fs-4 mb-0">
                                Articles <br><?= $countArticles ?>
                            </p>
                            <i class="bi bi-archive-fill fs-1"></i>
                        </div>
                    </div>
                </div>
            </a>
            <a href="../provider/allProviders.php" class="col-md-3" style="text-decoration:none;">
                <div class="card shadow-sm rounded border-0 h-100" style="background-color: #e3f2fd;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div class="d-flex justify-content-between align-items-center w-100 mb-2">
                            <p class="fs-4 mb-0">
                                Fournisseurs <br><?= $countProviders ?>
                            </p>
                            <i class="bi bi-person-lines-fill fs-1"></i>
                        </div>
                    </div>
                </div>
            </a>

        </div>

    </div>
    <hr>

    <div class="row mb-4">
        <div class="col-md-7">
            
            <?php
                if (count($mostSales) > 0):
            ?>
            <div class="card shadow">
                <div class="card-header">
                    <div class="d-flex">
                        <i class='bx bxs-right-top-arrow-circle text-secondary fs-4'></i>
                        <h5 class="text-secondary">L'article le plus vendu</h5>
                    </div>                    
                </div>
                <div class="card-body">
                    <div style="min-height: 300px; max-height: 300px; overflow-y: auto;">
                        <table class="table table-hover">
                            <tr>
                                <th>Article</th>
                                <th>Quantité en stock</th>
                                <th>Quantité vendue</th>
                            </tr>
                            <?php foreach($mostSales as $item): ?>
                                <tr>
                                    <td><?= $item["article"] ?></td>
                                    <td class="px-5"><?= $item["stockQuantity"] ?></td>
                                    <td class="px-5"><?= $item["totalSales"] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
            <?php  
                else:
            ?>
            <div class="card shadow">
                <h5 class="text-secondary my-5 mx-auto">Aucun article vendu encore</h5>
            </div>
            <?php  
                endif;
            ?>


        </div>

        <div class="col-md-5">
            <?php if (count($getOutOfStockItems)> 0):  ?>
            <div class="card shadow">
                <div class="card-header">
                    <div class="d-flex">
                        <i class='bx bx-calendar-exclamation text-secondary fs-4'></i>
                        <h5 class="text-secondary">Articles épuisés</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div style="min-height: 300px; max-height: 300px; overflow-y: auto;">
                        <table class="table table-hover">
                            <tr>
                                <th>Article</th>
                            </tr>
                            <?php for ($i = 0; $i < count($getOutOfStockItems); $i++): ?>
                                <tr>
                                    <td><?= $getOutOfStockItems[$i]["nom_article"] ?></td>
                                </tr>
                            <?php endfor; ?>
                        </table>
                    </div>
                </div>
            </div>
            <?php else: ?>
                <div class="card shadow">
                    <h5 class="text-secondary my-5 mx-auto">Aucun article n'est épuisé pour le moment</h5>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <?php
                if (count($recentSales) > 0):
            ?>
            <div class="card shadow mb-3">
                <div class="card-header">
                    <div class="d-flex">
                        <i class='bx bx-history text-secondary fs-4'></i> <h5 class="text-secondary">Ventes Récentes</h5>
                    </div>                    
                </div>
                <div class="card-body">
                    <div style="min-height: 300px; max-height: 300px; overflow-y: auto;">

                        <table class="table table-hover">
                            <tr>
                                <th>Client</th>
                                <th>Article</th>
                                <th>Quantité</th>
                                <th>Prix</th>
                                <th>Date vente</th>
                            </tr>
                            <?php foreach($recentSales as $sale): ?>
                                <tr>
                                    <td><?= $sale['clientName'] ?></td>
                                    <td><?= $sale["article"] ?></td>
                                    <td class="px-5"><?= $sale["quantity"] ?></td>
                                    <td><?= $sale["price"] ?></td>
                                    <td>
                                        <?php
                                            $dateTime = new DateTime($sale['saleDate']);
                                            $formattedDate = $dateTime->format('Y-m-d');
                                            echo $formattedDate;
                                        ?>
                                    </td>
                                    
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
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

    <?php include('../assets/footer.php'); ?>
    </div>



    <script src="../public/js/sidebar.js"></script>
    <script src="../public/js/bootstrap.js"></script>
    <script src="../public/js/jquery.min.js"></script>

</body>
</html>
