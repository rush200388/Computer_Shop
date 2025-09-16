<?php
if(isset($_GET['file'])){
    $file = basename($_GET['file']); // sanitize
    $path = "../uploads/" . $file;   // outside public

    if(file_exists($path)){
        header('Content-Type: ' . mime_content_type($path));
        readfile($path);
        exit;
    }
}
http_response_code(404);
?>
