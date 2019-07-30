@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
@include('users.partials.header', [
'title' => 'Tentang kami',
'description' => 'Informasi seputar pengembang Sistem Manajemen Beban Kerja (SIMANJA)'
])

<div class="container-fluid mt--7">
    <div class="col-xl-12 order-xl-1">
        <div class="card bg-secondary shadow">
            <div class="card-header bg-white border-0">
                <div class="row align-items-center">
                    <h3 class="col-12 mb-0" style="text-align:center">{{ __('Inilah kami!') }}</h3>
                </div>
            </div>
            <div class="card-body">
                <img src="{{ asset('argon') }}/img/theme/team-4-800x800.jpg" style="width:30%"
                    class="rounded mx-auto d-block img-fluid">
                <br>
                <p class="blockquote text-center">Kami (Sena, Rian, dan Doni) adalah mahasiswa dari Universitas
                    Brawijaya Fakultas Ilmu Komputer
                    jurusan Teknik Informatika
                    yang saat ini (pada saat laman ini dibuat) yang telah menempuh semester 6-7 sedang melakukan
                    Praktik Kerja Lapangan (PKL)
                    yang merupakan salah satu syarat kelulusan dari akademisi Universtas Brawijaya Fakultas Ilmu
                    Komputer.</p>
                <br><br>
            </div>
            <div class="row">
                <div class="col-12 col-lg-4 mb-5">
                    <div class="card card-profile shadow">
                        <div class="row justify-content-center">
                            <div class="col-lg-3 order-lg-2">
                                <div class="card-profile-image">
                                    <a href="#">
                                        <img src="{{ asset('argon') }}/img/theme/team-4-800x800.jpg"
                                            class="rounded-circle">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                            <div class="d-flex justify-content-between">
                                <a href="https://www.instagram.com/senaabdillah"
                                    class="btn btn-sm btn-info mr-4">Instagram</a>
                                <a href="https://wa.me/628563250975?text=SUBJECT:BPS Simanja dev %0AHai Sena!"
                                    target="_blank" class="btn btn-sm btn-default float-right">Whatsapp</a>
                            </div>
                        </div>
                        <div class="card-body pt-0 pt-md-4">
                            <div class="row">
                                <div class="col">
                                    <div class="text-center mt-md-5">
                                        <table>
                                            <tr>
                                                <td>
                                                    <h1>Avi</h1>
                                                </td>
                                                <td>
                                                    <h1 class="text-success">sena</h1>
                                                </td>
                                                <td>
                                                    <h1>&nbsp;Abdillah Alwi</h1>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="card-profile-stats d-flex justify-content-center mt-md--2">
                                            &#9993; avisena@student.ub.ac.id
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 mb-5">
                    <div class="card card-profile shadow">
                        <div class="row justify-content-center">
                            <div class="col-lg-3 order-lg-2">
                                <div class="card-profile-image">
                                    <a href="#">
                                        <img src="{{ asset('argon') }}/img/theme/team-4-800x800.jpg"
                                            class="rounded-circle">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                            <div class="d-flex justify-content-between">
                                <a href="https://www.instagram.com/rianonymous"
                                    class="btn btn-sm btn-info mr-4">Instagram</a>
                                <a href="https://wa.me/6281554411718?text=SUBJECT:BPS Simanja dev %0AHai Rian!"
                                    class="btn btn-sm btn-default float-right">Whatsapp</a>
                            </div>
                        </div>
                        <div class="card-body pt-0 pt-md-4">
                            <div class="row">
                                <div class="col">
                                    <div class="text-center mt-md-5">
                                        <table>
                                            <tr>
                                                <td>
                                                    <h1>Muhammad&nbsp;Faj</h1>
                                                </td>
                                                <td>
                                                    <h1 class="text-success">rian</h1>
                                                </td>
                                                <td>
                                                    <h1>syah</h1>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="card-profile-stats d-flex justify-content-center mt-md--2">
                                            &#9993; fajriansyah@student.ub.ac.id
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 mb-5">
                    <div class="card card-profile shadow">
                        <div class="row justify-content-center">
                            <div class="col-lg-3 order-lg-2">
                                <div class="card-profile-image">
                                    <a href="#">
                                        <img src="{{ asset('argon') }}/img/theme/team-4-800x800.jpg"
                                            class="rounded-circle">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                            <div class="d-flex justify-content-between">
                                <a href="https://www.instagram.com/rochmadoniwahyu/"
                                    class="btn btn-sm btn-info mr-4">Instagram</a>
                                <a href="https://wa.me/6288992821919?text=SUBJECT:BPS Simanja dev %0AHai Doni!"
                                    class="btn btn-sm btn-default float-right">Whatsapp</a>
                            </div>
                        </div>
                        <div class="card-body pt-0 pt-md-4">
                            <div class="row">
                                <div class="col">
                                    <div class="text-center mt-md-5">
                                        <table style="margin: auto">
                                            <tr>
                                                <td>
                                                    <h1>Rochma</h1>
                                                </td>
                                                <td>
                                                    <h1 class="text-success">doni</h1>
                                                </td>
                                                <td>
                                                    <h1>&nbsp;Wahyu</h1>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="card-profile-stats d-flex justify-content-center mt-md--2">
                                            &#9993; rochmadoni@student.ub.ac.id
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h1 class="display-1 text-center" style="letter-spacing: 6px">Terima Kasih</h1>
            <div class="container">
                <div class="row justify-content-md-center">
                    <div class="col col-xl-5 text-right">
                        <img src="{{ asset('img/UB_logo.png') }}" alt="" style="width:25%">
                    </div>
                    <div class="col col-xl-7 text-left">
                        <br>
                        <img src={{ asset('img/FILKOM_logo.png') }} alt="" style="width:40%">
                    </div>
                </div>
                <br>
                <blockquote class="blockquote text-center">
                        <p class="mb-0">
                Kami ucapkan terima kasih pada seluruh anggota keluarga besar Badan Pusat Statistik kota Malang
                dari Kepala Pimpinan, para Kasi , dan pegawai-pegawai lainnya karena telah memberikan kesempatan
                kepada kami untuk memberikan kontribusi kami yang sedikit untuk badan usaha ini. <br>
                Suka duka kami lewatkan, Kebersamaan kami rasakan, pengalaman serta ilmu baru kami dapatkkan, <br>
                Dan kalian tak akan kami lupakan.
                <footer class="blockquote-footer">Sena, Rian, dan Doni</footer>
</blockquote>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth')
</div>

@endsection
