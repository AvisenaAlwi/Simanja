<?php

namespace App\Http\Controllers;

use App\User;
use ArrayObject;
use Exception;
use Illuminate\Http\Request;
use Matrix\Matrix;

class RekomendasiController extends Controller
{
    function index(){
        $pendidikan = 3;
        $ti = 3;
        $menulis = 2;
        $administrasi = 3;
        $pengalaman_survei = 4;
        $aktivitas = 20;
        $k = ['pendidikan'=>$pendidikan, 'ti'=>$ti, 'menulis'=>$menulis, 'administrasi'=>$administrasi, 'pengalaman_survei'=>$pengalaman_survei, 'aktivitas'=>$aktivitas];
        $kriterias = [];
        foreach ($k as $ka=>$va) {
            $z = [];
            foreach ($k as $ks=>$vs){
                if ($ks != 'aktivitas'){
                    $z[$ks] = $this->convertToIntensitasKepentingan($va, $vs);
                    if ($ka == 'aktivitas')
                        $z[$ks] = 7;
                }else{
                    if ($ka == 'aktivitas' && $ks == 'aktivitas')
                        $z[$ks] = 1;
                    else if ($ks == 'aktivitas')
                            $z[$ks] = 1/7;
                }
            }
            $kriterias[$ka] = $z;
        }
        // dd($kriterias);
        $kriterias = $this->normalisasiDanBobotkan($kriterias);
        $users = User::all();
        $alternativeValues = [];
        foreach ($k as $kri=>$val){
            $tabel = [];
            foreach ($users as $user1){
                $z = [];
                foreach ($users as $user2){
                    if ($kri != 'aktivitas')
                        $z[(int)$user2->id] = $this->convertToIntensitasKepentingan(
                            $this->convertToLikert($user1->$kri),
                            $this->convertToLikert($user2->$kri)
                        );
                    else
                        $z[(int)$user2->id] = $this->convertToIntensitasKepentingan(
                            $this->convertToLikertAktivitas($user1->$kri),
                            $this->convertToLikertAktivitas($user2->$kri)
                        );
                    
                    // if ($this->convertToIntensitasKepentingan(
                    //     $this->convertToLikert($user1->$kri),
                    //     $this->convertToLikertAktivitas($user2->$kri)
                    // ) == null)
                    //     dd($user1->$kri,$this->convertToLikertAktivitas($user1->$kri), $this->convertToLikertAktivitas($user2->$kri));
                }
                $tabel[(int)$user1->id] = $z;
            }
            $tabel = $this->normalisasiDanBobotkan($tabel);
            $alternativeValues[$kri] = $tabel;
        }
        $hasilAlternative = [];
        foreach ($alternativeValues as $ke => $value){
            foreach ($value['bobot'] as $key => $v){
                if (!isset($hasilAlternative[$key]))
                    $hasilAlternative[$key] = [];
                $hasilAlternative[$key][$ke] = $v;
            }
        }
        $hasilAlternative = new Matrix($hasilAlternative);
        $hasilKriteria = new Matrix($kriterias['bobot']);
        $f = $hasilAlternative->multiply($hasilKriteria)->toArray();
        $maxValue = PHP_INT_MIN;
        $indexPemenang = -1;
        foreach ($f as $key => $value){
            if ($value > $maxValue){
                $indexPemenang = $key;
                $maxValue = $value;
            }
        }
        $idPemenang = $users[$indexPemenang]->name;
        return view('rekomendasi', ['kriterias'=>$kriterias,'alternativeValues'=>$alternativeValues, 'pemenang'=>$idPemenang]);
    }

    function convertToLikert($value) 
    {
        if ($value == 'Sangat Tinggi')
            return 1;
        else if ($value == 'Tinggi')
            return 2;
        else if ($value == 'Cukup')
            return 3;
        else if ($value == 'Kurang')
            return 4;
        else if ($value == 'Sangat Kurang')
            return 5;
        else if ($value == 'S2')
            return 1;
        else if ($value == 'S1')
            return 2;
        else if ($value == 'D-IV')
            return 3;
        else if ($value == 'D-III')
            return 4;
        else if ($value == 'SMA')
            return 5;
    }
    public function convertToLikertAktivitas($value)
    {
        if ($value <= 4)
            return 1;
        else if ($value <= 8)
            return 2;
        else if ($value <= 12)
            return 3;
        else if ($value <= 16)
            return 4;
        else if ($value > 16)
            return 5;
    }
    function convertToIntensitasKepentingan($value1, $value2) 
    {
        if ($value1 - $value2 == 0)
            return 1/1;
        else if ($value1 - $value2 == 1)
            return 1/3;
        else if ($value1 - $value2 == 2)
            return 1/5;
        else if ($value1 - $value2 == 3)
            return 1/7;
        else if ($value1 - $value2 == 4)
            return 1/9;
        else if ($value1 - $value2 == -1)
            return 3;
        else if ($value1 - $value2 == -2)
            return 5;
        else if ($value1 - $value2 == -3)
            return 7;
        else if ($value1 - $value2 == -4)
            return 9;
    }
    function convertToIntensitasKepentinganAktivitas($value1, $value2)
    {
        $x = $value1 - $value2; 
        if ($x < 2 && $x > -2)
            return 1;
        else if ($x > -4 && $x < 0)
            return 3;
        else if ($x > -6 && $x < 0)
            return 5;
        else if ($x > -8 && $x < 0)
            return 7;
        else if ($x <= -8 && $x < 0)
            return 9;
        else if ($x < 4 && $x > 0)
            return 1/3;
        else if ($x < 6 && $x > 0)
            return 1/5;
        else if ($x < 8 && $x > 0)
            return 1/7;
        else if ($x >= 8 && $x > 0)
            return 1/9;
    }
    function normalisasiDanBobotkan($array) 
    {
        $array = collect($array);
        $jumlah = [];
        // $z = $array->sum('Drs. Sunaryo, M.Si');
        // $zz = 0;
        // foreach($array as $ke => $v){
        //     foreach($v as $kee => $vv){
        //         if ($kee == 'Drs. Sunaryo, M.Si')
        //             $zz += $vv;
        //     }
        // }
        // dd($array, $z, $zz);
        foreach ($array as $key1 => $value1) {
            $jumlah[$key1] = $array->sum($key1);
        }
        $normalisasi = collect([]);
        foreach ($array as $key1 => $value1) {
            $zr = [];
            foreach ($value1 as $key2 => $value2) {
                $zr[$key2] = $value2 / $jumlah[$key2];
            }
            $normalisasi[$key1] = $zr;
        }
        $jumlah = [];
        foreach ($normalisasi as $key1 => $value1) {
            $jumlah[$key1] = $normalisasi->sum($key1);
        }
        $normalisasi = collect($normalisasi);
        $jumlah = collect($jumlah);
        
        $rerata = $normalisasi->map(function ($item, $key) {
            return array_sum($item)/count($item);
        });
        $bobot = new Matrix($normalisasi->toArray());
        $bobot = $bobot->multiply($rerata->toArray())->toArray();
        $b = [];
        foreach (array_keys($normalisasi->toArray()) as $key => $val){
            $b[$val] = $bobot[$key][0];
        }
        return ['normalisasi'=>$normalisasi, 'rerata'=>$rerata->toArray(), 'bobot'=>$b];
    }
}
