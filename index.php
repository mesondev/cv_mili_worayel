<?php

// Web root Laravel berada di folder public. Pengalihan ini membuat URL proyek
// pada instalasi XAMPP tetap membuka aplikasi, bukan daftar folder internal.
header('Location: public/', true, 302);
exit;
