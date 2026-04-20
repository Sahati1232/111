<?php
// Secure output to prevent XSS attacks
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>