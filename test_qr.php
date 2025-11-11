<?php
include('includes/phpqrcode/qrlib.php');

$filename = 'imagenes/test_qr.png';
QRcode::png('https://www.google.com', $filename, QR_ECLEVEL_Q, 6);

echo "<h2>QR generado:</h2>";
echo "<img src='$filename'>";
?>
