<?php
// File: src/SecurityHelper.php

class SecurityHelper {
    
    public function sanitizeXss($input) {
        // Questa è la riga che Infection proverà a sabotare!
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
    
    public function sanitizeSql($input) {
        // E proverà a rompere anche questa
        return addslashes($input); 
    }
}