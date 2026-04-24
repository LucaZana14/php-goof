<?php
use PHPUnit\Framework\TestCase;

// Importiamo il vero codice della nostra applicazione
require_once __DIR__ . '/../../src/SecurityHelper.php';

class LoginSecurityTest extends TestCase {
    
    public function testXssPayloadIsSanitized() {
        $helper = new SecurityHelper();
        
        $xssInput = "<script>alert('XSS')</script>";
        $safeInput = $helper->sanitizeXss($xssInput); // Ora eseguiamo la TUA riga di codice
        
        $this->assertNotEquals($xssInput, $safeInput, "VULNERABILITÀ XSS!");
    }

    public function testSqlInjectionPayloadIsBlocked() {
        $helper = new SecurityHelper();
        
        $sqlInput = "' OR 1=1 --";
        $safeInput = $helper->sanitizeSql($sqlInput); // Eseguiamo la TUA riga di codice
        
        $this->assertNotEquals($sqlInput, $safeInput, "VULNERABILITÀ SQL!");
    }
}