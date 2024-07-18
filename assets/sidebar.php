<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php
            if (isset($pageTitle)) {
                echo $pageTitle;
            }
        ?>
    </title>


    <link rel="stylesheet" href="../../public/css/sidebar.css">
    <link rel="stylesheet" href="../../public/css/bootstrap.css">
    <link rel="stylesheet" href="../../public/css/boxicons.min.css">
    <link rel="stylesheet" href="../../public/css/main.css">

    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Include Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body id="body-pd" >
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
            <a href="../client/addClient.php" class="nav_link">
                <i class='bx bx-user nav_icon'></i>
                <span class="nav_name">Client</span>
            </a>
            <a href="../article/addArticle.php" class="nav_link">
                <i class='bx bxs-box nav_icon'></i>
                <span class="nav_name">Article</span>
            </a>
            <a href="../vente/addVente.php" class="nav_link">
            <i class='bx bxs-cart-download nav_icon'></i>
            <span class="nav_name">Vente</span>
            </a>
            <a href="../provider/addProvider.php" class="nav_link">
                <i class='bx bxs-user-detail nav_icon'></i>
                <span class="nav_name">Fournisseur</span>
            </a>
            <a href="../command/addCommand.php" class="nav_link">
                <i class='bx bxs-cart-add nav_icon'></i>
                <span class="nav_name">Commande</span>
            </a>
            <?php if ($_SESSION['role'] == 1): ?>
                <a href="../status/status.php" class="nav_link">
                    <i class='bx bx-bar-chart-alt-2 nav_icon'></i>
                    <span class="nav_name">Stats</span>
                </a>
                <a href="../settings/setting.php" class="nav_link">
                    <i class='bx bx-cog nav_icon'></i>
                    <span class="nav_name">Paramètre</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <a href="../logout.php" class="nav_link">
        <i class='bx bx-log-out nav_icon'></i>
        <span class="nav_name">SignOut</span>
    </a>
</nav>

    </div>
    <!-- Container Main start-->
    <!-- <div class="height-100 bg-light">
        <h4>Main Components</h4>
    </div>  -->


