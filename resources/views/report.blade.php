<!doctype html>
<html lang=ja>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/paper.css">
    <link rel="stylesheet" href="css/app.css">
    <style>@page { size: A4 }</style>
    <title>Document</title>
</head>
<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A4">

<!-- Each sheet element should have the class "sheet" -->
<!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
<section class="sheet padding-10mm">
    <div style="display: flex">
        <h1 style="margin: 0">ギター管一覧理表</h1>
        <p>　　1.ギター　2.アクセサリー　　8.消耗品・雑費　9.自宅使用分</p>
    </div>
    <div>
        <div class="row">
        <div class="col-md-6 left">
            <table class="table">
                <thead>
                <tr style="font-size: 1rem;">
                    <th style="width: 40%">商品名</th>
                    <th style="width: 9%">仕入</th>
                    <th style="width: 9%">到着</th>
                    <th style="width: 16%">仕入額</th>
                    <th style="width: 16%">仕入元</th>
                    <th style="width: 10%">区分</th>
                </tr>
                </thead>
                <tbody>
                @for($i=0; $i<15; $i++) 
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor
                </tbody>
            </table>
        </div>
        <div class="col-md-6 right">
            <table class="table table-striped">
                <thead>
                <tr>
                <th style="width: 20%"></th>
                <th style="width: 20%">仕入額</th>
                <th style="width: 10%">台</th>
                <th style="width: 20%">販売額</th>
                <th style="width: 10%">台</th>
                <th style="width: 20%">利益</th>
                </tr>
                </thead>
                <tbody>
                @foreach($reports As $report)
                    <tr>
                        <td>{{ $report['month'] }}</td>
                        <td>{{ $report['purchase_price'] }}</td>
                        <td>{{ $report['purchase_length'] }}</td>
                        <td>{{ $report['sale_price'] }}</td>
                        <td>{{ $report['sale_length'] }}</td>
                        <td>{{ $report['benefit'] }}</td>
                    </tr>
                @endforeach
                <tr class="result">
                    <td>年計</td>
                    <td>{{ collect($reports)->sum('purchase_price') }}</td>
                    <td>{{ collect($reports)->sum('purchase_length') }}</td>
                    <td>{{ collect($reports)->sum('sale_price') }}</td>
                    <td>{{ collect($reports)->sum('sale_length') }}</td>
                    <td>{{ collect($reports)->sum('benefit') }}</td>
                </tr>
                <tr>
                    <td>総合計</td>
                    <td>{{ $totalReport['purchase_price'] }}</td>
                    <td>{{ $totalReport['purchase_length'] }}</td>
                    <td>{{ $totalReport['sale_price'] }}</td>
                    <td>{{ $totalReport['sale_length'] }}</td>
                    <td>{{ $totalReport['benefit'] }}</td>
                </tr>
                </tbody>
            </table>
        </div>
        </div>
    </div>
</section>
</body>
<style>
    .left .table thead tr th {
        padding: 0;
        padding-left: 2px;
        border-right: 1px dashed #ccc;
    }
    .left .table tbody tr td {
        border-right: 1px dashed #ccc;
        padding-top: 20px;
    }
    .left .table tbody {
        border-bottom: 1px dashed #ccc;
    }

    .right .table thead {
        border: 1px solid black;
        border-bottom: none;
    }

    .right .table thead tr th {
        padding: 0;
        padding-left: 2px;
        border-right: 1px dashed #ccc;
    }
    .right .table tbody tr td {
        padding: 2px;
        padding-left: 5px;
    }
    .right .table tbody {
        border: 1px solid black;
        border-top: none;
    }
    .right .table tbody .result td{
        border-top: 1px black solid;
        font-weight: bold;
    }
</style>
</html>