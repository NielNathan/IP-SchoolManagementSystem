@extends('layouts.app')

@section('title','Student Dashboard')
@section('page-title','Student Dashboard')
@section('page-sub','Selamat datang! Berikut ringkasan belajar Anda.')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelas</p>
            <h3 class="text-3xl font-bold">X IPA 1</h3>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Mata Pelajaran</p>
            <h3 class="text-3xl font-bold">12</h3>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
            <p class="text-sm text-gray-500 dark:text-gray-400">Nilai Rata-rata</p>
            <h3 class="text-3xl font-bold">84</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
            <h4 class="font-semibold mb-4">Progress Nilai (Line)</h4>
            <canvas id="studentLineChart" height="160"></canvas>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
            <h4 class="font-semibold mb-4">Komposisi Nilai (Doughnut)</h4>
            <canvas id="studentDoughnutChart" height="160"></canvas>
        </div>

    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
        <h4 class="font-semibold mb-4">Tugas Terbaru</h4>

        <div class="space-y-3">
            <div class="p-4 border rounded-xl dark:border-gray-700 flex justify-between">
                <div>
                    <p class="font-semibold">Matematika — Limit</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Deadline: 5 Desember</p>
                </div>
                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-800 rounded-lg text-sm">Belum</span>
            </div>

            <div class="p-4 border rounded-xl dark:border-gray-700 flex justify-between">
                <div>
                    <p class="font-semibold">Biologi — Praktikum Enzim</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Deadline: 7 Desember</p>
                </div>
                <span class="px-3 py-1 bg-green-100 dark:bg-green-800 rounded-lg text-sm">Selesai</span>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    const lineEl = document.getElementById('studentLineChart');
    if (lineEl) {
        new Chart(lineEl.getContext('2d'), {
            type: 'line',
            data: {
                labels: ['Ulangan 1','Ulangan 2','UTS','Ulangan 3','UAS'],
                datasets: [{
                    label: 'Nilai',
                    data: [78, 82, 84, 88, 90],
                    borderWidth: 2,
                    tension: 0.3,
                    fill: false,
                }]
            }
        });
    }

    const doughEl = document.getElementById('studentDoughnutChart');
    if (doughEl) {
        new Chart(doughEl.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Tugas', 'Ulangan', 'UTS/UAS'],
                datasets: [{
                    data: [30, 40, 30],
                    hoverOffset: 4
                }]
            }
        });
    }

});
</script>
@endpush
