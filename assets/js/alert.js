/* =====================================================
   ALERT.JS
   Sistem Informasi Akademik
===================================================== */

/* ==========================================
   SUCCESS
========================================== */

function showSuccess(title, message = "") {

    Swal.fire({
        icon: "success",
        title: title,
        text: message,
        confirmButtonColor: "#2563eb",
        confirmButtonText: "OK"
    });

}


/* ==========================================
   ERROR
========================================== */

function showError(title, message = "") {

    Swal.fire({
        icon: "error",
        title: title,
        text: message,
        confirmButtonColor: "#dc2626",
        confirmButtonText: "OK"
    });

}


/* ==========================================
   WARNING
========================================== */

function showWarning(title, message = "") {

    Swal.fire({
        icon: "warning",
        title: title,
        text: message,
        confirmButtonColor: "#f59e0b",
        confirmButtonText: "OK"
    });

}


/* ==========================================
   INFO
========================================== */

function showInfo(title, message = "") {

    Swal.fire({
        icon: "info",
        title: title,
        text: message,
        confirmButtonColor: "#0ea5e9",
        confirmButtonText: "OK"
    });

}


/* ==========================================
   QUESTION
========================================== */

function showQuestion(title, message = "") {

    return Swal.fire({
        icon: "question",
        title: title,
        text: message,
        confirmButtonColor: "#2563eb",
        showCancelButton: true,
        confirmButtonText: "Ya",
        cancelButtonText: "Batal"
    });

}


/* ==========================================
   CONFIRM DELETE
========================================== */

function confirmDelete(url) {

    Swal.fire({

        title: "Hapus Data?",

        text: "Data yang dihapus tidak dapat dikembalikan.",

        icon: "warning",

        showCancelButton: true,

        confirmButtonColor: "#dc2626",

        cancelButtonColor: "#6b7280",

        confirmButtonText: "Ya, Hapus",

        cancelButtonText: "Batal"

    }).then((result)=>{

        if(result.isConfirmed){

            window.location.href = url;

        }

    });

}


/* ==========================================
   CONFIRM LOGOUT
========================================== */

function confirmLogout(url) {

    Swal.fire({

        title: "Logout",

        text: "Apakah Anda yakin ingin keluar?",

        icon: "question",

        showCancelButton: true,

        confirmButtonColor: "#2563eb",

        cancelButtonColor: "#6b7280",

        confirmButtonText: "Ya",

        cancelButtonText: "Batal"

    }).then((result)=>{

        if(result.isConfirmed){

            window.location.href = url;

        }

    });

}


/* ==========================================
   TOAST SUCCESS
========================================== */

function toastSuccess(message) {

    Swal.fire({

        toast: true,

        position: "top-end",

        icon: "success",

        title: message,

        showConfirmButton: false,

        timer: 2500,

        timerProgressBar: true

    });

}


/* ==========================================
   TOAST ERROR
========================================== */

function toastError(message) {

    Swal.fire({

        toast: true,

        position: "top-end",

        icon: "error",

        title: message,

        showConfirmButton: false,

        timer: 2500,

        timerProgressBar: true

    });

}


/* ==========================================
   LOADING
========================================== */

function showLoading(message = "Memproses...") {

    Swal.fire({

        title: message,

        allowOutsideClick: false,

        didOpen: () => {

            Swal.showLoading();

        }

    });

}


/* ==========================================
   CLOSE LOADING
========================================== */

function closeLoading() {

    Swal.close();

}