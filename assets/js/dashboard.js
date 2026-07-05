/* =====================================================
   DASHBOARD.JS
   Sistem Informasi Akademik
===================================================== */

document.addEventListener("DOMContentLoaded", function () {

    // ==============================
    // Jalankan ketika Dashboard dibuka
    // ==============================

    if (document.getElementById("dashboard-page")) {

        loadStatistic();
        loadClock();

    }

});


/* ==========================================
   LOAD STATISTIK DASHBOARD
========================================== */

function loadStatistic() {

    // Nantinya data diambil dari database menggunakan AJAX

    // Contoh sementara
    setValue("totalMahasiswa", 250);
    setValue("totalDosen", 35);
    setValue("totalMatkul", 60);
    setValue("totalKRS", 180);

}


/* ==========================================
   SET VALUE
========================================== */

function setValue(id, value) {

    let element = document.getElementById(id);

    if (!element) return;

    element.innerHTML = value;

}


/* ==========================================
   JAM DIGITAL
========================================== */

function loadClock() {

    updateClock();

    setInterval(updateClock, 1000);

}

function updateClock() {

    let now = new Date();

    let jam =
        String(now.getHours()).padStart(2, "0");

    let menit =
        String(now.getMinutes()).padStart(2, "0");

    let detik =
        String(now.getSeconds()).padStart(2, "0");

    let waktu = jam + ":" + menit + ":" + detik;

    let clock = document.getElementById("clock");

    if (clock) {

        clock.innerHTML = waktu;

    }

}


/* ==========================================
   FORMAT TANGGAL
========================================== */

function formatTanggal() {

    let tanggal = new Date();

    let hari = [
        "Minggu",
        "Senin",
        "Selasa",
        "Rabu",
        "Kamis",
        "Jumat",
        "Sabtu"
    ];

    let bulan = [
        "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember"
    ];

    let hasil =
        hari[tanggal.getDay()] + ", " +
        tanggal.getDate() + " " +
        bulan[tanggal.getMonth()] + " " +
        tanggal.getFullYear();

    let element = document.getElementById("tanggal");

    if (element) {

        element.innerHTML = hasil;

    }

}

formatTanggal();


/* ==========================================
   ANIMASI ANGKA
========================================== */

function animateNumber(id, target) {

    let element = document.getElementById(id);

    if (!element) return;

    let start = 0;

    let interval = setInterval(function () {

        start++;

        element.innerHTML = start;

        if (start >= target) {

            clearInterval(interval);

        }

    }, 10);

}


/* ==========================================
   REFRESH DASHBOARD
========================================== */

function refreshDashboard() {

    loadStatistic();

    formatTanggal();

}