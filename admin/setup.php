<?php
session_start();

if (!isset($_SESSION['userid']) || $_SESSION['userid'] != 'admin') {
    header('Location: login.php');
}

include('../function.php');
saveConfig(CONFIGFILE_ADMIN);

$config = loadConfig(CONFIGFILE_ADMIN);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Booth Configuration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        #photo-booth-container {
            position: relative;
            /* display: inline-block; */
        }

        #photo-booth-frame {
            position: relative;
            /* background-image: url(''); */
            background-size: cover;
            border: 1px solid #000;
        }

        .photo-area {
            position: absolute;
            background-color: rgba(255, 255, 255, 0.5);
            border: 2px dashed #000;
        }

        #frame-controls {
            margin-bottom: 10px;
        }

        #frame-controls input {
            width: 50px;
        }
    </style>
</head>

<body class="bg-gray-900 text-gray-100">
    <nav class="bg-gray-800 text-white shadow-md">
        <div class="container mx-auto flex justify-between items-center p-4">
            <a href="#" class="text-xl font-bold">BRU Open House</a>
            <div class="hidden md:flex space-x-4">
                <a href="index.php" class="hover:bg-gray-700 px-4 py-2 rounded">Home</a>
                <a href="setup.php" class="hover:bg-gray-700 px-4 py-2 rounded">Setup</a>
                <a href="index.php?logout" class="hover:bg-gray-700 px-4 py-2 rounded">Logout</a>
            </div>
            <button id="menu-toggle" class="md:hidden text-2xl">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div id="menu" class="md:hidden bg-gray-800 text-white p-4 space-y-2 hidden">
            <a href="index.php" class="block hover:bg-gray-700 px-4 py-2 rounded">Home</a>
            <a href="setup.php" class="block hover:bg-gray-700 px-4 py-2 rounded">Setup</a>
            <a href="index.php?logout" class="block hover:bg-gray-700 px-4 py-2 rounded">Logout</a>
        </div>
    </nav>

    <main class="container mx-auto p-6">
        <section class="bg-gray-800 shadow-md rounded-lg p-6 mb-6">
            <div class="flex items-center space-x-4 mb-4">
                <input type="file" id="frameImage"
                    class="file-input px-4 py-2 border border-gray-600 rounded-lg shadow-sm bg-gray-700 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                <button id="addRectangle"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">Add
                    Rectangle</button>
                <button id="removeRectangle"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">Remove
                    Rectangle</button>
                <button id="saveConfig"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">Save</button>
            </div>

            <div id="photo-booth-container"
                class="flex justify-between relative bg-gray-900 border border-gray-600 rounded-lg p-4">
                <div id="photo-booth-frame" class="relative rounded-lg overflow-hidden bg-gray-800">
                    <!-- Configured areas will be added dynamically here -->
                </div>
                <div id="photo-booth-render" class="relative rounded-lg overflow-hidden bg-gray-800">

                </div>
            </div>
        </section>

    </main>

    <video id="video" class="hidden" autoplay></video>
    <canvas id="canvasx" class="hidden"></canvas>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/3.0.1/js.cookie.js"
        integrity="sha512-DJw15+xxGmXB1/c6pvu2eRoVCGo5s6rdeswkFS4HLFfzNQSc6V71jk6t+eMYzlyakoLTwBrKnyhVc7SCDZOK4Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {
            <?php if ($config != null): ?>
                var savedConfig = <?= json_encode($config) ?>;
            <?php else: ?>
                var savedConfig = {};
            <?php endif; ?>

            var cameraStream = null
            var canvas = $('#canvasx')[0];
            var video = $('#video')[0];
            var rectangleCount = 1;
            let frameSize = {
                width: 0,
                height: 0
            };
            let frameOffset = {
                x: 0,
                y: 0
            };
            var frameimg, canvas_obj, ctx = null;
            var frameBG = new Image();

            var camera_device = null

            const getCameraSelection = async () => {
                const devices = await navigator.mediaDevices.enumerateDevices();
                const videoDevices = devices.filter(device => device.kind === 'videoinput');

                var options;
                var device_name = [];
                videoDevices.map(videoDevice => {
                    options += '<option value="' + videoDevice.deviceId + '">' + videoDevice.label + '</option>';
                });

                Swal.fire({
                    title: 'Setup Camera',
                    html: '<small>เลือกกล้อง<small><div class="control has-icons-left">' +
                        '<div class="select is-large">' +
                        '<select id="optionDevice">' + options + '</select>' +
                        '</div>' +
                        '</div>',
                    showCancelButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonText: 'ยืนยัน',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: "button is-link pd-btn"
                    }
                }).then((result) => {
                    let od = document.getElementById('optionDevice');
                    if (result.isConfirmed) {
                        camera_device = od.value;
                        Cookies.set('device', od.value);
                        window.location = '?success';
                    }
                });
            };

            if (!Cookies.get('device')) {
                getCameraSelection();
            } else {
                camera_device = Cookies.get('device');
                console.log('test')
            }

            function startCamera() {
                navigator.mediaDevices.getUserMedia({
                        video: true,
                        deviceId: {
                            exact: camera_device
                        }
                    })
                    .then(function(stream) {
                        cameraStream = stream;
                        video.srcObject = stream; // Set the video source to the stream
                    })
                    .catch(function(error) {
                        console.error('Error accessing camera: ', error);
                    });
            }

            function stopCamera() {
                if (cameraStream) {
                    cameraStream.getTracks().forEach(function(track) {
                        track.stop(); // Stop each track
                    });
                    video.srcObject = null; // Remove the stream from the video element
                }
            }


            function captureCamera() {
                var context = canvas.getContext('2d');

                if (video.readyState === video.HAVE_ENOUGH_DATA) {
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;

                    context.drawImage(video, 0, 0, canvas.width, canvas.height);

                    var dataURL = canvas.toDataURL('image/png');
                    return dataURL;
                } else {
                    console.error('Video is not ready for capture.');
                }
            }

            function waitForCameraReady(callback) {
                let isCameraReady = false;

                const timeout = setTimeout(function() {
                    if (!isCameraReady) {
                        console.error('Camera did not become ready within 3 seconds.');
                    }
                }, 3000); // 5 seconds timeout

                video.addEventListener('loadedmetadata', function() {
                    clearTimeout(timeout);
                    isCameraReady = true;
                    console.log('Camera is ready.');
                    callback();
                }, {
                    once: true
                });

                video.addEventListener('error', function() {
                    clearTimeout(timeout);
                    console.error('Failed to load the video stream.');
                }, {
                    once: true
                });
            }

            $('#frameImage').on('change', function(event) {
                var file = event.target.files[0];
                if (file) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        frameBG.src = e.target.result
                        $('#photo-booth-frame').css('background-image', 'url(' + e.target.result + ')');
                        startCamera()

                        waitForCameraReady(function() {
                            var capturedImage = captureCamera();
                            if (capturedImage) {
                                setupFrame()
                                renderBG()
                            }
                        });
                    };

                    reader.readAsDataURL(file);
                }
            });

            function renderBG() {
                frameimg = new Image();
                frameimg.src = frameBG.src
                canvas_obj = document.createElement("canvas");
                $('#photo-booth-render').html(canvas_obj);
                canvas_obj.classList.add('d-none');
                ctx = canvas_obj.getContext("2d");

                frameimg.onload = function() {
                    canvas_obj.width = frameimg.width;
                    canvas_obj.height = frameimg.height;

                    updateConfig()
                }
            }

            function drawImage(x, y, w, h) {
                var img = new Image();
                img.src = captureCamera();
                img.onload = function() {
                    var aspectRatio = img.width / img.height;
                    var newWidth, newHeight;

                    if (w / h > aspectRatio) {
                        newHeight = h;
                        newWidth = h * aspectRatio;
                    } else {
                        newWidth = w;
                        newHeight = w / aspectRatio;
                    }

                    var xOffset = x + (w - newWidth) / 2;
                    var yOffset = y + (h - newHeight) / 2;

                    ctx.drawImage(img, xOffset, yOffset, newWidth, newHeight);
                    imgs = canvas_obj.toDataURL("image/png");
                };
            }

            function setupFrame() {
                var img = new Image();
                img.onload = function() {
                    frameSize.width = img.width;
                    frameSize.height = img.height;
                    $('#photo-booth-frame').css({
                        width: frameSize.width,
                        height: frameSize.height
                    });

                    let containerRect = $('#photo-booth-container')[0].getBoundingClientRect();
                    let frameRect = $('#photo-booth-frame')[0].getBoundingClientRect();
                    frameOffset.x = frameRect.left - containerRect.left;
                    frameOffset.y = frameRect.top - containerRect.top;

                    if (savedConfig) {
                        for (let areaId in savedConfig) {
                            let areaConfig = savedConfig[areaId];
                            var newRectangle = $('<div class="photo-area"></div>').attr('id', areaId);
                            $('#photo-booth-frame').append(newRectangle);
                            $('#' + areaId).css({
                                top: areaConfig.top,
                                left: areaConfig.left,
                                width: areaConfig.width,
                                height: areaConfig.height
                            });
                            newRectangle.resizable({
                                containment: "#photo-booth-frame",
                                stop: updateConfig
                            }).draggable({
                                containment: "#photo-booth-frame",
                                stop: updateConfig
                            });
                            newRectangle.click(function() {
                                $('.photo-area').removeClass('selected');
                                $(this).addClass('selected');
                            });
                        }
                    }
                };
                img.src = frameBG.src
            }

            function addRectangle() {
                rectangleCount++;
                var newRectangle = $('<div class="photo-area"></div>').attr('id', 'box' + rectangleCount);

                $('#photo-booth-frame').append(newRectangle);
                $('#box' + rectangleCount).css({
                    top: 50 + rectangleCount * 50,
                    left: 50,
                    width: 150,
                    height: 150
                });

                newRectangle.resizable({
                    containment: "#photo-booth-frame",
                    stop: updateConfig
                }).draggable({
                    containment: "#photo-booth-frame",
                    stop: updateConfig
                });

                newRectangle.click(function() {
                    $('.photo-area').removeClass('selected');
                    $(this).addClass('selected');
                });

                updateConfig();
            }

            function removeSelectedRectangle() {
                var selectedElement = $('.photo-area.selected');

                if (selectedElement.length) {
                    console.log("Removing element with ID:", selectedElement.attr('id'));
                    selectedElement.remove();
                    updateConfig();
                } else {
                    console.log("No rectangle selected");
                }
            }

            $('#addRectangle').click(addRectangle);
            $('#removeRectangle').click(removeSelectedRectangle);


            $('.photo-area').resizable({
                containment: "#photo-booth-frame",
                stop: updateConfig
            }).draggable({
                containment: "#photo-booth-frame",
                stop: updateConfig
            });

            function updateConfig() {
                let config = {};
                ctx.drawImage(frameimg, 0, 0, canvas_obj.width, canvas_obj.height);
                $('.photo-area').each(function() {
                    let $this = $(this);
                    let position = $this.position();

                    config[this.id] = {
                        top: position.top,
                        left: position.left,
                        width: $this.width(),
                        height: $this.height()
                    };
                    drawImage(position.left, position.top, $this.width(), $this.height());
                });
            }

            $('#saveConfig').click(function() {
                let config = {};

                $('.photo-area').each(function() {
                    let $this = $(this);
                    let position = $this.position();
                    if (position.top != 0 && position.left != 0 && $this.width() != 0 && $this.height() != 0) {
                        config[this.id] = {
                            top: position.top,
                            left: position.left,
                            width: $this.width(),
                            height: $this.height()
                        };
                    }
                });

                if (Object.keys(config).length == 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'คุณยังไม่ได้สร้าง Rectangle',
                        showCancelButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        confirmButtonText: 'ยืนยัน',
                    });
                    return
                }

                // Convert the frame image to a data URL
                var frameImage = $('#photo-booth-frame').css('background-image').replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
                var imageData = '';

                if (frameImage) {
                    var img = new Image();
                    img.crossOrigin = 'Anonymous'; // Handle cross-origin images if necessary
                    img.onload = function() {
                        var canvas = document.createElement('canvas');
                        var ctx = canvas.getContext('2d');
                        canvas.width = img.width;
                        canvas.height = img.height;
                        ctx.drawImage(img, 0, 0);
                        imageData = canvas.toDataURL('image/png');

                        // Send the configuration and image data to the server
                        $.post('', {
                            config: JSON.stringify(config)
                        }).done(function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Done',
                                showCancelButton: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                confirmButtonText: 'ยืนยัน',
                            });
                            console.log("Configuration saved:", response);
                        }).fail(function(jqXHR, textStatus, errorThrown) {
                            console.error("Request failed:", textStatus, errorThrown);
                        });
                    };
                    img.src = frameImage;
                } else {
                    // Send only the configuration if no image is set
                    Swal.fire({
                        icon: 'error',
                        title: 'เลือกรูปก่อนบันทึกข้อมูล',
                        showCancelButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        confirmButtonText: 'ยืนยัน',
                    });
                }
            });


        });
    </script>

</body>

</html>