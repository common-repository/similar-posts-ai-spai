<?php
/* @var array $impStat */
/* @var array $clicksStat */

$impStat7 = array_reverse($impStat['7']);
$impData = [];
array_walk($impStat7, static function ($value) use (&$impData) {
    $impData[] = $value['imp'];
});

$clicksStat7 = array_reverse($clicksStat['7']);
$clicksData = [];
array_walk($clicksStat7, static function ($value) use (&$clicksData) {
    $clicksData[] = $value['clicks'];
});
?>

<div class="">
    <div class="spai-row">
        <div class="spai-col-2">
            <div class="mt20">
                <div>
                    <canvas class="spai-chart" id="impressionsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="spai-col-2">
            <div class="mt20">
                <div>
                    <canvas class="spai-chart" id="clicksChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    const impLabels = <?php echo json_encode(array_keys($impStat7)); ?>;


    const impData = {
        labels: impLabels,
        datasets: [{
            label: 'Impressions',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: <?php echo json_encode($impData); ?>,
        }]
    };

    const impConfig = {
        type: 'line',
        data: impData,
        options: {
            scales: {
                y: {
                    min: 0,
                    ticks: {
                        precision: 0
                    }
                }
            }
        },
    };

    const myChart = new Chart(
        document.getElementById('impressionsChart'),
        impConfig
    );
</script>

<script>
    const clicksLabels = <?php echo json_encode(array_keys($clicksStat7)); ?>;


    const clicksData = {
        labels: clicksLabels,
        datasets: [{
            label: 'Clicks',
            backgroundColor: 'rgb(75,164,39)',
            borderColor: 'rgb(75,164,39)',
            data: <?php echo json_encode($clicksData); ?>,
        }]
    };

    const clicksConfig = {
        type: 'line',
        data: clicksData,
        options: {
            scales: {
                y: {
                    min: 0,
                    ticks: {
                        precision: 0
                    }
                }
            }
        },
    };

  const impChart = new Chart(
    document.getElementById('clicksChart'),
    clicksConfig
  );
</script>

<style>
    .spai-chart {
        width: 100%;
        max-width: 700px;
        max-height: 400px;
    }
</style>
