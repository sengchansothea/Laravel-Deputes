@php
    $caseTypes = myArrayCaseType();
    $totalCasesType1 = getCasesCountByType();
    $totalResolved = countResolvedCases();
    $totalInProgress = countInProgressCases();
    $totalInProgressCurrentYear = countInProgressCasesCurrentYear();
    $totalNewCases = countNewCases();
    $todayCases = countTodayCases();

    $casesByYear = getCasesCountByYear();
    $lineChartData = getCaseStatisticByYear();

    $caseCounts = [
        'វិវាទបុគ្គលប្រភេទទី១' => $totalCasesType1,
        'វិវាទបុគ្គលប្រភេទទី២' => getCasesCountByType(2),
        'វិវាទការងាររួម' => getCasesCountByType(3),
    ];

    $labels = [
        'វិវាទបុគ្គលប្រភេទទី១',
        'វិវាទបុគ្គលប្រភេទទី២',
        'វិវាទការងាររួម',
        //    "Pending",
        //    "Dismissed"
    ];
    $values = [
        $totalResolved,
        1,
        $totalInProgress,
        //    150,
        //    70
    ];
    $colors = [
        '#3399FF',
        '#3399FF',
        '#feb019',
        //    "#3399FF",
        //    "#00e396",
        //    "#feb019",
        //    "#ff4560",
    ];

    //$githubIssuesData = [
    //        ['x' => "Jan", 'y' => 3, 'z' => 2],
    //        ['x' => "Feb", 'y' => 3, 'z' => 1],
    //        ['x' => "Mar", 'y' => 1, 'z' => 2],
    //        ['x' => "April", 'y' => 2, 'z' => 2],
    //        ['x' => "May", 'y' => 2, 'z' => 4],
    //        ['x' => "June", 'y' => 3, 'z' => 2],
    //        ['x' => "July", 'y' => 0, 'z' => 2]
    //    ];
    $barChartData = getCasesLastSixMonths();
    $barchartOfficer = getTop5Officers();
    $barChartOfficerLabels = ['Resolved', 'Progressing']; // Change labels dynamically

    $barChartLabels = ['Resolved', 'Progressing']; // Change labels dynamically

    $pieChartData = [
        ['Category', 'Value'],
        ['Resolved', $totalResolved],
        ['Progressing', $totalInProgress],
        // ["Pending", 150],
        // ["Dismissed", 70]
    ];

    $pieChartColors = ['#3399FF', '#ff4560']; // Define Colors

    $topActiveOfficers = [
        ['label' => 'January1', 'value' => 15],
        ['label' => 'February2', 'value' => 20],
        ['label' => 'March', 'value' => 10],
        ['label' => 'April', 'value' => 25],
        ['label' => 'May', 'value' => 30],
        ['label' => 'June', 'value' => 18],
    ];

    $topActiveOfficers = [
        ['name' => 'Copper', 'density' => 10, 'color' => '#4466f2'],
        ['name' => 'Silver', 'density' => 12, 'color' => '#1ea6ec'],
        ['name' => 'Gold', 'density' => 14, 'color' => '#22af47'],
        ['name' => 'Platinum', 'density' => 16, 'color' => '#007bff'],
    ];

    $usersCreatedCases = getUsersWithCaseCount(); // Fetch top 5 officers with case data
    $casesByDomain = getCasesByDomain();

    //dd(getCaseStatisticByYear());

    // Format data for Morris Donut Chart
    $chartCreatedUsers = collect($usersCreatedCases)->map(function ($user) {
        return [
            'label' => $user['username'],
            'value' => $user['case_count'], // Use total case count as value
        ];
    });

    // Format data for Morris Donut Chart
    $chartCasesByDomain = collect($casesByDomain)->map(function ($user) {
        return [
            'label' => $user['domain_name'],
            'value' => $user['case_count'], // Use total case count as value
        ];
    });

    // Format data for Morris Donut Chart
    $chartCasesByYear = collect($casesByYear)->map(function ($user) {
        return [
            'label' => $user['year'],
            'value' => $user['case_count'], // Use total case count as value
        ];
    });

@endphp

<style>
    .stat-card {
        border: none;
        border-radius: 16px;
        color: #fff;
        padding: 25px 20px;
        position: relative;
        overflow: hidden;
        transition: all .3s ease;
        min-height: 160px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .stat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, .2);
    }

    .stat-card .icon {
        font-size: 50px;
        opacity: 0.2;
        position: absolute;
        top: 20px;
        right: 20px;
    }

    .stat-card h6 {
        font-size: 14px;
        /* subtitle smaller */
        margin-bottom: 12px;
        font-weight: 500;
        opacity: 0.9;
    }

    .stat-card h3 {
        font-size: 30px;
        /* main number slightly smaller for balance */
        font-weight: 700;
        margin: 0;
    }

    #user_created_cases text,
    #case-by-domain text
     {
        font-family: 'Siemreap', sans-serif !important;
    }

    @media (max-width: 768px) {
        .stat-card h3 {
            font-size: 26px;
        }

        .stat-card .icon {
            font-size: 40px;
        }
    }

    /* Gradient colors */
    .bg-pink {
        background: linear-gradient(135deg, #ff5f9e, #ff2e93);
    }

    .bg-green {
        background: linear-gradient(135deg, #28a745, #20c997);
    }

    .bg-yellow {
        background: linear-gradient(135deg, #f6c23e, #f39c12);
    }

    .bg-gray {
        background: linear-gradient(135deg, #6c757d, #495057);
    }

    .bg-red {
        background: linear-gradient(135deg, #e74a3b, #c0392b);
    }

    .bg-blue {
        background: linear-gradient(135deg, #4e73df, #224abe);
    }

    .header-blue {
        background-color: #1e88e5;
        /* blue */
        color: #fff;
    }

    /* CARD */
    .dashboard-card {
        border: none;
        border-radius: 18px;
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.08);
        transition: all 0.35s ease;
        animation: fadeUp 0.6s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 45px rgba(0, 0, 0, 0.12);
    }

    /* HEADER */
    .card-header {
        padding: 16px 22px;
        border-radius: 18px 18px 0 0;
        border: none;
    }

    .card-header h5 {
        margin: 0;
        font-weight: 700;
        color: #fff;
        letter-spacing: 0.4px;
    }

    /* HEADER COLORS */
    .header-purple {
        background: linear-gradient(135deg, #6f42c1, #9b5de5);
    }

    .header-green {
        background: linear-gradient(135deg, #198754, #20c997);
    }

    /* BODY */
    .chart-block {
        background: #fff;
        padding: 22px;
        border-radius: 0 0 18px 18px;
    }

    /* CHART */
    .chart-container {
        height: 330px;
    }

    #case-statistic-by-year,
    #user_created_cases {
        width: 100%;
        height: 100%;
    }

    /* ANIMATION */
    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>




{{-- {{ dd($totalInProgressCurrentYear) }} --}}

<x-admin.layout-main :adata="$adata">
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/chartist.css') }}">
        <style>
            #case-by-domain svg text {
                font-size: 10px !important;
                /*font-weight: bold; !* Optional *!*/
                color: #0b5ed7;
            }

            #chart-container svg text[fill="#007bff"] {
                fill: #007bff !important;
            }

            /* Blue */
            #chart-container svg text[fill="#008000"] {
                fill: #008000 !important;
            }

            /* Green */
            #chart-container svg text[fill="#FF0000"] {
                fill: #FF0000 !important;
            }

            /* Red */
        </style>
    </x-slot>
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="container py-4">
                <div class="row justify-content-center g-4">
                    <!-- Row 1 -->
                    <div class="col-lg-4 col-md-6">
                        <div class="stat-card bg-pink">
                            <i data-feather="database" class="icon"></i>
                            <h4>បណ្ដឹងសរុប</h4>
                            <h3>{{ $totalCasesType1 }}</h3>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="stat-card bg-green">
                            <i data-feather="lock" class="icon"></i>
                            <h4>បណ្ដឹងបានបញ្ចប់</h4>
                            <h3>{{ $totalResolved }}</h3>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="stat-card bg-yellow">
                            <i data-feather="activity" class="icon"></i>
                            <h4>បណ្ដឹងកំពុងដំណើរការ</h4>
                            <h3>{{ $totalInProgress }}</h3>
                        </div>
                    </div>

                    <!-- Row 2 -->
                    <div class="col-lg-4 col-md-6">
                        <div class="stat-card bg-gray">
                            <i data-feather="layers" class="icon"></i>
                            <h4>បណ្ដឹងក្នុងឆ្នាំនេះ</h4>
                            <h3>{{ $totalInProgressCurrentYear }}</h3>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="stat-card bg-red">
                            <i data-feather="arrow-up" class="icon"></i>
                            <h4>បណ្ដឹងថ្មីៗ</h4>
                            <h3>{{ $totalNewCases }}</h3>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="stat-card bg-blue">
                            <i data-feather="bell" class="icon"></i>
                            <h4>បណ្ដឹងថ្ងៃនេះ</h4>
                            <h3>{{ $todayCases }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- LINE CHART -->
                <div class="col-lg-6 col-sm-12 mb-4">
                    <div class="card dashboard-card">
                        <div class="card-header header-purple">
                            <h5>របាយការណ៌សំណុំរឿងតាមឆ្នាំនីមួយៗ</h5>
                        </div>
                        <div class="card-body chart-block">
                            <div class="chart-container">
                                <div id="case-statistic-by-year"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DONUT CHART -->
                <div class="col-lg-6 col-sm-12 mb-4">
                    <div class="card dashboard-card">
                        <div class="card-header header-green">
                            <h5>ចំនួនសំណុំរឿងដែលបញ្ចូលដោយ Users នីមួយៗ</h5>
                        </div>
                        <div class="card-body chart-block">
                            <div class="chart-container">
                                <div id="user_created_cases"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row">

                <!-- LAST 6 MONTHS -->
                <div class="col-sm-6">
                    <div class="card dashboard-card">
                        <!-- FIXED HEADER -->
                        <div class="card-header project-header" style="background-color: #a127c0; color: #fff;">
                            <h5 class="fw-bold m-0">
                                របាយការណ៌សំណុំរឿង ៦ ខែចុងក្រោយ
                            </h5>
                        </div>

                        <div class="card-body mt-3">
                            <div class="github-lg">

                                <!-- LEGEND -->
                                <div class="show-value-top d-flex">
                                    <div class="value-left d-inline-block me-3">
                                        <div class="square d-inline-block bg-success"></div>
                                        <span class="fw-bold text-success">
                                            សំណុំរឿងបានបញ្ចប់
                                        </span>
                                    </div>

                                    <div class="value-right d-inline-block">
                                        <div class="square d-inline-block bg-warning"></div>
                                        <span class="fw-bold text-warning">
                                            សំណុំរឿងកំពុងដំណើរការ
                                        </span>
                                    </div>
                                </div>

                                <!-- CHART -->
                                <div class="github-chart">
                                    <div class="flot-chart-placeholder" id="barchart_last6months"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- ACTIVE OFFICERS -->
                <div class="col-sm-6">
                    <div class="card dashboard-card">
                        <!-- FIXED HEADER -->
                        <div class="card-header project-header" style="background-color: #0d6efd; color: #fff;">
                            <h5 class="fw-bold m-0">
                                មន្ត្រីទទួលបន្ទុកដែលសកម្មក្នុងសំណុំរឿង
                            </h5>
                        </div>

                        <div class="card-body mt-3">
                            <div class="github-lg">

                                <!-- LEGEND -->
                                <div class="show-value-top d-flex">
                                    <div class="value-left d-inline-block me-3">
                                        <div class="square d-inline-block bg-primary"></div>
                                        <span class="fw-bold text-primary">
                                            សំណុំរឿងបានបញ្ចប់
                                        </span>
                                    </div>

                                    <div class="value-right d-inline-block">
                                        <div class="square d-inline-block bg-danger"></div>
                                        <span class="fw-bold text-danger">
                                            សំណុំរឿងកំពុងដំណើរការ
                                        </span>
                                    </div>
                                </div>

                                <!-- CHART -->
                                <div class="github-chart">
                                    <div class="flot-chart-placeholder" id="barchart_officers"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-6 col-sm-12">
                    <div class="card dashboard-card">
                        <div class="card-header" style="background-color: #298c8c; color:#9fc8c8">
                            <h5 class="fw-bold">បំណែងចែកសំណុំរឿងទៅតាមដែនសមត្ថកិច្ច</h5>
                        </div>
                        <div class="card-body chart-block">
                            <div class="flot-chart-container">
                                <div class="flot-chart-placeholder" id="case-by-domain"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-sm-12">
                    <div class="card dashboard-card">
                        <div class="card-header" style="background-color: #ffa226; color: #298c8c">
                            <h5 class="fw-bold">បំណែងចែកសំណុំរឿងទៅតាមឆ្នាំនមួយៗ</h5>
                        </div>
                        <div class="card-body chart-block">
                            <div class="flot-chart-container">
                                <div class="flot-chart-placeholder" id="case-by-year"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Task Distribution Section -->
            {{--            <div class="col-sm-6"> --}}
            {{--                <div class="card"> --}}
            {{--                    <div class="card-header project-header"> --}}
            {{--                        <div class="row"> --}}
            {{--                            <div class="col-sm-8"> --}}
            {{--                                <h5 class="blue">ប្រភេទសំណុំរឿង</h5> --}}
            {{--                            </div> --}}
            {{--                        </div> --}}
            {{--                    </div> --}}
            {{--                    <div class="card-body chart-block chart-vertical-center project-charts"> --}}
            {{--                        <canvas id="myDoughnutGraph"></canvas> --}}
            {{--                    </div> --}}
            {{--                </div> --}}
            {{--            </div> --}}
            <!-- End Task Distribution Section -->

        </div>
    </div>

    <x-slot name="moreAfterScript">
        <!-- Required Chart Libraries -->
        <script src="{{ rurl('assets/js/chart/chartist/chartist.js') }}"></script>
        <script src="{{ rurl('assets/js/chart/chartjs/chart.min.js') }}?v={{ time() }}"></script>
        <script src="{{ rurl('assets/js/chart/morris-chart/raphael.js') }}"></script>
        <script src="{{ rurl('assets/js/chart/morris-chart/morris.js') }}"></script>

        <script src="{{ rurl('assets/js/chart/google/google-chart-loader.js') }}"></script>
        <script src="{{ rurl('assets/js/chart/google/google-chart.js') }}"></script>

        <script src="{{ rurl('assets/js/counter/jquery.waypoints.min.js') }}"></script>
        <script src="{{ rurl('assets/js/counter/jquery.counterup.min.js') }}"></script>
        <script src="{{ rurl('assets/js/counter/counter-custom.js') }}"></script>


        <!-- Other Scripts (Removed Unused Comments) -->
        <script src="{{ rurl('assets/js/dashboard/project-custom.js') }}"></script>

        <!-- Doughnut Chart Initialization -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                /* ================= LINE CHART ================= */
                new Morris.Line({
                    element: "case-statistic-by-year",
                    data: {!! json_encode($lineChartData) !!},
                    xkey: "year",
                    ykeys: ["case_count", "resolved_count", "unresolved_count"],
                    labels: ["Total", "Resolved", "Progressing"],
                    lineColors: ["#0d6efd", "#20c997", "#dc3545"],
                    lineWidth: 3,
                    pointSize: 5,
                    pointFillColors: ["#ffffff"],
                    pointStrokeColors: ["#0d6efd", "#20c997", "#dc3545"],
                    smooth: true,
                    hideHover: "auto",
                    resize: true,
                    gridTextColor: "#6c757d",
                    gridTextSize: 12,
                    xLabelFormat: function(x) {
                        return x.getFullYear();
                    },
                    hoverCallback: function(index, options, content, row) {
                        return `
                <div style="
                    background:#ffffff;
                    padding:12px 14px;
                    border-radius:10px;
                    box-shadow:0 8px 20px rgba(0,0,0,0.15);
                    text-align:left;
                    font-family:'Noto Sans Khmer','Khmer OS Battambang','Hanuman',sans-serif;
                    font-size:14px;
                    line-height:1.7;
                ">
                    <div style="color:#0d6efd;font-weight:600;">
                        សរុប (Total): ${row.case_count}
                    </div>
                    <div style="color:#20c997;font-weight:600;">
                        ដោះស្រាយបាន (Resolved): ${row.resolved_count}
                    </div>
                    <div style="color:#dc3545;font-weight:600;">
                        មិនទាន់ដោះស្រាយបាន (Progressing): ${row.unresolved_count}
                    </div>
                </div>
            `;
                    }
                });

                /* ================= DONUT : USER CREATED CASES ================= */
                new Morris.Donut({
                    element: 'user_created_cases',
                    data: {!! json_encode($chartCreatedUsers) !!},
                    colors: ["#0dcaf0", "#dc3545", "#ffc107", "#20c997", "#6f42c1", "#fd7e14", "#0d6efd"],
                    resize: true,
                    formatter: value => value + " ករណី"
                });

                /* ================= DONUT : CASE BY DOMAIN ================= */
                new Morris.Donut({
                    element: 'case-by-domain',
                    data: {!! json_encode($chartCasesByDomain) !!},
                    colors: ["#ffc107", "#0d6efd", "#20c997", "#dc3545"],
                    resize: true
                });

                /* ================= DONUT : CASE BY YEAR ================= */
                new Morris.Donut({
                    element: 'case-by-year',
                    data: {!! json_encode($chartCasesByYear) !!},
                    colors: ["#0dcaf0", "#dc3545", "#ffc107", "#20c997", "#6f42c1", "#fd7e14"],
                    resize: true
                });

                /* ================= BAR : LAST 6 MONTHS ================= */
                Morris.Bar({
                    element: 'barchart_last6months',
                    data: @json($barChartData),
                    xkey: 'x',
                    ykeys: ['y', 'z'],
                    labels: @json($barChartLabels),
                    barColors: ["#22af47", "#ff9f40"],
                    stacked: true,
                    resize: true
                });

                /* ================= BAR : OFFICERS ================= */
                Morris.Bar({
                    element: 'barchart_officers',
                    data: @json($barchartOfficer),
                    xkey: 'x',
                    ykeys: ['y', 'z'],
                    labels: @json($barChartOfficerLabels),
                    barColors: ["#3399FF", "#ff4560"],
                    stacked: true,
                    resize: true
                });

            });
        </script>


        <!-- Pass Dynamic Data to JavaScript -->
        <script>
            window.chartData = {
                labels: @json(array_values($labels)),
                values: @json(array_values($values)),
                colors: @json(array_values($colors)), // Passing color array
                highlights: @json(array_values($colors)) // Same colors for highlight
            };
            window.pieChartData = @json($pieChartData);
            window.pieChartColors = @json($pieChartColors);
            window.barChartData = @json($topActiveOfficers);
            window.caseTypesData = @json($caseTypes);
        </script>



    </x-slot>
</x-admin.layout-main>
