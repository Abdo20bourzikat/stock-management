<?php 
    $pageTitle = "Reçu de vente";
    include '../../model/loginModel.php';
    accessPermission();
    include '../../model/venteModel.php';
    include '../../assets/sidebar.php';

    $contactInfo = getDataById('contactinfo', 1);

    if (isset($_GET['vente_id'])) {
        $venteId = $_GET['vente_id'];       

        $query = "SELECT vente.id as `sale_id`, vente.quantite as `quantity`, vente.prix as `price`, vente.date_vente,
                article.nom_article as `article`, article.prix_unitaire as `prix_unitaire`, CONCAT(client.nom, ' ', client.prenom) as `clientName`, client.email as `email`,
                client.telephone as `phone`, client.adresse as `address`
                FROM vente
                JOIN article ON vente.id_article = article.id
                JOIN client ON vente.id_client = client.id 
                AND vente.id = :sale_id";

        $request = $cnx->prepare($query);
        $request->bindParam(':sale_id', $venteId, PDO::PARAM_INT);
        $request->execute();
        $vente = $request->fetch(PDO::FETCH_ASSOC);
    }
    
?>

<div id="mainContainer" class="container">
    <div class="row mt-1">
        <div class="card shadow col-md-12" style="min-height: 600px;">
            <div class="same-space m-5">
                <h3>Alpha-Stock</h3>
                <div>
                    <p class="fs-5">Reçu N°: #<?= $vente['sale_id'] ?></p>
                    <p class="fs-5">Date: <?= date('d/m/y H:i', strtotime($vente['date_vente'])) ?></p>
                </div>
            </div>
            <div class="mx-2">
                <p>Nom: <?= $vente['clientName'] ?></p>
                <p>Email: <?= $vente['email'] ?></p>
                <p>Téléphone: <?= $vente['phone'] ?></p>
                <p>Adresse: <?= $vente['address'] ?></p>
            </div>

            <div class="row mt-3 mx-2 mb-5">
                <table class="table table-hover">
                    <tr>
                        <th>Article</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th>Prix total</th>
                    </tr>
                    <tr>
                        <td><?= $vente['article'] ?></td>
                        <td><?= $vente["quantity"] ?></td>
                        <td><?= $vente["prix_unitaire"] ?></td>
                        <td><?= $vente["price"] ?></td>
                    </tr>
                </table>
            </div>

            <div class="row mt-2 mb-4 mx-2">
                <div class="d-flex justify-content-between">
                    <div>
                        <a id="cancelReceipt" href="./allClients.php" class="btn btn-secondary btn-sm shadow-none mx-1">Annuler</a>
                        <button id="printReceipt" class="btn btn-primary btn-sm shadow-none"><i class="bi bi-printer"></i> Imprimer</button>
                    </div>
                    <p id="totalReceipt" class="fs-5">
                        <strong>Total: <?= $vente["price"] ?></strong>
                    </p>
                </div>
            </div>

            <hr>
            <div class="row">
                <div class="d-flex mb-4">
                    <p class="mx-2"><i class="bi bi-shop"></i>Entreprise:  <?= $contactInfo['company'] ?></p>
                    <p class="mx-2">
                        <i class="bi bi-telephone"></i>Téléphone:  <?= $contactInfo['phone'] ?>
                    </p>
                    <p class="mx-2">
                        <i class="bi bi-envelope mx-1"></i>Email:  <?= $contactInfo['email'] ?>
                    </p>
                    <p class="mx-2">
                        <i class="bi bi-geo-alt"></i>Adresse:  <?= $contactInfo['address'] ?>
                    </p>
                </div>
            </div>

        </div>
    </div>

    <?php include('../../assets/footer.php'); ?>


</div>

    <script>
        var printReceipt = document.querySelector('#printReceipt');
        printReceipt.addEventListener("click", () => {
            window.print();

             // Add a timeout to ensure the print dialog is completed before reloading
             setTimeout(() => {
                location.reload();
            }, 100);
        });

        function hiddenItem() {
            var mainContainer = document.getElementById('mainContainer');
            mainContainer.classList.remove('container');
            var sidebarBody = document.qeurySelector('#sidebarBody');
            var printReceipt = document.querySelector('#printReceipt');

            sidebarBody.style.display = none;
            printReceipt.style.display = none;
        }
    </script>
    <script src="../../public/js/sidebar.js"></script>
    <script src="../../public/js/bootstrap.js"></script>
    <script src="../../public/js/jquery.min.js"></script>

</body>
</html>
