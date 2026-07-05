<?php

include "config/koneksi.php";

if($koneksi)
{
    echo "Koneksi Database Berhasil";
}
else
{
    echo "Koneksi Database Gagal";
}

?>