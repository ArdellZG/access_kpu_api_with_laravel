<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit:0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Real Count</title>

    {{-- CSS Bootstrap --}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    {{-- JS Bootstrap --}}
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    {{-- Chart --}}
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
   

    {{-- JS AXIOS --}}
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    {{-- Jquery --}}
    <script src="{{ asset('js/jquery-3.4.0.min.js') }}"></script>

    {{-- Vue Js --}}
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
</head>
<body>
    
    <div id="app"">
        <div class="container">
            <div class="card mt-5">
                <div class="card-header">
                    Real Count
                </div>
                <div class="card-body">

                    <div class="row" style="min-height:50vh">
                        <div class="col-md-4 mt-2">
                            <div class="card">
                                <div class="card-header">Total Suara = @{{ Suara }} <br/> (Sumber: <a href="https://pemilu2019.kpu.go.id">KPU 2019</a>)</div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">Jokowi & Ma'ruf: @{{ S01 }} ( @{{ ((S01/Suara) * 100).toFixed(2) }}% )</li>
                                    <li class="list-group-item">Prabowo & Sandi: @{{ S02 }} ( @{{ ((S02/Suara) * 100).toFixed(2) }}% )</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-8 mt-2">
                            <div class="card">
                                <div class="card-header">Chart:</div>
                                <div class="card-body">
                                    <div id="chart" style="min-width:500px; height:300px"></div>
                                </div>
                            </div>                            
                        </div>
                    </div>

                </div>
                <div class="card-footer text-right">
                    Last updated: @{{ last_update }}
                </div>
            </div>
        </div>
    </div>

    <script>
        var vue = new Vue({
            el: "#app",
            beforeMount() {                
                this.fillSuara();
                google.charts.load("current", {packages:["corechart"]});
                google.charts.setOnLoadCallback(this.chart);
            },
            data: {
                Suara: 0,
                S01: 0,
                S02: 0,
                last_update: ""
            },
            methods: {                
                fillSuara: function() {

                    let vue = this;
                    let data = axios.get("http://localhost:8000/api/suara").then(function(data) {
                            vue.Suara = data.data.Suara01 + data.data.Suara02;
                            vue.S01 = data.data.Suara01;
                            vue.S02 = data.data.Suara02;
                            vue.last_update = data.data.last_update;                          
                        });
                    setInterval(function () {
                        data = axios.get("http://localhost:8000/api/suara").then(function(data) {                            
                            vue.Suara = data.data.Suara01 + data.data.Suara02;
                            vue.S01 = data.data.Suara01;
                            vue.S02 = data.data.Suara02;     
                            vue.last_update = data.data.last_update;                       
                        });                        
                    }, 10000);                     
                },
                chart: function() {
                    let vue = this;
                    var data = google.visualization.arrayToDataTable([
                        ['Real Count', 'Pilpres 2019'],
                        ['Jokowi & Ma\'ruf', vue.S01],
                        ['Prabowo & Sandi', vue.S02],
                    ]);

                    var options = {
                        title: 'Real Count',
                        pieHole: 0.4,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('chart'));
                    chart.draw(data, options);

                    setInterval(function() {
                        var data = google.visualization.arrayToDataTable([
                            ['Real Count', 'Pilpres 2019'],
                            ['Jokowi & Ma\'ruf', vue.S01],
                            ['Prabowo & Sandi', vue.S02],
                        ]);

                        var options = {
                            title: 'Real Count',
                            pieHole: 0.4,
                        };

                        var chart = new google.visualization.PieChart(document.getElementById('chart'));
                        chart.draw(data, options);
                    }, 2000);
                }
            }
        });

        

        

        
    </script>


</body>
</html>