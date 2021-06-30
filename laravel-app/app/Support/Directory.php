<?php
namespace App\Support;

use Illuminate\Support\Facades\Cache;

class Directory
{
    public static function loai_dd(){
        return [
            'cs_cl_tt' => 'Cơ sở cách ly tập trung',
            'cs_cl_yte' => 'Cơ sở cách ly y tế',
            'chot_ksd' => 'Chốt kiểm soát dịch',
            'phongtoa_pt' => 'Điểm phong tỏa',
        ];
    }

    public static function loai_dd_ln(){
        return [
            'khoiphat' => 'Khởi phát',
            'lamviec' => 'Làm việc',
        ];
    }

    public static function loaihinh_dd(){
        $values = ['Nhà riêng', 'Chung cư', 'Ký túc xá', 'Nhà trọ', 'Resort/Khu nghỉ dưỡng, Khách sạn', 'Khách sạn', 'Nhà nghỉ', 'Quán ăn uống', 'Cửa hàng thời trang', 'Ngân hàng/quỹ tính dụng', 'Cơ sở khám chữa bệnh', 'Nhà thuốc', 'Nhà hộ sinh/Nhà bảo sinh', 'Phòng xét nghiệm', 'Khu cách ly', 'Nhà hàng', 'Chợ', 'Siêu thị', 'Cửa hàng tiện lợi', 'Karaoke', 'Bar', 'Club/Bub/Lounge', 'Văn phòng', 'Cơ sở sản xuất', 'Trụ sở cơ quan nhà nước', 'Cơ sở giáo dục', 'Trung tâm bảo trợ xã hộ', 'Cơ sở giải trí, nghệ thuật', 'Trung tâm thể thao', 'Điểm du lịch', 'Cơ sở tôn giáo', 'Cơ sở giam giữ', 'Dịch vụ làm đẹp', 'Cơ sở luyện tập', 'Cơ sở chăm sóc vật nuôi', 'Sân bay', 'Bến xe', 'Nhà ga', 'Khác'];
        return array_combine($values, $values);
    }

    public static function hinhthuc_cl(){
        return [1 => 'Cách ly tập trung', 2 => 'Cách ly tại nhà', 3 => 'Cách ly điều trị', 4 => 'Cách ly theo cụm dân cư', 5 => 'Cách ly theo cơ quan đơn vị'];
    }

    public static function gioitinh(){
        return [1 => 'Nam', 2 => 'Nữ'];
    }

    public static function ketqua_xn(){
        return [1 => 'Dương tính', 2 => 'Âm tính', 3 => 'Chờ'];
    }

    public static function tinhtrang_ht(){
        return [1 => 'Khỏi', 2 => 'Mắc bệnh'];
    }

    public static function phanloai_cb(){
        return ['Ca xâm nhập'];
    }

    public static function loaihinh_pht(){
        return ['Tàu điện', 'Tàu hỏa', 'Tàu thủy', 'Xe khách', 'Xe buýt', 'Taxi', 'Grab car', 'Grab bike', 'Xe ôm', 'Ô tô',];
    }

    public static function phanloai_cl(){
        return collect(['F0', 'F1', 'F2', 'F3', 'F4', 'F5'])->combineValues()->all();
    }

    public static function quans($ma_tp = '79', $columns =  ['tenquan', 'maquan']){
        return \App\Models\HcQuan::where('ma_tp', $ma_tp)->get($columns)->pluck(...$columns);
    }

    public static function phuongs($maquan = '769', $columns =  ['tenphuong', 'maphuong']){
        return \App\Models\HcPhuong::where('maquan', $maquan)->get($columns)->pluck(...$columns);
    }

    public static function tinhtrang_pt(){
        return [
            1 => 'Đang phong tỏa',
            2 => 'Đã gỡ bỏ',
            3 => 'Tái phong tỏa',
        ];
    }

    public static function noi_ph(){
        return array_combine($values = [
            'Khu cách ly tập trung',
            'Cộng đồng' ,
            'Khu phong tỏa' ,
            'Cách ly tại nhà',
        ], $values);
    }

    public static function nguon_ln(){
        return Cache::remember('nguon_ln.index', 10*24*60*60,function () {
            return \App\Models\Cabenh::distinct($col = 'nguon_ln')->select($col)->whereNotNull($col)->get()->map->toArray()
                ->pluck($col)->filter()->all();
        });
    }

    public static function loaihinh_ch(){
        return [
            'ST' => 'Siêu thị',
            'CHTL' => 'Cửa hàng tiện lợi',
        ];
    }

    public static function phantuyen_type(){
        return [
            'PHUONGXA' => 'Phường xã',
            'KHUPHO' => 'Khu phố',
            'TO_DP' => 'Tổ dân phố',
            'CHUNGCU' => 'Chung cư',
            'KHUDOTHI' => 'Khu đô thị',
        ];
    }
}
