// Created By Miftah GanzZ [https://github.com/miftahganzz], Plisss don't delete credit

<?php
include 'functions.php';

$language = 'en';
$endpoint = $_SERVER['REQUEST_URI'];
if (strpos($endpoint, '/id') === 0) {
    $language = 'id';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Uploader</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>
<body class="bg-gray-900 p-8 text-white">

    <div class="container mx-auto max-w-full bg-gray-800 p-4 rounded-lg relative">
        <h1 class="text-3xl font-semibold mb-4 text-center"><?= translate('image_uploader', $language); ?></h1>
      <select id="languageDropdown" onchange="changeLanguage(this)">
          <option value="en" <?= ($language === 'en') ? 'selected' : ''; ?>>EN</option>
          <option value="id" <?= ($language === 'id') ? 'selected' : ''; ?>>ID</option>
      </select>
        <form method="POST" enctype="multipart/form-data" class="text-center" onsubmit="return validateForm()">
            <label for="fileInput" class="block text-gray-400 mb-2">
                <i class="fas fa-upload text-3xl mb-2"></i><br><?= translate('choose_image', $language); ?>
            </label>
            <input type="file" name="file" id="fileInput" class="m-auto py-3 px-4 rounded-md text-gray-800 bg-gray-200 w-72" required>
            <button type="submit" name="upload" class="bg-blue-500 text-white rounded p-3 mt-2"><i class="fas fa-cloud-upload"></i> <?= translate('upload', $language); ?></button>
            <div id="progress-bar"></div>
        </form>

        <?php
        if (isset($_POST['upload'])) {
            if (!isset($_FILES["file"])) {
        ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: '<?= translate('error', $language); ?>',
                        text: '<?= translate('select_image', $language); ?>',
                    });
                </script>
        <?php
            } else {
                $uploadDirectory = "file/";
                $fileExtension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                $uploadFile = $uploadDirectory . uniqid() . "." . $fileExtension;
                $progress = 0;

                echo '<script>
                        let timerInterval;
                        Swal.fire({
                            title: "' . translate('uploading', $language) . '",
                            html: \'<div class="progress"><div class="progress-bar"></div></div>\',
                            showConfirmButton: false,
                            onOpen: () => {
                                Swal.showLoading();
                                const progressSteps = Swal.getContent().querySelector(".progress-bar");
                                Swal.getContent().querySelector(".progress").style.display = "block";

                                timerInterval = setInterval(() => {
                                    progress += 10;
                                    if (progress <= 100) {
                                        progressSteps.style.width = progress + "%";
                                    }
                                    if (progress === 100) {
                                        clearInterval(timerInterval);
                                    }
                                }, 500);
                            }
                        });
                    </script>';

                $allowedExtensions = ["jpg", "jpeg", "png", "gif", "jfif", "webp", "bmp", "tiff", "svg", "ico", "tga", "dds", "hdr", "tif"];

                if (in_array($fileExtension, $allowedExtensions) && move_uploaded_file($_FILES["file"]["tmp_name"], $uploadFile)) {
                    $result = [
                        "name" => $_FILES["file"]["name"],
                        "url" => $uploadFile,
                        "size" => number_format($_FILES["file"]["size"] / 1024, 2) . " KB",
                        "html" => '<img src="' . $uploadFile . '" alt="Uploaded Image" />',
                        "bbcode" => '[img]' . $uploadFile . '[/img]',
                        "markdown" => '![Uploaded Image](' . $uploadFile . ')',
                    ];
        ?>
                    <div class="mt-4 text-center">
                        <div class="mb-2">
                            <img src="<?= $result['url'] ?>" alt="Uploaded File" class="max-w-md mx-auto rounded-md">
                        </div>
                        <p class="mt-2 text-sm text-gray-600"><?= translate('file_name', $language); ?>: <?= $result['name'] ?> | <?= translate('file_size', $language); ?>: <?= $result['size'] ?></p>
                        <button onclick="copyURL('<?= $result['url'] ?>')" class="bg-blue-500 text-white rounded p-2 mt-2"><?= translate('copy_url', $language); ?></button>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: '<?= translate('image_uploaded_successfully', $language); ?>',
                                showConfirmButton: false,
                                showCancelButton: true,
                                cancelButtonText: 'OK'
                            }).then((result) => {
                                if (result.isDismissed) {
                                    copyURL('<?= $result['url'] ?>');
                                }
                            });
                        </script>
                    </div>
        <?php
                } else {
        ?>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: '<?= translate('error', $language); ?>',
                            text: '<?= translate('image_upload_error', $language); ?>',
                            confirmButtonText: 'OK'
                        });
                    </script>
        <?php
                }
            }
        }
        ?>

        <script src="assets/script.js"></script>
        <script>
          function changeLanguage(select) {
              const language = select.value;
              window.location.href = '/' + language;
          }
        </script>
    </div>
</body>
</html>
