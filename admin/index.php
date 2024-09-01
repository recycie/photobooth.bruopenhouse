<?php
session_start();
date_default_timezone_set('Asia/Bangkok');

// Handle logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
}

if (!isset($_SESSION['userid']) || $_SESSION['userid'] != 'admin') {
    header('Location: login.php');
}

include('../config.php');

if (isset($_GET['download'])) {
    require_once 'zipclass.php';

    $zip = new GoodZipArchive();

    $inputFolder = '../uptmp';
    $outputZipFile = 'openhouse-photo.zip';

    // Create the zip archive
    $zip->create_func($inputFolder, $outputZipFile);

    // Check if the zip file was created successfully
    if (file_exists($outputZipFile)) {
        // Set headers to force download of the zip file
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . basename($outputZipFile) . '"');
        header('Content-Length: ' . filesize($outputZipFile));
        readfile($outputZipFile);
        unlink($outputZipFile);
    } else {
        echo 'Error: Could not create the zip file.';
    }
}

// Handle session expiration
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 86400)) {
    session_unset();
    session_destroy();
}

$directory = "../uptmp";
$images = array_slice(glob("$directory/*.png"), 0, 10);
$new_images = [];

foreach ($images as $img) {
    $new_images[filemtime($img)] = $img;
}
krsort($new_images);

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRU Open House | Project v6.2111</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="../js/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-rc.2/lazyload.js"></script>
</head>

<body class="bg-gray-900 text-gray-100">
    <nav class="bg-gray-800 text-white shadow-md">
        <div class="container mx-auto flex justify-between items-center p-4">
            <a href="#" class="text-xl font-bold">BRU Open House</a>
            <div class="hidden md:flex space-x-4">
                <a href="index.php" class="hover:bg-gray-700 px-4 py-2 rounded">Home</a>
                <a href="setup.php" class="hover:bg-gray-700 px-4 py-2 rounded">Setup</a>
                <a href="?logout" class="hover:bg-gray-700 px-4 py-2 rounded">Logout</a>
            </div>
            <button id="menu-toggle" class="md:hidden text-2xl">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div id="menu" class="md:hidden bg-gray-800 text-white p-4 space-y-2 hidden">
            <a href="index.php" class="block hover:bg-gray-700 px-4 py-2 rounded">Home</a>
            <a href="setup.php" class="block hover:bg-gray-700 px-4 py-2 rounded">Setup</a>
            <a href="?logout" class="block hover:bg-gray-700 px-4 py-2 rounded">Logout</a>
        </div>
    </nav>


    <div class="container mx-auto p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-100 mb-4">Photo <small class="text-sm">(<?= count($images) ?> รูป)</small></h2>
            <a class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition" href="?download">Download .zip</a>
            <hr class="my-4">
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php foreach ($new_images as $image): ?>
                <div data-qrcode="qrcode-<?= htmlspecialchars(basename($image, ".png")) ?>" data-link="<?= ENPOINT_URL_DOWNLOAD ?>/download.php?id=<?= basename($image, ".png") ?>" class="showImageButton bg-gray-800 rounded-lg overflow-hidden shadow-lg relative" style="width: 75%;">
                    <img class="lazyload w-full h-auto" src="<?= htmlspecialchars($image) ?>" alt="Image">
                    <div id="qrcode-<?= htmlspecialchars(basename($image, ".png")) ?>" class="hidden" v-loading="PanoramaInfo.bgenerateing">
                        <!-- QR Code will be generated here -->
                    </div>
                    <div class="p-4 text-center">
                        <small><?= htmlspecialchars(basename($image, ".png")) ?></small>
                    </div>
                </div>
                <script>
                    new QRCode(document.getElementById("qrcode-<?= htmlspecialchars(basename($image, ".png")) ?>"), {
                        text: "<?= ENPOINT_URL_DOWNLOAD ?>/download.php?id=<?= basename($image, ".png") ?>",
                        width: 450,
                        height: 450,
                        colorDark: "#363636",
                        colorLight: "#f5f5f5",
                        correctLevel: QRCode.CorrectLevel.L
                    });
                </script>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal structure for image display -->
    <div id="imageModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden">
        <div class="bg-white p-4 rounded relative max-w-lg mx-auto">
            <img id="modalImage" src="" alt="QR Code" class="max-w-full max-h-screen">

            <!-- Footer with buttons -->
            <footer class="mt-4 flex justify-center space-x-4">
                <a id="downloadButton" class="px-4 py-2 bg-indigo-500 text-white rounded">Download</a>
                <button id="closeModalButton" class="px-4 py-2 bg-gray-600 text-white rounded">Close</button>
            </footer>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.showImageButton').on('click', function() {
                qrcodeid = $(this).data('qrcode')
                qrcodeLink = $(this).data('link')
                image = $('#' + qrcodeid + ' > img')[0].currentSrc
   
                $('#downloadButton').attr('href', qrcodeLink)
                $('#modalImage').attr('src', image);
                $('#imageModal').removeClass('hidden');
            });

            $('#closeModalButton').on('click', function() {
                $('#imageModal').addClass('hidden');
            });

        });

    </script>
    <script>
        document.querySelectorAll("img.lazyload").forEach(img => img.classList.add('lazy'));
        document.getElementById('menu-toggle').addEventListener('click', () => {
            document.getElementById('menu').classList.toggle('hidden');
        });
    </script>

</body>

</html>