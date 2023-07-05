<x-master>
    <x-slot name="title">Charts</x-slot>

    <x-slot name="content_area">
        <!--start row-->
        <div class="row">
            <div class="col-12 col-lg-8 mx-auto">
                <x-widgets.card-component cardTitle='Inbound Calls' cardHeaderVisible='1' btnVisible='0' btnName=''
                    btnClass='' btnIcon=''>
                    <div class="chart-container1">
                        <canvas id="inbound"></canvas>
                    </div>
                </x-widgets.card-component>
            </div>

            <div class="col-12 col-lg-8 mx-auto">
                <x-widgets.card-component cardTitle='Outbound Calls' cardHeaderVisible='1' btnVisible='0' btnName=''
                    btnClass='' btnIcon=''>
                    <div class="chart-container1">
                        <canvas id="outbound"></canvas>
                    </div>
                </x-widgets.card-component>
            </div>
        </div>
    </x-slot>

    @push('js')
        <script>
            $(document).ready(function() {
                inbound();
                outbound();
            });

            function inbound() {
                var ctx = document.getElementById('inbound').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: [<?php echo "'" . implode("','", $data['inboundCalls']['days']) . "'"; ?>],
                        datasets: [{
                                label: 'Offered',
                                data: [<?php echo "'" . implode("','", $data['inboundCalls']['totalInbound']) . "'"; ?>],
                                backgroundColor: "transparent",
                                borderColor: "#0d6efd",
                                pointRadius: "0",
                                borderWidth: 4
                            },
                            {
                                label: 'Answered',
                                data: [<?php echo "'" . implode("','", $data['inboundCalls']['totalAnswered']) . "'"; ?>],
                                backgroundColor: "transparent",
                                borderColor: "#17a00e",
                                pointRadius: "0",
                                borderWidth: 4
                            },
                            {
                                label: 'Drop',
                                data: [<?php echo "'" . implode("','", $data['inboundCalls']['totalDrop']) . "'"; ?>],
                                backgroundColor: "transparent",
                                borderColor: "#f41127",
                                pointRadius: "0",
                                borderWidth: 4
                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        legend: {
                            display: true,
                            labels: {
                                fontColor: '#585757',
                                boxWidth: 40
                            }
                        },
                        tooltips: {
                            enabled: false
                        },
                        scales: {
                            xAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    fontColor: '#585757'
                                },
                                gridLines: {
                                    display: true,
                                    color: "rgba(0, 0, 0, 0.07)"
                                },
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    fontColor: '#585757'
                                },
                                gridLines: {
                                    display: true,
                                    color: "rgba(0, 0, 0, 0.07)"
                                },
                            }]
                        }
                    }
                });
            }


            function outbound() {
                var ctx = document.getElementById('outbound').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: [<?php echo "'" . implode("','", $data['outboundCalls']['days']) . "'"; ?>],
                        datasets: [{
                                label: 'Dialed',
                                data: [<?php echo "'" . implode("','", $data['outboundCalls']['totalOutbound']) . "'"; ?>],
                                backgroundColor: "transparent",
                                borderColor: "#0d6efd",
                                pointRadius: "0",
                                borderWidth: 4
                            },
                            {
                                label: 'Answered',
                                data: [<?php echo "'" . implode("','", $data['outboundCalls']['totalAnswered']) . "'"; ?>],
                                backgroundColor: "transparent",
                                borderColor: "#17a00e",
                                pointRadius: "0",
                                borderWidth: 4
                            },
                            {
                                label: 'Drop',
                                data: [<?php echo "'" . implode("','", $data['outboundCalls']['totalDrop']) . "'"; ?>],
                                backgroundColor: "transparent",
                                borderColor: "#f41127",
                                pointRadius: "0",
                                borderWidth: 4
                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        legend: {
                            display: true,
                            labels: {
                                fontColor: '#585757',
                                boxWidth: 40
                            }
                        },
                        tooltips: {
                            enabled: false
                        },
                        scales: {
                            xAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    fontColor: '#585757'
                                },
                                gridLines: {
                                    display: true,
                                    color: "rgba(0, 0, 0, 0.07)"
                                },
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    fontColor: '#585757'
                                },
                                gridLines: {
                                    display: true,
                                    color: "rgba(0, 0, 0, 0.07)"
                                },
                            }]
                        }
                    }
                });
            }
        </script>
    @endpush
</x-master>
