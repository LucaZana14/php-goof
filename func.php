<?php 
    require __DIR__.'/vendor/autoload.php';
    include("db.php");

    use League\CommonMark\CommonMarkConverter;

    // OTTIMO: Questo protegge la visualizzazione del Markdown
    $converter = new CommonMarkConverter(['html_input' => 'escape', 'allow_unsafe_links' => false]);

    if (isset($_GET['edid'])){

        $id = $_GET['edid'];

        // 1. Prepariamo la query con il segnaposto
        $stmt = mysqli_prepare($conn, "SELECT * FROM task WHERE id = ?");

        if($stmt) {
            // 2. Colleghiamo l'ID (usiamo "i" perché l'ID è un numero intero)
            mysqli_stmt_bind_param($stmt, "i", $id);
            
            // 3. Eseguiamo
            mysqli_stmt_execute($stmt);
            
            // 4. Recuperiamo il risultato
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result);
                $title = $row['title'];

                $_SESSION['message'] = 'Edit Task';
                $_SESSION['message_type'] = 'info';
            }
            
            // 5. Chiudiamo lo statement
            mysqli_stmt_close($stmt);
        }
    }
?>