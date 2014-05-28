{extends file="Layout/Admin.tpl"}

{block name="title"}Статистика{/block}

{block name="header"} Статистика <small>распределение по доменам</small>{/block}

{block name="breadcrumb"}
    <li><a href="/api/admin"><i class="icon-dashboard"></i> Дашбоард</a></li>
    <li class="active"><i class="fa fa-bar-chart-o"></i> Домены</li>
{/block}

{block name="content"}

    {include file="Admin/TimeManageForm.tpl"}

<h2>Распределение по доменам</h2>
    <div id="refGraph"></div>
<h2>Данные текстом</h2>
    <pre>{var_dump($refs)}</pre>
{/block}

{block name="scripts"}
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
        google.load("visualization", "1", {
            packages:["corechart"]});
        google.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable({$graphData});

            var options = {
               is3D:true,
                height:500
            };

            var chart = new google.visualization.PieChart(document.getElementById('refGraph'));
            chart.draw(data, options);
        }
    </script>
    <script src="/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $('#fromDate').datetimepicker({
                language: 'pt-BR'
            });

            $('#toDate').datetimepicker({
                language: 'pt-BR'
            });
        });
    </script>
{/block}
