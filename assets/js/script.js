/* ===================================================
   SCRIPT.JS
   Sistem Informasi Akademik
=================================================== */

document.addEventListener("DOMContentLoaded", function () {

    // ============================
    // SIDEBAR TOGGLE
    // ============================

    const sidebar = document.querySelector(".sidebar");
    const menuButton = document.getElementById("menuButton");

    if (menuButton && sidebar) {

        menuButton.addEventListener("click", function () {

            sidebar.classList.toggle("active");

        });

    }

});


/* ==========================================
   SHOW / HIDE PASSWORD
========================================== */

function togglePassword(inputId, iconId) {

    let input = document.getElementById(inputId);
    let icon = document.getElementById(iconId);

    if (!input || !icon) return;

    if (input.type === "password") {

        input.type = "text";

        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");

    } else {

        input.type = "password";

        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");

    }

}


/* ==========================================
   PREVIEW FOTO
========================================== */

function previewImage(input, previewId) {

    let preview = document.getElementById(previewId);

    if (!preview) return;

    if (input.files && input.files[0]) {

        let reader = new FileReader();

        reader.onload = function (e) {

            preview.src = e.target.result;

        };

        reader.readAsDataURL(input.files[0]);

    }

}


/* ==========================================
   KONFIRMASI LOGOUT
========================================== */

function confirmLogout() {

    return confirm();

}


/* ==========================================
   KONFIRMASI HAPUS
========================================== */

function confirmDelete() {

    return confirm();

}


/* ==========================================
   SCROLL TO TOP
========================================== */

window.onscroll = function () {

    let button = document.getElementById("btnTop");

    if (!button) return;

    if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {

        button.style.display = "block";

    } else {

        button.style.display = "none";

    }

};


function scrollToTop() {

    window.scrollTo({

        top: 0,

        behavior: "smooth"

    });

}


/* ==========================================
   SEARCH TABLE
========================================== */

function searchTable(inputId, tableId) {

    let input = document.getElementById(inputId);

    let filter = input.value.toUpperCase();

    let table = document.getElementById(tableId);

    let tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {

        let td = tr[i].getElementsByTagName("td");

        let found = false;

        for (let j = 0; j < td.length; j++) {

            if (td[j]) {

                let txt = td[j].textContent || td[j].innerText;

                if (txt.toUpperCase().indexOf(filter) > -1) {

                    found = true;

                    break;

                }

            }

        }

        tr[i].style.display = found ? "" : "none";

    }

}


/* ==========================================
   LOADING BUTTON
========================================== */

function loadingButton(button) {

    button.disabled = true;

    button.innerHTML =
        '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...';

}


/* ==========================================
   RESET BUTTON
========================================== */

function resetButton(button, text) {

    button.disabled = false;

    button.innerHTML = text;

}


/* ==========================================
   PREVIEW PDF
========================================== */

function previewPDF(inputId, outputId) {

    const input = document.getElementById(inputId);

    const output = document.getElementById(outputId);

    if (!input || !output) return;

    input.addEventListener("change", function () {

        const file = this.files[0];

        if (file) {

            output.src = URL.createObjectURL(file);

        }

    });

}

// ==============================
// JAM DIGITAL
// ==============================

function updateClock() {

    const now = new Date();

    const jam =
        String(now.getHours()).padStart(2, '0') + ":" +
        String(now.getMinutes()).padStart(2, '0') + ":" +
        String(now.getSeconds()).padStart(2, '0');

    const tanggal = now.toLocaleDateString('id-ID', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });

    const jamEl = document.getElementById("jam");
    const tanggalEl = document.getElementById("tanggal");

    if (jamEl) jamEl.innerHTML = jam;
    if (tanggalEl) tanggalEl.innerHTML = tanggal;
}

setInterval(updateClock, 1000);

updateClock();