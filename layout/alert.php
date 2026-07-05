<?php

// ==============================
// ALERT SUKSES
// ==============================
function alertSuccess($pesan, $redirect = "")
{
?>

<script>

Swal.fire({

    icon:'success',

    title:'Berhasil',

    text:'<?php echo $pesan; ?>',

    confirmButtonColor:'#2563eb'

}).then((result)=>{

    <?php
    if($redirect!=""){
    ?>

    window.location='<?php echo $redirect; ?>';

    <?php
    }
    ?>

});

</script>

<?php
}



// ==============================
// ALERT ERROR
// ==============================
function alertError($pesan)
{
?>

<script>

Swal.fire({

    icon:'error',

    title:'Gagal',

    text:'<?php echo $pesan; ?>',

    confirmButtonColor:'#dc2626'

});

</script>

<?php
}



// ==============================
// ALERT WARNING
// ==============================
function alertWarning($pesan)
{
?>

<script>

Swal.fire({

    icon:'warning',

    title:'Perhatian',

    text:'<?php echo $pesan; ?>',

    confirmButtonColor:'#f59e0b'

});

</script>

<?php
}



// ==============================
// ALERT INFO
// ==============================
function alertInfo($pesan)
{
?>

<script>

Swal.fire({

    icon:'info',

    title:'Informasi',

    text:'<?php echo $pesan; ?>',

    confirmButtonColor:'#0ea5e9'

});

</script>

<?php
}
?>