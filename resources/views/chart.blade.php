<!-- resources/views/chart.blade.php -->
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>نمودار خطی</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<h2 style="text-align:center;">نمودار پیشرفت دانش‌آموز</h2>
<h4 style="text-align:center;">رگرسیون: {{$regression}}</h4>
<canvas id="myChart" width="400" height="200"></canvas>

<script>
    const ctx = document.getElementById('myChart').getContext('2d');

    const myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($xs) !!},
            datasets: [

                {
                    label: 'منتخب',
                    data: {!! json_encode($ys) !!},
                    borderColor: 'rgba(153, 102, 255, 1)',
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</body>
</html>
