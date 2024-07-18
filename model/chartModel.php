<?php

    function getSalesDataWithLimit($startDate = null, $endDate = null) {
        global $cnx;

        // Default to the last month if no dates are provided
        if (is_null($startDate) && is_null($endDate)) {
            $endDate = date('Y-m-d');
            $startDate = date('Y-m-d', strtotime('-1 month'));
        }

        // Get sales data with article names, ordered by latest to newest
        $stmt = $cnx->prepare("
            SELECT v.id, a.nom_article, v.quantite, v.prix, v.date_vente
            FROM vente v
            JOIN article a ON v.id_article = a.id
            WHERE v.date_vente BETWEEN :startDate AND :endDate
            AND v.etat = '1'
            ORDER BY v.date_vente DESC
        ");
        $stmt->bindValue(':startDate', $startDate);
        $stmt->bindValue(':endDate', $endDate);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Chart part 
    function getSalesData($startDate, $endDate) {
        global $cnx;
        $stmt = $cnx->prepare("SELECT date_vente, quantite, prix FROM vente WHERE date_vente BETWEEN :start AND :end");
        $stmt->execute([
            'start' => $startDate->format('Y-m-d'), 
            'end' => $endDate->format('Y-m-d')
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function aggregateDataByDay($salesData) {
        $dailySales = [];
        foreach ($salesData as $sale) {
            $date = (new DateTime($sale['date_vente']))->format('Y-m-d');
            if (!isset($dailySales[$date])) {
                $dailySales[$date] = 0;
            }
            $dailySales[$date] += $sale['quantite'] * $sale['prix'];
        }
        return $dailySales;
    }

    function aggregateDataByWeek($salesData) {
        $weeklySales = [];
        foreach ($salesData as $sale) {
            $date = (new DateTime($sale['date_vente']))->format('Y-W');
            if (!isset($weeklySales[$date])) {
                $weeklySales[$date] = 0;
            }
            $weeklySales[$date] += $sale['quantite'] * $sale['prix'];
        }
        return $weeklySales;
    }

    function aggregateDataByMonth($salesData) {
        $monthlySales = [];
        foreach ($salesData as $sale) {
            $date = (new DateTime($sale['date_vente']))->format('Y-m');
            if (!isset($monthlySales[$date])) {
                $monthlySales[$date] = 0;
            }
            $monthlySales[$date] += $sale['quantite'] * $sale['prix'];
        }
        return $monthlySales;
    }

    function renderChart($data, $label, $title, $canvasId) {
        $labels = array_keys($data);
        $values = array_values($data);

        echo "<canvas id='$canvasId'></canvas>";
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var ctx = document.getElementById('$canvasId').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: " . json_encode($labels) . ",
                        datasets: [{
                            label: '$label',
                            data: " . json_encode($values) . ",
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        title: {
                            display: true,
                            text: '$title'
                        },
                        scales: {
                            xAxes: [{
                                type: 'time',
                                time: {
                                    unit: '$label'
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            });
        </script>";
    }

    // Fetch data
    if (isset($_POST['filterSalesChart'])) {
        $filterData = filterData($_POST);

        // Convert to DateTime objects
        $date1 = new DateTime($filterData['date1']);
        $date2 = new DateTime($filterData['date2']);
        $salesData = getSalesData($date1, $date2);
    } else {
        $firstDate = new DateTime('first day of January ' . date('Y'));
        $lastDate = new DateTime('last day of December ' . date('Y'));
        $salesData = getSalesData($firstDate, $lastDate);
    }

    // Aggregate data
    $dailySales = aggregateDataByDay($salesData);
    $weeklySales = aggregateDataByWeek($salesData);
    $monthlySales = aggregateDataByMonth($salesData);




?>
