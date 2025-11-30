<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - IP School</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

    <div class="flex">

        <aside class="w-64 h-screen bg-white shadow-md fixed">
            <div class="p-6 border-b">
                <h1 class="font-bold text-lg">IP School</h1>
                <p class="text-sm text-gray-600">Dashboard</p>
            </div>

            <nav class="mt-4">
                <a href="#" class="block py-3 px-6 hover:bg-blue-50">🏠 Dashboard</a>
                <a href="#" class="block py-3 px-6 hover:bg-blue-50">👨‍🎓 Students</a>
                <a href="#" class="block py-3 px-6 hover:bg-blue-50">👨‍🏫 Instructors</a>
                <a href="#" class="block py-3 px-6 hover:bg-blue-50">📚 Courses</a>
                <a href="#" class="block py-3 px-6 hover:bg-blue-50">📖 Subjects</a>
                <a href="#" class="block py-3 px-6 hover:bg-blue-50">📅 Schedules</a>

                <hr class="my-4">

                <button onclick="logout()" class="block py-3 px-6 text-red-600 hover:bg-red-50 w-full text-left">
                    🚪 Logout
                </button>
            </nav>
        </aside>

        <main class="ml-64 w-full">

            <header class="bg-white shadow p-4 flex justify-between items-center">
                <h2 class="text-xl font-semibold">Dashboard</h2>

                <div id="user-info" class="font-medium text-gray-700">
                    Memuat...
                </div>
            </header>

            <div class="p-6">

                <h3 class="text-lg font-semibold mb-4">Statistik Sekolah</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div class="bg-white p-6 rounded-xl shadow">
                        <p class="text-gray-500">Total Siswa</p>
                        <h1 id="total_students" class="text-3xl font-bold">-</h1>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow">
                        <p class="text-gray-500">Total Guru</p>
                        <h1 id="total_instructors" class="text-3xl font-bold">-</h1>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow">
                        <p class="text-gray-500">Total Mata Pelajaran</p>
                        <h1 id="total_subjects" class="text-3xl font-bold">-</h1>
                    </div>

                </div>


                <div class="mt-10">

                    <h3 class="text-lg font-semibold mb-4">Jadwal Hari Ini</h3>

                    <div class="bg-white p-6 rounded-xl shadow">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b">
                                    <th class="pb-3">Mata Pelajaran</th>
                                    <th class="pb-3">Guru</th>
                                    <th class="pb-3">Waktu</th>
                                </tr>
                            </thead>
                            <tbody id="schedule_table">
                                <tr>
                                    <td colspan="3" class="py-4 text-center text-gray-500">Memuat...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>

        </main>

    </div>

    <script>
        async function loadDashboard() {

            try {
                const base = "http://localhost:8000/api";

                const [students, instructors, subjects, schedule] = await Promise.all([
                    fetch(base + "/students").then(r => r.json()),
                    fetch(base + "/instructors").then(r => r.json()),
                    fetch(base + "/subjects").then(r => r.json()),
                    fetch(base + "/schedule/today").then(r => r.json()),
                ]);

                // update angka di card
                document.getElementById("total_students").textContent = students.total || 0;
                document.getElementById("total_instructors").textContent = instructors.total || 0;
                document.getElementById("total_subjects").textContent = subjects.total || 0;

                // update jadwal hari ini
                const tbody = document.getElementById("schedule_table");
                tbody.innerHTML = "";

                if (schedule.data && schedule.data.length > 0) {
                    schedule.data.forEach(item => {
                        tbody.innerHTML += `
                            <tr class="border-b">
                                <td class="py-3">${item.subject}</td>
                                <td class="py-3">${item.instructor}</td>
                                <td class="py-3">${item.time_start} - ${item.time_end}</td>
                            </tr>
                        `;
                    });
                } else {
                    tbody.innerHTML = `<tr><td colspan="3" class="py-4 text-center text-gray-500">Tidak ada jadwal hari ini</td></tr>`;
                }

                document.getElementById("user-info").textContent = "Selamat datang, Admin";

            } catch (e) {
                console.error(e);
                alert("Gagal memuat data dashboard");
            }
        }

        loadDashboard();

        function logout() {
            alert("Anda telah logout");
            window.location.href = "login.html";
        }
    </script>

</body>
</html>
