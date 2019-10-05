<?php

namespace App\Http\Controllers;

use App\MyActivity;
use App\User;
use ArrayObject;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Matrix\Matrix;

class RekomendasiController extends Controller
{
    function index()
    {
        $pendidikan = 3;
        $ti = 3;
        $menulis = 2;
        $administrasi = 3;
        $pengalaman_survei = 4;

        $task_criteria = [$pendidikan, $ti, $menulis, $administrasi, $pengalaman_survei];

        // START Mendapatkan data id, nip, dan nama user dan mengkonversi data dari database kedalam skala likert terbalik
        $users = User::select(['id', 'nip', 'name'])->get()->toArray();
        $user_criterias = User::select(['pendidikan', 'ti', 'menulis', 'administrasi', 'pengalaman_survei'])->get()->toArray();
        for ($i = 0; $i < sizeof($user_criterias); $i++)
            foreach ($user_criterias[$i] as $key => $value)
                $user_criterias[$i][$key] = $this->convertToLikert($value) ?? $user_criterias[$i][$key];
        // END

        // START Mencari similarity antara kriteria dari tugas dengan masing-masing kriteria pegawai
        $criteria_similarity = [];
        foreach ($user_criterias as $user_criteria) {
            $user_criteria = array_values($user_criteria);
            $similarity = $this->cosine_similarity($task_criteria, $user_criteria);
            array_push($criteria_similarity, $similarity);
        }
        // END

        // START Mencari banyak kegiatan yang diemban oleh masing-masing pegawai selama bulan sekarang
        $banyakKegiatanYangDiemban = [];
        $jumlahVolumeKegiatan = [];
        $monthNow = Carbon::now()->formatLocalized('%B_%Y');
        foreach ($users as $user) {
            $user_id = $user['id'];
            $assignments = DB::table('assignment')
                ->join('activity', 'assignment.activity_id', '=', 'activity.id')
                ->select(['awal', 'akhir', 'petugas'])
                ->whereRaw("JSON_CONTAINS(JSON_KEYS(`petugas`), '\"$user_id\"') = true")
                ->whereDate('awal', '<=', now())
                ->whereDate('akhir', '>=', now())
                ->get();
            $count = $assignments->count();
            $volumeKegiatan = 0;
            foreach ($assignments as $assignment) {
                try {
                    $volumeKegiatan += (int) json_encode($assignment->petugas)[$user_id][$monthNow];
                } catch (Exception $e) { }
            }

            $myactivies = MyActivity::where('created_by_user_id', '=', $user_id)
                ->whereDate('awal', '<=', now())
                ->whereDate('akhir', '>=', now())
                ->get();
            $count += $myactivies->count();
            foreach ($myactivies as $myactivity)
                $volumeKegiatan += $myactivity->volume;

            $banyakKegiatanYangDiemban[(int) $user['id']] = $count;
            $jumlahVolumeKegiatan[(int) $user['id']] = $volumeKegiatan;
        }
        // END

        $criteria_weight = [10, 3, 7];
        $criteria_alternatif = [$criteria_similarity, $banyakKegiatanYangDiemban, $jumlahVolumeKegiatan];
        $index_pemenang = $this->weightedProduct($criteria_weight, $criteria_alternatif);
        echo $users[$index_pemenang]['name'];
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

    public function weightedProduct($criteria_weight, $criteria_alternatif)
    {
        $sum_of_criteria_weight = array_sum($criteria_weight);
        $criteria_weight_normalizied = [];
        array_map(function ($x) use (&$sum_of_criteria_weight, &$criteria_weight_normalizied) {
            array_push($criteria_weight_normalizied, ($x / $sum_of_criteria_weight));
        }, $criteria_weight);
        $S = $this->hitung_S($criteria_weight_normalizied, $criteria_alternatif, [1, -1, -1]);
        $Si = $this->hitung_Si($S);
        $Vi = $this->hitung_Vi($Si);
        return array_search(max($Vi), $Vi);
    }

    public function hitung_S($criteria_weight_normalizied, $criteria_alternatif, $sign = [])
    {
        $S = [];
        foreach ($criteria_weight_normalizied as $key => $cwn) {
            $cwm_temp = [];
            foreach ($criteria_alternatif[$key] as $criteria) {
                $value = pow($criteria, $cwn * $sign[$key]);
                array_push($cwm_temp, !is_infinite($value) ? $value : 0);
            }
            array_push($S, $cwm_temp);
        }
        return $S;
    }

    public function hitung_Si(array $S)
    {
        $Si = [];
        foreach ($S as $criteria_alternatif) {
            foreach ($criteria_alternatif as $index => $alternatif) {
                if (array_key_exists($index, $Si)) {
                    $Si[$index] *= $alternatif;
                } else {
                    $Si[$index] = $alternatif;
                }
            }
        }
        return $Si;
    }

    public function hitung_Vi($Si)
    {
        $sum = array_sum($Si);
        $sum = $sum == 0 ? 0 : $sum;
        $Vi = [];
        foreach ($Si as $si) {
            array_push($Vi, $si / $sum);
        }
        return $Vi;
    }

    public function cosine_similarity(array $p1, array $p2)
    {
        $top = 0;
        for ($i = 0; $i < sizeof($p1); $i++)
            $top += $p1[$i] * $p2[$i];
        $bottom = $this->panjang_vektor($p1) * $this->panjang_vektor($p2);
        return $bottom == 0 ? 0 : ($top / $bottom);
    }

    public function panjang_vektor(array $p1)
    {
        $top = 0;
        array_map(function ($x) use (&$top) {
            $top += pow($x, 2);
        }, $p1);
        return sqrt($top);
    }
}
