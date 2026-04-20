<?php
// Secure output to prevent XSS attacks
function h($string) {
    if ($string === null) {
        return '';
    }
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>