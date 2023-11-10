<?php
// functions.php
function cleanURL($url) {
   
    $cleanedURL = str_replace("\\", "/", $url);
    $cleanedURL = preg_replace("#(https://+)/#i", "https://", $cleanedURL);

    return $cleanedURL;
}

function handleAPIRequest() {
    $response = [];

    if (isset($_GET['url']) && !empty($_GET['url'])) {
        $externalFileUrl = cleanURL($_GET['url']);
      
        $fileInfo = pathinfo($externalFileUrl);
        $fileName = $fileInfo['filename'];
        $fileExtension = $fileInfo['extension'];

        $fileContent = file_get_contents($externalFileUrl);

        if ($fileContent !== false) {
            $fileSizeBytes = strlen($fileContent);

            $fileSizeMB = number_format($fileSizeBytes / 1048576, 2);

            $localFilePath = "file/" . $fileName . "." . $fileExtension;
            file_put_contents($localFilePath, $fileContent);

            $localFileUrl = "https://" . $_SERVER['HTTP_HOST'] . "/" . $localFilePath;

            $data = [
                "file name" => $fileName,
                "file size" => $fileSizeMB . " MB",
                "mime" => mime_content_type($localFilePath),
                "path" => $localFilePath,
                "ext" => $fileExtension,
                "fileurl" => $localFileUrl,
            ];

            $response = [
                "author" => "Miftah GanzZ",
                "status" => "success",
                "code" => 200,
                "data" => $data,
            ];
        } else {
            $response = [
                "author" => "Miftah GanzZ",
                "status" => "error",
                "code" => 400,
                "message" => "Failed to download the file from the provided URL.",
            ];

            http_response_code(400);
        }
    } else {
        $response = [
            "author" => "Miftah GanzZ",
            "status" => "error",
            "code" => 400,
            "message" => "Parameter 'url' must be provided and not empty.",
        ];

        http_response_code(400);
    }

    header("Content-Type: application/json");
 
    $jsonResponse = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    echo $jsonResponse;
    exit;
}

if (isset($_GET['url'])) {
    handleAPIRequest();
}

function translate($text, $language) {
    $translations = [
        'en' => [
            'image_uploader' => 'Image Uploader',
            'choose_image' => 'Choose an image:',
            'upload' => 'Upload',
            'select_image' => 'Please select an image to upload.',
            'uploading' => 'Uploading...',
            'image_uploaded_successfully' => 'Image Uploaded Successfully!',
            'image_upload_error' => 'Failed to upload the image or unsupported image type.',
            'copy_url' => 'Copy URL',
            'url_copied' => 'URL Copied!',
            'select_language' => 'Select Language:',
        ],
        'id' => [
            'image_uploader' => 'Pengunggah Gambar',
            'choose_image' => 'Pilih gambar:',
            'upload' => 'Unggah',
            'select_image' => 'Silakan pilih gambar yang akan diunggah.',
            'uploading' => 'Mengunggah...',
            'image_uploaded_successfully' => 'Gambar Diunggah dengan Sukses!',
            'image_upload_error' => 'Gagal mengunggah gambar atau jenis gambar tidak didukung.',
            'copy_url' => 'Salin URL',
            'url_copied' => 'URL Disalin!',
            'select_language' => 'Pilih Bahasa:',
        ],
    ];

    return $translations[$language][$text] ?? $text;
}
?>
