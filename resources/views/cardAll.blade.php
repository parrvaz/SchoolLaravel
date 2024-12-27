<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body {
            font-family: 'iransans', 'fa', sans-serif;
        }

        .head {
            border: 2px solid black;
            border-radius: 5px;
            padding: 3mm;
        }

        table {
            border: 2px solid #e2e2e2;
            border-collapse: collapse;
            font-family: 'iransans_ul' , 'fa', sans-serif;
        }

        tr:nth-child(even) {
            background-color: #f7f7f7;
        }

        td, th {
            padding: 3mm;
            border-right: 1px solid #e2e2e2;
            vertical-align: middle;
            font-size: 12px;
        }

        th {
            border: 1px solid #e2e2e2;
            background-color: #e2e2e2;
        }
        td{
            direction: ltr;
        }
        /* optional styles */
        @page {
            /*size: auto;*/
            direction: rtl;
            margin-header: 1cm;
            margin-footer: 1cm;
            margin-bottom: 2.25cm;
            margin-top: 3cm;
            header: otherpages;
            footer: page-footer;
        }

        @page :first {
            header: firstpage;
            margin-top: 4.4cm;
        }
    </style>
</head>
<body>
<htmlpageheader name="firstpage" style="display:none">
    <div class="" style="padding: 0.9rem ; width: 100%; border-radius: 5px ; border: 2px solid black;">
        <div
            style="font-weight: bold; text-align: center ; width: 100%">{{$header['title']}} {{$header['school']}}  </div>
                <div style="width: 30%; text-align: left ; float: left">
                    <span>سال تحصیلی:</span>
                    <span>{{$header["year"]}}</span>
                </div>
                <div style="width: 40%; text-align: center ; float: left">
                     <span> پایه:</span>
                        <span>{{$header['grade'] }}</span>
                </div>
                <div style="width: 30%; text-align: right ; float: right">
                    <span> زمان:</span>
                    <span>{{$header['month'] }}</span>
                </div>

        <div style="width: 30%; text-align: left ; float: left">
            <span>صفحه:</span>
            <span>{PAGENO}</span>
        </div>
        <div style="width: 40%; text-align: center ; float: left">
            <span> معدل:</span>
            <span>{{$header['average'] }}</span>
        </div>
        <div style="width: 30%; text-align: right ; float: right">
            <span> نام دانش آموز:</span>
            <span> {{$header['studentName'] }}</span>
        </div>

{{--        <div style="margin-left: auto ; text-align: center ; width: 100%; direction: rtl">--}}
{{--            <span>بازه زمانی :</span>--}}
{{--            <span--}}
{{--                style="font-size: 16px; font-weight: bold"> {{$header['month']}} </span>--}}
{{--        </div>--}}
{{--        <div style="width: 48%; text-align: left ; float: left">--}}
{{--            <span>صفحه</span>--}}
{{--            <span>{PAGENO}</span>--}}
{{--        </div>--}}
{{--        <div style="width: 48%; text-align: right ; float: right">--}}
{{--            <span> معدل:</span>--}}
{{--            <span>{{$items['average'] }}</span>--}}
{{--        </div>--}}
    </div>
</htmlpageheader>
<htmlpageheader name="otherpages" style="display:none">
    <div class="" style="padding: 0.9rem ; width: 100%; border-radius: 5px ; border: 2px solid black">
        <div style="width: 50% ; text-align: right; font-weight: bold ; float: right">{{$header['title']}}</div>
        <div style=" text-align: left ; float: left">
            <span>صفحه</span>
            <span>{PAGENO}</span>
        </div>

    </div>
</htmlpageheader>
<sethtmlpageheader name="firstpage" value="on" show-this-page="1"/>
<sethtmlpageheader name="otherpages" value="on"/>
<htmlpagefooter name="page-footer">
    <div style="padding: 0.4rem ; width: 100% ; border-top: 2px solid black">
        <div style="font-weight: bold; width: 55%; float: right; text-align: left;">www.pishkar.ir</div>
    </div>
</htmlpagefooter>
<table width="100%" rotate="0" style="text-align: center ; direction: rtl;">
    <thead>
    <tr>
        <th width="5%">#</th>
        <th>نام درس</th>
        <th>واحد</th>
        <th>نمره</th>
    </tr>
    </thead>


    <tbody>
    @php $i=1 @endphp
    @foreach($items as $key=> $item)

        <tr>
            <td>{{$i++}}</td>
            <td>{{ \App\Models\Course::find($item['course_id'])->name}}</td>
            <td>{{number_format($item['factor'])}}</td>
            <td>{{number_format($item['score'])}}</td>
        </tr>

    @endforeach
    </tbody>

</table>

</body>
</html>
