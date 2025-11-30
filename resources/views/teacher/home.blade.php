@extends('layouts.app')

@section('title','Teacher Dashboard')
@section('page-title','Teacher Dashboard')
@section('page-sub','Ringkasan kegiatan mengajar & kelas Anda')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Kelas</p>
            <h3 class="text-3xl font-bold">3</h3>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Siswa Dibimbing</p>
            <h3 class="text-3xl font-bold">98</h3>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
            <p class="text-sm text-gray-500 dark:text-gray-400">Jadwal Hari Ini</p>
            <h3 class="text-3xl font-bold">2 Mata Pelajaran</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
            <h4 class="font-semibold mb-4">Nilai Rata-rata Kelas (Bar)</h4>
            <canvas id="teacherBarChart" height="160"></canvas>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
            <h4 class="font-semibold mb-4">Kehadiran Mingguan (Line)</h4>
            <canvas id="teacherLineChart" height="160"></canvas>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
        <h4 class="font-semibold mb-4">Kelas yang Anda Bimbing</h4>

        <ul class="space-y-3">
            <li class="p-4 border border-gray-200 dark:border-gray-700 rounded-xl flex justify-between">
                <span>Kelas X IPA 1</span>
                <span class="text-sm text-gray-500">32 siswa</span>
            </li>
            <li class="p-4 border border-gray-200 dark:border-gray-700 rounded-xl flex justify-between">
                <span>Kelas X IPA 2</span>
                <span class="text-sm text-gray-500">33 siswa</span>
            </li>
            <li class="p-4 border border-gray-200 dark:border-gray-700 rounded-xl flex justify-between">
                <span>Kelas XI IPA 1</span>
                <span class="text-sm text-gray-500">33 siswa</span>
            </li>
        </ul>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    const barEl = document.getElementById('teacherBarChart');
    if (barEl) {
        new Chart(barEl.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['X IPA1', 'X IPA2', 'XI IPA1'],
                datasets: [{
                    label: 'Rata-rata Nilai',
                    data: [82, 78, 85],
                    borderWidth: 1
                }]
            }
        });
    }

    const lineEl = document.getElementById('teacherLineChart');
    if (lineEl) {
        new Chart(lineEl.getContext('2d'), {
            type: 'line',
            data: {
                labels: ['Sen','Sel','Rab','Kam','Jum'],
                datasets: [{
                    label: 'Kehadiran (%)',
                    data: [95, 92, 90, 96, 93],
                    borderWidth: 2,
                    fill: false,
                }]
            }
        });
    }

});
</script>
@endpush
