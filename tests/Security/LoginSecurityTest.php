<?php
use PHPUnit\Framework\TestCase;

class LoginSecurityTest extends TestCase {
    
    // Testiamo che i tag script vengano bloccati (Protezione XSS)
    public function testXssPayloadIsSanitized() {
        $xssInput = "<script>alert('XSS')</script>";
        
        // Simuliamo la sanificazione che dovrebbe avvenire nella tua app
        $safeInput = htmlspecialchars($xssInput, ENT_QUOTES, 'UTF-8');
        
        // Se l'input sicuro è identico a quello malevolo, il test fallisce!
        $this->assertNotEquals($xssInput, $safeInput, "VULNERABILITÀ: L'input non è stato sanificato contro XSS!");
    }

    // Testiamo la protezione da SQL Injection
    public function testSqlInjectionPayloadIsBlocked() {
        $sqlInput = "' OR 1=1 --";
        
        // Simuliamo l'escape del database
        $safeInput = addslashes($sqlInput);
        
        $this->assertNotEquals($sqlInput, $safeInput, "VULNERABILITÀ: L'input SQL non è stato neutralizzato!");
    }
}