<?php
// In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
// of $_FILES.

$uploaddir = '.\A\\';
$uploadfile = $uploaddir . basename($_FILES['uploadpic']['name']);
var_dump($_FILES);

echo '<pre>';
if (move_uploaded_file($_FILES['uploadpic']['tmp_name'], $uploadfile)) {
    echo "File is valid, and was successfully uploaded.\n";
} else {
    echo "Possible file upload attack!\n";
}

echo 'Here is some more debugging info:';
print_r($_FILES);

print "</pre>";

?>