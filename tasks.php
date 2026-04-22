<?php
require('func.php');

if(isset($_POST['save_task'])){
    $title = $_POST['title']; // Niente urlencode, usiamo le Prepared Statements

    if(isset($_POST['edid'])) { 
        $edid = $_POST['edid'];
        // UPDATE sicuro
        $stmt = mysqli_prepare($conn, "UPDATE task SET title = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "si", $title, $edid);
    } else {
        // INSERT sicuro
        $stmt = mysqli_prepare($conn, "INSERT INTO task(title) VALUES (?)");
        mysqli_stmt_bind_param($stmt, "s", $title);
    }

    if($stmt) {
        if(mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = 'Task saved successfully';
            $_SESSION['message_type'] = 'success';
        } else {
            die("Execution failed: " . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);
    }

} elseif (isset($_GET['delid'])) {
    $id = $_GET['delid'];

    $stmt = mysqli_prepare($conn, "DELETE FROM task WHERE id = ?");
    
    if($stmt){
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        // Eseguiamo e controlliamo il risultato INSIEME
        if(mysqli_stmt_execute($stmt)){
            $_SESSION['message'] = 'Task removed successfully';
            $_SESSION['message_type'] = 'warning';
        } else {
            die("Execution failed: " . mysqli_stmt_error($stmt));
        }
        
        // Chiudiamo SOLO alla fine
        mysqli_stmt_close($stmt);
    } else {
        die("Errore nella preparazione della query: " . mysqli_error($conn));
    }
}

header('Location: index.php');
?>