<div style="margin: 0px 30%;"><canvas id="myChart" width="400px" height="400px"></canvas></div>
<script>
$(function () {
    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels:  ['客户','员工','商品','待处理订单','待配送订单','退货申请'],
            datasets: [{
                label: '数量',
                data: [{{$customer}}, {{$employe}}, {{$product}}, {{$pending}}, {{$delivery}}, {{$refund}}],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        // options: {
        //     scales: {
        //         yAxes: [{
        //             ticks: {
        //                 beginAtZero:true,
        //                 responsive:false
        //             }
        //         }]
        //     }

        // }
        options: {
            beginAtZero:true,
            responsive:false
        }
    });
});
</script>
