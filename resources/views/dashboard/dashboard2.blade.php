@php
$caseTypes = myArrayCaseType();
$totalResolved = countResolvedCases();
$totalInProgress = countInProgressCases();
$totalNewCases = countNewCases();
$todayCases = countTodayCases();

$casesByYear = getCasesCountByYear();

$lineChartData = getCaseStatisticByYear();
//$lineChartData = [
//        ['year' => '2020', 'resolved_count' => 30, 'unresolved_count' => 15],  // X = year, Y = cases, Z = resolved
//        ['year' => '2021', 'resolved_count' => 45, 'unresolved_count' => 25],
//        ['year' => '2022', 'resolved_count' => 60, 'unresolved_count' => 35],
//        ['year' => '2023', 'resolved_count' => 80, 'unresolved_count' => 50],
//        ['year' => '2024', 'resolved_count' => 95, 'unresolved_count' => 70],
//    ];

$caseCounts = [
        'វិវាទបុគ្គលប្រភេទទី១' => getCasesCountByType(),
        'វិវាទបុគ្គលប្រភេទទី២' => getCasesCountByType(2),
        'វិវាទការងាររួម' => getCasesCountByType(3),
    ];


$labels = [
    "វិវាទបុគ្គលប្រភេទទី១",
    "វិវាទបុគ្គលប្រភេទទី២",
    "វិវាទការងាររួម",
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
        "#3399FF",
        "#3399FF",
        "#feb019"
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
$barChartOfficerLabels = [
    "Resolved",
    "Progressing",
    ]; // Change labels dynamically

$barChartLabels = [
    "Resolved",
    "Progressing"
    ]; // Change labels dynamically

$pieChartData = [
    ['Category', 'Value'],
    ["Resolved", $totalResolved],
    ["Progressing", $totalInProgress],
    // ["Pending", 150],
    // ["Dismissed", 70]
];

$pieChartColors = ["#3399FF", "#ff4560"]; // Define Colors

$topActiveOfficers = [
    ['label' => 'January1', 'value' => 15],
    ['label' => 'February2', 'value' => 20],
    ['label' => 'March', 'value' => 10],
    ['label' => 'April', 'value' => 25],
    ['label' => 'May', 'value' => 30],
    ['label' => 'June', 'value' => 18]
];

$topActiveOfficers = [
    ['name' => 'Copper', 'density' => 10, 'color' => '#4466f2'],
    ['name' => 'Silver', 'density' => 12, 'color' => '#1ea6ec'],
    ['name' => 'Gold', 'density' => 14, 'color' => '#22af47'],
    ['name' => 'Platinum', 'density' => 16, 'color' => '#007bff']
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

{{--{{ dd(getCaseStatisticByYear()) }}--}}

<x-admin.layout-main :adata="$adata">
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/chartist.css') }}">
        <style>
            #case-by-domain svg text {
                font-size: 10px !important;
                /*font-weight: bold; !* Optional *!*/
                color: #0b5ed7;
            }

            #chart-container svg text[fill="#007bff"] { fill: #007bff !important; } /* Blue */
            #chart-container svg text[fill="#008000"] { fill: #008000 !important; } /* Green */
            #chart-container svg text[fill="#FF0000"] { fill: #FF0000 !important; } /* Red */


        </style>
    </x-slot>
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body row" style="padding: 15px 15px 0px 15px;">
                                <div class="col-sm-3">
                                    <div class="card">
                                        <div class="card-body bg-success" style="border-radius: 5px">
                                            <div class="icons-section text-center"><img src="../assets/images/bitcoin/1.png" alt="">
                                                <h6 class="fw-bold" style="font-size: 20px">សំណុំរឿងបានបញ្ចប់</h6>
                                                <h5 class="counter" style="font-size: 26px">{{ $totalResolved }} <span><i data-feather="lock"></i></span></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="card">
                                        <div class="card-body bg-warning" style="border-radius: 5px">
                                            <div class="icons-section text-center"><img src="../assets/images/bitcoin/2.png" alt="">
                                                <h6 class="fw-bold" style="font-size: 20px;">សំណុំរឿងកំពុងដំណើរការ</h6>
                                                <h5 class="counter" style="font-size: 26px">{{ $totalInProgress }} <span><i data-feather="activity"></i></span></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="card">
                                        <div class="card-body bg-danger" style="border-radius: 5px">
                                            <div class="icons-section text-center"><img src="../assets/images/bitcoin/3.png" alt="">
                                                <h6 class="fw-bold" style="font-size: 20px">សំណុំរឿងថ្មីៗ</h6>
                                                <h5><span class="counter" style="font-size: 26px">{{ $totalNewCases }} <span><i data-feather="arrow-up"></i></span></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="card">
                                        <div class="card-body bg-primary" style="border-radius: 5px">
                                            <div class="icons-section text-center"><img src="../assets/images/bitcoin/3.png" alt="">
                                                <h6 class="fw-bold" style="font-size: 20px">សំណុំរឿងថ្ងៃនេះ</h6>
                                                <h5><span class="counter" style="font-size: 26px">{{ $todayCases }} <span><i data-feather="bell"></i></span></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-6 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="fw-bold text-purple">របាយការណ៌សំណុំរឿងតាមឆ្នាំនិមួយៗ</h5>
                    </div>
                    <div class="card-body chart-block">
                        <div class="flot-chart-container">
                            <div class="flot-chart-placeholder" id="case-statistic-by-year"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="fw-bold text-success">ចំនួនសំណុំរឿងដែលបញ្ចូលដោយ Users និមួយៗ</h5>
                    </div>
                    <div class="card-body chart-block">
                        <div class="flot-chart-container">
                            <div class="flot-chart-placeholder" id="user_created_cases"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header project-header">
                        <div class="row">
                            <div class="col-sm-8">
                                <h5 class="fw-bold blue">របាយការណ៌សំណុំរឿង៦ខែចុងក្រោយ</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-3">
                        <div class="github-lg">
                            <div class="show-value-top d-flex">
                                <div class="value-left d-inline-block">
                                    <div class="square d-inline-block bg-success"></div><span class="fw-bold text-success">សំណុំរឿងបានបញ្ចប់</span>
                                </div>
                                <div class="value-right d-inline-block">
                                    <div class="square d-inline-block bg-warning"></div><span class="fw-bold text-warning">សំណុំរឿងកំពុងដំណើរការ</span>
                                </div>
                            </div>
                            <div class="github-chart">
                                <div class="flot-chart-placeholder" id="barchart_last6months"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header project-header">
                        <div class="row">
                            <div class="col-sm-8">
                                <h5 class="fw-bold pink">មន្ត្រីទទួលបន្ទុកដែលសកម្មក្នុងសំណុំរឿង</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-3">
                        <div class="github-lg">
                            <div class="show-value-top d-flex">
                                <div class="value-left d-inline-block">
                                    <div class="square d-inline-block bg-primary"></div><span class="fw-bold text-primary">សំណុំរឿងបានបញ្ចប់</span>
                                </div>
                                <div class="value-right d-inline-block">
                                    <div class="square d-inline-block bg-danger"></div><span class="fw-bold text-danger">សំណុំរឿងកំពុងដំណើរការ</span>
                                </div>
                            </div>
                            <div class="github-chart">
                                <div class="flot-chart-placeholder" id="barchart_officers"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="fw-bold text-warning">បំណែងចែកសំណុំរឿងទៅតាមដែនសមត្ថកិច្ច</h5>
                    </div>
                    <div class="card-body chart-block">
                        <div class="flot-chart-container">
                            <div class="flot-chart-placeholder" id="case-by-domain"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="fw-bold text-danger">បំណែងចែកសំណុំរឿងទៅតាមឆ្នាំនិមួយៗ</h5>
                    </div>
                    <div class="card-body chart-block">
                        <div class="flot-chart-container">
                            <div class="flot-chart-placeholder" id="case-by-year"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Task Distribution Section -->
{{--            <div class="col-sm-6">--}}
{{--                <div class="card">--}}
{{--                    <div class="card-header project-header">--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-sm-8">--}}
{{--                                <h5 class="blue">ប្រភេទសំណុំរឿង</h5>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="card-body chart-block chart-vertical-center project-charts">--}}
{{--                        <canvas id="myDoughnutGraph"></canvas>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
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

                var barChartData = @json($barChartData);
                var barChartLabels = @json($barChartLabels);

                var barChartOfficer = @json($barchartOfficer);
                var barChartOfficerLabel = @json($barChartOfficerLabels);

                new Morris.Line({
                    element: "case-statistic-by-year",
                    data: {!! json_encode($lineChartData) !!}, // Convert Laravel data to JSON
                    xkey: "year",
                    ykeys: ["case_count", "resolved_count", "unresolved_count"], // Y-axis & Z-axis
                    labels: ["Total", "Resolved", "Progressing"],
                    lineColors: ["#007bff","#008000", "#FF0000"], // Blue Green , Red
                    hideHover: "auto",
                    resize: true,
                    xLabelFormat: function (x) {
                        // Format the x-axis label to display the year
                        return x.getFullYear();  // Ensure it shows just the year as a number
                    },
                    hoverCallback: function (index, options, content, row) {
                        let keyMapping = {
                            "case_count": "Total",
                            "resolved_count": "Resolved",
                            "unresolved_count": "Progressing",
                        };

                        let colors = {
                            "case_count": "#007bff",
                            "resolved_count": "#008000",
                            "unresolved_count": "#FF0000"
                        };

                        let tooltip = `<div style="background: white; padding: 5px; border-radius: 5px;">`;
                        Object.keys(keyMapping).forEach((key) => {
                            if (row[key] !== undefined) {
                                tooltip += `<div style="color: ${colors[key]}; font-weight: bold;">${keyMapping[key]}: ${row[key]}</div>`;
                            }
                        });
                        tooltip += `</div>`;

                        return tooltip;
                    }
                });

                new Morris.Donut({ // Donut Chart Represents Cases by Users Created
                    element: 'user_created_cases',
                    data: {!! json_encode($chartCreatedUsers) !!}, // Pass the chart data
                    colors: ["#0dcaf0", "#f41127", "#ffc107", "#20c997", "#d63384", "#ffff00", "#0000FF"],
                    resize: true
                });

                new Morris.Donut({  // Donut Chart Represents Cases by Domain
                    element: 'case-by-domain',
                    data: {!! json_encode($chartCasesByDomain) !!}, // Pass the chart data
                    colors: ["#ffff00", "#0000FF", "#00FF00", "#f41127"],
                    resize: true
                });

                new Morris.Donut({ // Donut Chart Represents Cases by Year
                    element: 'case-by-year',
                    data: {!! json_encode($chartCasesByYear) !!}, // Pass the chart data
                    colors: ["#0dcaf0", "#f41127", "#ffc107", "#20c997", "#d63384", "#ffff00", "#0000FF"],
                    // colors: ["#ffff00", "#0000FF", "#00FF00", "#f41127"],
                    resize: true
                });

                // Initialize Morris Bar Chart For Last 6 Months Cases
                Morris.Bar({
                    element: 'barchart_last6months',
                    data: barChartData,
                    xkey: 'x',
                    ykeys: ['y', 'z'],
                    labels: barChartLabels,
                    barColors: ["#22af47", "#ff9f40"],
                    stacked: true
                });

                // Initialize Morris Bar Chart For Top Active Officers
                Morris.Bar({
                    element: 'barchart_officers',
                    data: barChartOfficer,
                    xkey: 'x',
                    ykeys: ['y', 'z'],
                    labels: barChartOfficerLabel,
                    barColors: ["#3399FF", "#ff4560"],
                    stacked: true
                });

                var ctx = document.getElementById("doughnutChart");
                if (ctx) {
                    var myDoughnutChart = new Chart(ctx, {
                        type: "doughnut",
                        data: {
                            labels: ["វិវាទបុគ្គលប្រភេទទី១", "វិវាទបុគ្គលប្រភេទទី២", "វិវាទការងាររួម"],
                            datasets: [
                                {
                                    data: [
                                        {{ $caseCounts['វិវាទបុគ្គលប្រភេទទី១'] }},
                                        {{ $caseCounts['វិវាទបុគ្គលប្រភេទទី២'] }},
                                        {{ $caseCounts['វិវាទការងាររួម'] }},
                                    ],
                                    backgroundColor: ["#ffcc00", "#28a745", "#007bff", "#dc3545"],
                                },
                            ],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                        },
                    });
                }

                // Handle Filter Change
                // document.getElementById("taskFilter").addEventListener("change", function() {
                //     var selectedValue = this.value;
                //     var newLabels, newData;
                //
                //     switch (selectedValue) {
                //         case "today":
                //             newLabels = ["Done", "Ongoing", "Waiting"];
                //             newData = [60, 25, 15];
                //             break;
                //         case "weekly":
                //             newLabels = ["Completed", "Delayed", "Upcoming"];
                //             newData = [40, 35, 25];
                //             break;
                //         case "monthly":
                //             newLabels = ["Finished", "Pending", "Stuck"];
                //             newData = [70, 20, 10];
                //             break;
                //         default:
                //             newLabels = ["Completed", "In Progress", "Pending"];
                //             newData = [50, 30, 20];
                //     }
                //
                //     updateChart(newLabels, newData);
                // });

                console.log("Doughnut Chart Loaded:", labels, values);
            });
        </script>

        <!-- Pass Dynamic Data to JavaScript -->
        <script>
            window.chartData = {
                labels: @json(array_values($labels)),
                values: @json(array_values($values)),
                colors: @json(array_values($colors)),  // Passing color array
                highlights: @json(array_values($colors)) // Same colors for highlight
            };
            window.pieChartData = @json($pieChartData);
            window.pieChartColors = @json($pieChartColors);
            window.barChartData = @json($topActiveOfficers);
            window.caseTypesData = @json($caseTypes);
        </script>
    </x-slot>
</x-admin.layout-main>
