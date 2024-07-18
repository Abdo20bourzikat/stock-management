<?php

    function calculateProfit($startDate = null, $endDate = null) {
        global $cnx;

        // If no dates are provided, calculate profit for the last month by default
        if (!$startDate || !$endDate) {
            $startDate = date('Y-m-01', strtotime('-1 month'));
            $endDate = date('Y-m-t', strtotime('-1 month'));
        }

        // Calculate total sales revenue
        $stmt = $cnx->prepare("SELECT SUM(quantite * prix) as total_sales FROM vente WHERE etat = '1' AND date_vente BETWEEN :start AND :end");
        $stmt->execute(['start' => $startDate, 'end' => $endDate]);
        $totalSales = $stmt->fetchColumn();

        // Calculate total cost of goods sold (COGS)
        $stmt = $cnx->prepare("SELECT SUM(quantite * prix) as total_cost FROM commande WHERE date_commande BETWEEN :start AND :end");
        $stmt->execute(['start' => $startDate, 'end' => $endDate]);
        $totalCost = $stmt->fetchColumn();

        // Calculate profit
        $profit = $totalSales - $totalCost;

        // Format values to two decimal places without thousands separator
        $totalSalesFormatted = number_format($totalSales, 2, '.', '');
        $totalCostFormatted = number_format($totalCost, 2, '.', '');
        $profitFormatted = number_format($profit, 2, '.', '');

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalSales' => $totalSalesFormatted,
            'totalCost' => $totalCostFormatted,
            'profit' => $profitFormatted
        ];
    }

    if (isset($_POST['filterStatus'])) {
        $filtredData = filterData($_POST);
        $calculateProfit = calculateProfit($filtredData['statusDate1'], $filtredData['statusDate2']);
    } else {
        $calculateProfit = calculateProfit();
    }


    function getStockDataWithLimit() {
        global $cnx;
        
        // Get total quantity purchased with article names
        $stmt = $cnx->prepare("
            SELECT id, nom_article, quantite as current_stock
            FROM article
        ");
        $stmt->execute();
        $stockData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
       
        return $stockData;
    }

?>
