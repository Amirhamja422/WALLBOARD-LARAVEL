<x-master>
    <x-slot name="title">Dashboard</x-slot>

    <x-slot name="content_area">
        <!--start row-->
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <x-widgets.counter-component :data="$data" />
        </div>
        <!--end row-->

        <!--start row-->
        <div class="row">
            <div class="col-12 col-lg-8">
                <x-widgets.card-component cardTitle='Calls Overview' cardHeaderVisible='1' btnVisible='0' btnName=''
                    btnClass='' btnIcon=''>
                    <x-widgets.bar-chart-component />

                    <x-slot name="more_data">
                        <x-widgets.general-component />
                    </x-slot>
                </x-widgets.card-component>
            </div>

            <div class="col-12 col-lg-4">
                <x-widgets.card-component cardTitle='Trending Products' cardHeaderVisible='1' btnVisible='0'
                    btnName='' btnClass='' btnIcon=''>
                    <x-widgets.doughnut-chart-component />

                    <x-slot name="more_data">
                        <x-widgets.list-count-component />
                    </x-slot>
                </x-widgets.card-component>
            </div>
        </div>
        <!--end row-->
    </x-slot>

    @push('js')
        <script>
            $(document).ready(function() {
                barChart();
                doughnut();
            });

            function barChart() {
                var ctx = document.getElementById("chart1").getContext('2d');

                var gradientStroke1 = ctx.createLinearGradient(0, 0, 0, 300);
                gradientStroke1.addColorStop(0, '#6078ea');
                gradientStroke1.addColorStop(1, '#17c5ea');

                var gradientStroke2 = ctx.createLinearGradient(0, 0, 0, 300);
                gradientStroke2.addColorStop(0, '#ff8359');
                gradientStroke2.addColorStop(1, '#ffdf40');

                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [<?php echo "'" . implode("','", $data['chart']['days']) . "'"; ?>],
                        datasets: [{
                            label: 'Inbound',
                            data: [<?php echo implode(',', $data['chart']['totalInbound']); ?>],
                            borderColor: gradientStroke1,
                            backgroundColor: gradientStroke1,
                            hoverBackgroundColor: gradientStroke1,
                            pointRadius: 0,
                            fill: false,
                            borderWidth: 0
                        }, {
                            label: 'Outbound',
                            data: [<?php echo implode(',', $data['chart']['totalOutbound']); ?>],
                            borderColor: gradientStroke2,
                            backgroundColor: gradientStroke2,
                            hoverBackgroundColor: gradientStroke2,
                            pointRadius: 0,
                            fill: false,
                            borderWidth: 0
                        }]
                    },

                    options: {
                        maintainAspectRatio: false,
                        legend: {
                            position: 'bottom',
                            display: false,
                            labels: {
                                boxWidth: 8
                            }
                        },
                        tooltips: {
                            displayColors: false,
                        },
                        scales: {
                            xAxes: [{
                                barPercentage: .5
                            }]
                        }
                    }
                });
            }

            function doughnut() {
                var ctx = document.getElementById("chart2").getContext('2d');

                var gradientStroke1 = ctx.createLinearGradient(0, 0, 0, 300);
                gradientStroke1.addColorStop(0, '#fc4a1a');
                gradientStroke1.addColorStop(1, '#f7b733');

                var gradientStroke2 = ctx.createLinearGradient(0, 0, 0, 300);
                gradientStroke2.addColorStop(0, '#4776e6');
                gradientStroke2.addColorStop(1, '#8e54e9');


                var gradientStroke3 = ctx.createLinearGradient(0, 0, 0, 300);
                gradientStroke3.addColorStop(0, '#ee0979');
                gradientStroke3.addColorStop(1, '#ff6a00');

                var gradientStroke4 = ctx.createLinearGradient(0, 0, 0, 300);
                gradientStroke4.addColorStop(0, '#42e695');
                gradientStroke4.addColorStop(1, '#3bb2b8');

                var myChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ["Jeans", "T-Shirts", "Shoes", "Lingerie"],
                        datasets: [{
                            backgroundColor: [
                                gradientStroke1,
                                gradientStroke2,
                                gradientStroke3,
                                gradientStroke4
                            ],
                            hoverBackgroundColor: [
                                gradientStroke1,
                                gradientStroke2,
                                gradientStroke3,
                                gradientStroke4
                            ],
                            data: [25, 80, 25, 25],
                            borderWidth: [1, 1, 1, 1]
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        cutoutPercentage: 75,
                        legend: {
                            position: 'bottom',
                            display: false,
                            labels: {
                                boxWidth: 8
                            }
                        },
                        tooltips: {
                            displayColors: false,
                        }
                    }
                });
            }
        </script>
    @endpush
</x-master>
