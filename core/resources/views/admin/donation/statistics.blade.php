@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 p-md-4">
                <div class="card-body">
                    <div class="row g-2 align-items-center justify-content-between">
                        <div class="col-sm-6">
                            <div class="d-flex">
                                <h5>@lang('Total Amount'):</h5>
                                <span class="text--base total_amount fw-bold "> </span>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <select class="form-control" name="donation_time">
                                <option value="week">@lang('Current Week')</option>
                                <option value="month" selected>@lang('Current Month')</option>
                                <option value="year">@lang('Current Year')</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="chart-area"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/global/js/vendor/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/vendor/chart.js.2.8.0.js') }}"></script>
    <script>
        'use strict';
        (function($) {

            var chart;

            $('[name=donation_time]').on('change', function() {
                let time = $(this).val();
                let url = "{{ route('admin.donation.get.statistics') }}";

                $.get(url, {
                    time: time,
                }, function(response) {
                    if (!response.chart_data) {
                        return;
                    }

                    let successData = [];
                    let labels = [];

                    $.each(response.chart_data, function(label, value) {
                        labels.push(label);
                        successData.push(value.success);
                    });

                    $('.total_amount').text(`{{ $general->cur_sym }}` + response.total_amount);

                    var options = {
                        series: [{
                            name: "Donation",
                            data: successData
                        }],
                        chart: {
                            type: 'area',
                            height: 350,
                            zoom: {
                                enabled: false
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            curve: 'straight'
                        },
                        labels: labels,
                        yaxis: {
                            opposite: true
                        },
                        xaxis: {
                            categories: labels,
                        },
                        legend: {
                            horizontalAlign: 'left'
                        }
                    };

                    if (chart) {
                        chart.destroy();
                    }

                    chart = new ApexCharts(document.querySelector("#chart-area"), options);
                    chart.render();

                });
            }).change();

        })(jQuery);
    </script>
@endpush
