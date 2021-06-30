<p style="font-size: 15px">
    <b style="color: red">{{$model->tenphuong}}</b> đã tăng <b style="color: red">{{$model->count}}</b> ca dương tính
</p>

<p style="font-size: 14px; font-weight: bold; text-decoration: underline;">Bảng thống kê chi tiết</p>

<table class="table">
    <thead>
        <tr>
            <th style="padding: 2px 7px;">#</th>
            <th style="padding: 2px 7px;">Phường xã</th>
            <th style="padding: 2px 7px;">Số ca</th>
            <th style="padding: 2px 7px;">Trong ngày</th>
        </tr>
    </thead>
    <tbody>
    @foreach($data as $k => $i)
        <tr style="@if($i->maphuong === $model->maphuong) background: #ff00001f @endif">
            <td style="padding: 7px 7px; border-top: 1px solid #dee2e6">{{$k+1}}</td>
            <td style="padding: 7px 7px; border-top: 1px solid #dee2e6">{{$i->tenphuong}}</td>
            <td style="padding: 7px 7px; border-top: 1px solid #dee2e6">{{$i->count}}</td>
            <td style="padding: 7px 7px; border-top: 1px solid #dee2e6">{{$i->today_count}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
