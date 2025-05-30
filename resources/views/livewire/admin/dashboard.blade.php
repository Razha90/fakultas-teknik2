@section('title', 'Dashboard | FT')

<div>
    <div class="page-breadcrumb mb-4 rounded bg-white p-4 shadow">
        <h3 class="text-dark">👋 Selamat Datang, <strong>{{ Auth::user()->fullname }}</strong></h3>
        <p class="text-muted mb-0">
            Departemen: <span class="badge bg-primary">{{ Auth::user()->department->name }}</span>
        </p>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            @php
                                $cards = [
                                    [
                                        'title' => 'Departments',
                                        'count' => $departments,
                                        'icon' => 'building',
                                        'bg' => 'primary',
                                        'animate' => true,
                                    ],
                                    [
                                        'title' => 'Categories',
                                        'count' => $categories,
                                        'icon' => 'folder',
                                        'bg' => 'success',
                                    ],
                                    [
                                        'title' => 'Contents',
                                        'count' => $contents,
                                        'icon' => 'file-alt',
                                        'bg' => 'warning',
                                    ],
                                    ['title' => 'Users', 'count' => $users, 'icon' => 'users', 'bg' => 'danger'],
                                    [
                                        'title' => 'Content by Publish',
                                        'count' => $publishedContents,
                                        'icon' => 'upload',
                                        'bg' => 'info',
                                    ],
                                    [
                                        'title' => 'Content by Unpublished',
                                        'count' => $unpublishedContents,
                                        'icon' => 'times',
                                        'bg' => 'secondary',
                                    ],
                                ];
                            @endphp

                            @foreach ($cards as $card)
                                <div class="col-md-3 col-sm-6">
                                    <div
                                        class="card bg-{{ $card['bg'] }} dashboard-card animate__animated animate__fadeInUp mb-3 text-white shadow">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h5 class="card-title">{{ $card['title'] }}</h5>
                                                    <h2>{{ $card['count'] }}</h2>
                                                </div>
                                                <div class="align-self-center">
                                                    <i
                                                        class="fa fa-{{ $card['icon'] }} fa-3x {{ $card['animate'] ?? false ? 'animate__animated animate__pulse animate__infinite' : '' }}"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="card shadow">
                            <div class="card-header bg-info text-white">
                                <h4 class="mb-0">Total Views per Kategori</h4>
                            </div>
                            <div class="card-body">
                                <canvas id="viewsChart" height="120"></canvas>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('viewsChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($viewData->pluck('category')) !!},
                datasets: [{
                    label: 'Total Views',
                    data: {!! json_encode($viewData->pluck('total_views')) !!},
                    backgroundColor: ctx => ctx.chart.data.labels.map((_, i) => `hsl(${i * 45}, 70%, 50%)`),
                    borderColor: '#fff',
                    borderWidth: 2,
                    borderRadius: {
                        topLeft: 10,
                        topRight: 10
                    },
                    hoverBackgroundColor: '#222',
                    hoverBorderColor: '#000'
                }]
            },
            options: {
                responsive: true,
                animation: {
                    duration: 1500,
                    easing: 'easeOutBounce'
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#343a40',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#343a40',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        cornerRadius: 5,
                        padding: 10
                    },
                    title: {
                        display: true,
                        text: 'Distribusi View Berdasarkan Jenis Konten',
                        color: '#000000',
                        font: {
                            size: 20,
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            color: '#343a40'
                        },
                        grid: {
                            color: '#dee2e6',
                            borderDash: [4, 4]
                        }
                    },
                    x: {
                        ticks: {
                            color: '#343a40',
                            font: {
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: '#dee2e6',
                            borderDash: [2, 2]
                        }
                    }
                }
            }
        });
    </script>
</div>
