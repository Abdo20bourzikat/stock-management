<?php
    $pageTitle = "Reçu du client";
    include '../../model/loginModel.php';
    accessPermission();
    include '../../model/clientModel.php';
    include '../../assets/sidebar.php';

    $contactInfo = getDataById('contactinfo', 1);

    if (isset($_GET['client_id'])) {
        $clientId = $_GET['client_id'];
    
        // Base query
        $query = "SELECT vente.id as `sale_id`, vente.quantite as `quantity`, vente.prix as `price`, vente.date_vente,
                article.nom_article as `article`, article.prix_unitaire as `prix_unitaire`, client.id as `client_id`,
                CONCAT(client.nom, ' ', client.prenom) as `clientName`, client.email as `email`,
                client.telephone as `phone`, client.adresse as `address`
                FROM vente
                JOIN article ON vente.id_article = article.id
                JOIN client ON vente.id_client = client.id 
                WHERE client.id = :clientId";
    
        // Add date filter if needed
        if (isset($_POST['filterReceiptSales']) && isset($_POST['receiptDate1']) && isset($_POST['receiptDate2'])) {
            $receiptDate1 = $_POST['receiptDate1'];
            $receiptDate2 = $_POST['receiptDate2'];
            $query .= " AND vente.date_vente BETWEEN :date1 AND :date2";
        }
    
        $query .= " GROUP BY vente.id DESC";
    
        // Prepare the final query
        $request = $cnx->prepare($query);
    
        // Bind parameters
        $request->bindParam(':clientId', $clientId, PDO::PARAM_INT);
    
        if (isset($_POST['filterReceiptSales']) && isset($_POST['receiptDate1']) && isset($_POST['receiptDate2'])) {
            $request->bindParam(':date1', $receiptDate1, PDO::PARAM_STR);
            $request->bindParam(':date2', $receiptDate2, PDO::PARAM_STR);
        }
    
        // Execute the query
        $request->execute();
        $clients = $request->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
?>


<div id="mainContainer" class="container">
        <div id="filterReceipt" class="row mt-3">
            <div class="card shadow mb-2">
                <div class="card-header">
                    <i class="bi bi-filter-square mx-1"></i>Filtrer
                </div>
                <div class="card-body p-4">
                    <form action="" method="post">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="" class="form-label">Date 1</label>
                                <input type="date" name="receiptDate1" class="form-control shadow-none">
                            </div>
                            <div class="col-md-6">
                                <label for="" class="form-label">Date 2</label>
                                <input type="date" name="receiptDate2" class="form-control shadow-none">
                            </div>
                        </div>
                        <button type="submit" name="filterReceiptSales" class="btn btn-primary btn-sm shadow-none">Filtrer</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="row mt-1">
            <div class="card shadow col-md-12" style="min-height: 600px;">
                <div class="same-space m-5">
                    <h3>Alpha-Stock</h3>
                    <div>
                        <p class="fs-5">Reçu N°: #<?= $clients[0]['client_id'] ?></p>
                        <p class="fs-5">Date: <?= date('d/m/y H:i') ?></p>
                    </div>
                </div>
                <div class="mx-2">
                    <p class="">Nom: <?= $clients[0]['clientName'] ?></p>
                    <p class="">Email: <?= $clients[0]['email'] ?></p>
                    <p class="">Téléphone: <?= $clients[0]['phone'] ?></p>
                    <p class="">Adresse: <?= $clients[0]['address'] ?></p>
                </div>


            <div class="row mt-3 mx-2 mb-5">
                <div id="scrollReceipt" class="table-container" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-hover">
                        <tr>
                            <th>Article</th>
                            <th>Quantité</th>
                            <th>Prix unitaire</th>
                            <th>Prix total</th>
                            <th>Date vente</th>
                            <th>Supprimer</th>
                        </tr>
                        <?php
                            $total = 0;
                            foreach ($clients as $client): 
                            $total += $client["price"];
                        ?>
                        <tr>
                            <td><?= $client['article'] ?></td>
                            <td><?= $client["quantity"] ?></td>
                            <td><?= $client["prix_unitaire"] ?></td>
                            <td><?= $client["price"] ?></td>
                            <td>
                            <?php
                                $dateTime = new DateTime($client['date_vente']);
                                $formattedDate = $dateTime->format('Y-m-d');
                                echo $formattedDate;
                            ?>
                            </td>
                            <td>
                                <button onclick="deleteRecord(<?= $client['sale_id'] ?>, <?= $client['price'] ?>)" style="background: none; border-style: none;">
                                    <i class="bi bi-trash text-danger fs-4"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>

            <div class="row mt-2 mb-4 mx-2">
                <div class="d-flex justify-content-between">
                    <div>
                        <a id="cancelReceipt" href="./allClients.php" class="btn btn-secondary btn-sm shadow-none mx-1">Annuler</a>
                        <button id="printReceipt" class="btn btn-primary btn-sm shadow-none"><i class="bi bi-printer"></i> Imprimer</button>
                    </div>
                    <p id="totalReceipt" class="fs-5">
                        <strong>Total: <?= $total ?></strong>
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
                        <i class="bi bi-geo-alt mx-1"></i>Adresse:  <?= $contactInfo['address'] ?>
                    </p>
                </div>
            </div>

            <script>
                var printReceipt = document.querySelector('#printReceipt');
                printReceipt.addEventListener("click", () => {
                    // Call the hiddenItem function to hide elements before printing
                    hiddenItem();
                    window.print();

                    // Add a timeout to ensure the print dialog is completed before reloading
                    setTimeout(() => {
                        location.reload();
                    }, 100);
                });

                function hiddenItem() {
                    var scrollReceipt = document.getElementById('scrollReceipt');
                    var mainContainer = document.getElementById('mainContainer');
                    
                    // Remove overflow and max-height styles
                    scrollReceipt.style.maxHeight = 'none';
                    scrollReceipt.style.overflowY = 'visible';
                    
                    // Remove Bootstrap container class
                    mainContainer.classList.remove('container');

                    var sidebarBody = document.querySelector('#sidebarBody');
                    var printReceipt = document.querySelector('#printReceipt');
                    if (sidebarBody) sidebarBody.style.display = 'none';
                    if (printReceipt) printReceipt.style.display = 'none';
                }
            </script>
            <script>
                function deleteRecord(saleId, price) {
                    // Find the row to delete
                    var row = document.querySelector('button[onclick="deleteRecord(' + saleId + ', ' + price + ')"]').closest('tr');

                    // Remove the row from the table
                    row.parentNode.removeChild(row);

                    // Update the total
                    var totalElement = document.querySelector('p.fs-5 strong');
                    var total = parseFloat(totalElement.textContent.split(': ')[1]);
                    total -= price;
                    totalElement.innerHTML = 'Total: <strong>' + total.toFixed(2) + '</strong>';
                }
            </script>

            </div>
        </div>
    </div>
</body>
</html>

