<?php
// ปิดการเข้าถึงสคริปต์นี้หากไม่จำเป็น
// die("Access Denied");

// เริ่มต้นเซสชัน
session_start();

// นำเข้าไฟล์ฟังก์ชัน
include('function.php');

// โหลดการตั้งค่าจากไฟล์คอนฟิก
$config = loadConfig(CONFIGFILE);

// กำหนดเส้นทางของรูปภาพที่ใช้เป็นพื้นหลัง
$impath = "images/frame.png";

// ดึงประเภทไฟล์ของรูปภาพ
$type = pathinfo($impath, PATHINFO_EXTENSION);

// อ่านข้อมูลจากไฟล์รูปภาพ
$data = file_get_contents($impath);

// แปลงข้อมูลรูปภาพเป็นรูปแบบ Base64
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>BRU Open House | Project v8.1259</title>

    <!-- นำเข้า CSS จาก Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.2/css/bootstrap.min.css"
        integrity="sha512-CpIKUSyh9QX2+zSdfGP+eWLx23C8Dj9/XmHjZY2uDtfkdLGo0uY12jgcnkX9vXOgYajEKb/jiw67EYm+kBf+6g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- นำเข้า CSS จาก Bulma -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">

    <!-- นำเข้า FontAwesome สำหรับไอคอน -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- นำเข้า CSS หลัก -->
    <link rel="stylesheet" href="/css/main.css">

    <!-- นำเข้า jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.js"
        integrity="sha512-CX7sDOp7UTAq+i1FYIlf9Uo27x4os+kGeoT7rgwvY+4dmjqV0IuE/Bl5hVsjnQPQiTOhAX1O2r2j5bjsFBvv/A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- นำเข้า css-doodle -->
    <script src="https://unpkg.com/css-doodle@0.15.3/css-doodle.min.js"></script>
    <style>
        body {
            width: 100%;
            height: 100vh;
            margin: 0;
            background: #270f34;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .swal2-popup {
            width: 69rem !important;
            /* Adjust the width as needed */
            max-width: 100% !important;
            /* Ensure it doesn't exceed the viewport width */
        }

        css-doodle {
            --color: @p(#51eaea, #fffde1, #ff9d76, #FB3569);

            --rule: ( :doodle {
                    @grid: 3x1 / 40vmin;
                    --deg: @p(-180deg, 180deg);
                }

                :container {
                    perspective: 30vmin;
                }

                :after, :before {
                    content: '';
                    background: var(--color);
                    @place-cell: @r(100%) @r(100%);
                    @size: @r(6px);
                    @shape: heart;
                }

                @place-cell: center;
                @size: 100%;
                box-shadow: @m2(0 0 50px var(--color));
                background: @m100(radial-gradient(var(--color) 50%, transparent 0) @r(-20%, 120%) @r(-20%, 100%) / 1px 1px no-repeat);
                will-change: transform, opacity;
                animation: scale-up 300s linear infinite;
                animation-delay: calc(-300s / @I * @i);

                @keyframes scale-up {
                    0%, 95%, 100% {
                        transform: translateZ(0) rotate(0);
                        opacity: 0;
                    }

                    10% {
                        opacity: 1;
                    }

                    95% {
                        transform: translateZ(30vmin) rotateZ(@var(--deg));
                    }
                })
        }
    </style>
</head>

<body>
    <?php
    // กำหนดตัวเลือกการผสมสีของวัตถุ doodle
    $items = [
        "empty" => "",
        "difference" => "difference",
        "exclusion" => "exclusion",
        "plus-lighter" => "plus-lighter",
        "luminosity" => "luminosity",
    ];
    ?>
    <!-- css-doodle สำหรับการแสดงกราฟิก -->
    <css-doodle use="var(--rule)" class="d-none" id="doodle"
        style="position: absolute;mix-blend-mode: <?= $items[array_rand($items)] ?>;opacity: 0.5;"></css-doodle>

    <!-- ส่วนของ UI หลัก -->
    <div class="h-100 d-flex align-items-center justify-content-center flex-column d-none" id="d">

        <!-- ปุ่มเริ่มต้นการใช้งาน -->
        <button class="button is-large is-responsive is-primary is-light title" id="btn-start"
            style="font: 2.5rem sans-serif;color: transparent;font-weight: 800;letter-spacing: 2px;position: relative;border-width: 1px;border-style: solid;border-image: radial-gradient(70% 6000% at 50% 100%,#bd3ff6 0,#66d9fb 60%) 1;background: radial-gradient(70% 6000% at 50% 100%,#bd3ff6 0,#66d9fb 60%);-webkit-background-clip: text;-webkit-animation: text 1.5s 1;">
            <i class="fa-solid fa-camera fa-xl fa-pull-left"> </i>S T A R T</button>
        <span class="xloader d-none" id="wl"></span>
        <span class="start-loader d-none" id="start-loader"></span>

        <!-- การแสดงผลวิดีโอ -->
        <div class="display-cover d-none" id="cdisplay">
            <video autoplay></video>
            <canvas class="d-none"></canvas>

            <!-- ตัวเลือกกล้อง -->
            <div class="video-options d-none">
                <select name="" id="" class="custom-select">
                    <option value=""></option>
                </select>
            </div>
        </div>

        <!-- ปุ่มถ่ายภาพ -->
        <div class="d-none" id="ts">
            <button class="take-screenshot" id="take-pic"></button>
        </div>

        <!-- แสดงภาพที่ถ่าย -->
        <div class="pic-left d-none" id="pic"></div>
        <span class="d-none" style="color:white;" id="pic-count"></span>

        <!-- การนับถอยหลัง -->
        <div class="overlay">
            <span id="take-cd" class="fw-bold" style="font-size: 18rem; color: white;"></span>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/3.0.1/js.cookie.js"
        integrity="sha512-DJw15+xxGmXB1/c6pvu2eRoVCGo5s6rdeswkFS4HLFfzNQSc6V71jk6t+eMYzlyakoLTwBrKnyhVc7SCDZOK4Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="js/qrcode.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/feather.min.js"></script>

    <script language="JavaScript">
        // รับค่าการตั้งค่าและเวลานับถอยหลังจาก PHP
        const config = <?= json_encode($config) ?>;
        const shootCountdown = <?= SHOTCOUNTDOWN ?>;

        // ตรวจสอบว่าการตั้งค่ากรอบรูปยังไม่ได้ถูกตั้งค่า
        if (config == null) {
            Swal.fire({
                title: 'คำเตือน',
                text: 'คุณยังไม่ได้ตั้งค่ากรอบรูป โปรดทำการตั้งค่ากรอบรูปก่อน',
                icon: 'warning',
                confirmButtonText: 'ตกลง',
                confirmButtonColor: '#3085d6',
                timer: 3000, // ปิดการแจ้งเตือนโดยอัตโนมัติหลังจาก 3 วินาที
                allowOutsideClick: false, // ป้องกันการปิดโดยการคลิกนอกหน้าต่าง
                allowEscapeKey: false, // ป้องกันการปิดด้วยปุ่ม ESC
            }).then((result) => {
                if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                    // เปลี่ยนเส้นทางไปยัง URL ที่กำหนด
                    window.location.href = 'admin/setup.php';
                }
            });
        }
        // กำหนดจำนวนภาพสูงสุดตามการตั้งค่า
        const max_picture = Object.keys(config).length;
        $('#pic-count').text('0/' + max_picture)
        var take_ = 0;
        // สร้างออบเจ็กต์ภาพ
        var imgobj_1 = new Image();
        var imgobj_2 = new Image();
        var imgobj_3 = new Image();
        var imgobj_4 = new Image();
        var imgobj_5 = new Image();
        var imgobj_6 = new Image();
        var imgobj_7 = new Image();
        var imgobj_8 = new Image();
        var imgobj_9 = new Image();
        var imgobj_10 = new Image();
        imgobj_1.src = "<?= $base64 ?>";

        // อาร์เรย์เก็บภาพและออบเจ็กต์สำหรับการควบคุม
        let arr_pic = [];
        let nload = document.getElementById('start-loader');
        let bodyhidden = document.getElementById('d');

        function delay(time) {
            return new Promise(resolve => setTimeout(resolve, time));
        }

        $(document).keydown(function(event) {
            if (event.ctrlKey && event.keyCode == 116) {
                Cookies.remove('device');
            }
        });

        document.onreadystatechange = async function() {
            if (document.readyState !== "complete") {
                nload.classList.add('d-none');
            } else {
                await delay(1000);
                if (export_status == false) {
                    nload.classList.remove('d-none');
                }

                console.log("ready");
            }
        };

        feather.replace();
        const play = document.getElementById('btn-start');
        const wl = document.getElementById('wl');
        const pic = document.getElementById('pic');
        const cdisplay = document.getElementById('cdisplay');
        const video = document.querySelector('video');
        const canvas = document.querySelector('canvas');
        const screenshot = document.getElementById('take-pic');
        let camera_device;
        let constraints;

        constraints = {
            video: {
                width: {
                    ideal: 1856
                },
                height: {
                    ideal: 1392
                },
            }
        };

        // เริ่มการสตรีมวิดีโอเมื่อคลิกปุ่มเริ่ม
        play.onclick = () => {
            if ('mediaDevices' in navigator && navigator.mediaDevices.getUserMedia) {
                const updatedConstraints = {
                    ...constraints,
                    deviceId: {
                        exact: camera_device
                    }
                };
                startStream(updatedConstraints);
            }
        };
        // ฟังก์ชันสำหรับถ่ายภาพ
        const doScreenshot = async () => {
            var start_loader = document.getElementById('wl');

            if (arr_pic.length < max_picture && take_ != 1) {
                take_ = 1;
                let c = $("#take-cd");
                for (let a = shootCountdown; a >= 1; a--) {
                    c.html(a);
                    await delay(1000);
                }
                c.html("");

                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                // ปรับปรุงฟิลเตอร์สำหรับภาพ
                canvas.getContext('2d').filter = 'brightness(1.1) contrast(1.2) saturate(1.3) sharpen(1.1)';;
                // canvas.getContext('2d').translate(canvas.width, 0);
                // canvas.getContext('2d').scale(-1,1); 
                canvas.getContext('2d').drawImage(video, 0, 0);

                cdisplay.classList.add('blur');
                // start_loader.classList.add('force-loader');
                // start_loader.classList.remove('d-none');

                count_picture = arr_pic.length + 1;
                Swal.fire({
                    html: '<small>' + count_picture + '/' + max_picture + '</small>',
                    imageUrl: canvas.toDataURL('image/webp'),
                    imageWidth: 1024,
                    imageAlt: 'photobooth',
                    showCancelButton: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ถ่ายใหม่',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: "button is-link pd-btn",
                        cancelButton: "button pd-btn"
                    }
                }).then((result) => {
                    cdisplay.classList.remove('blur');
                    if (result.isConfirmed) {
                        if (arr_pic.length == 0) {
                            pic.classList.remove('d-none');
                        }
                        arr_pic.push(canvas.toDataURL('image/jpeg'));
                        if (arr_pic.length == max_picture) {
                            screenshot.setAttribute('disabled', '')
                            export_pic();
                            sessionStorage.setItem('retry2export', 0); 
                            location.reload();
                        }
                    }
                });
                take_ = 0;
            } else {
                console.log("Take to limit.");
            }
        };

        screenshot.onclick = doScreenshot;

        // ฟังก์ชันเริ่มการสตรีมวิดีโอ
        const startStream = async (constraints) => {
            play.classList.add('d-none');
            nload.classList.add('d-none');
            wl.classList.remove('d-none');

            try {
                const stream = await navigator.mediaDevices.getUserMedia(constraints);
                handleStream(stream);
            } catch (error) {
                console.error('Error accessing media devices.', error);
            }
        };

        const handleStream = async (stream) => {
            video.srcObject = stream;
            await delay(500);
            wl.classList.add('d-none');
            cdisplay.classList.remove('d-none');
            screenshot.classList.remove('d-none');
            nload.classList.add('d-none');
            document.getElementById('ts').classList.remove('d-none');
            document.body.style.background = "#fff";
        };

        // ฟังก์ชันเลือกกล้อง
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
                },
            }).then((result) => {
                let od = document.getElementById('optionDevice');
                if (result.isConfirmed) {
                    camera_device = od.value;
                    Cookies.set('device', od.value);
                    window.location = '?success';
                }
            });
        };

        var canvas_obj = document.createElement("canvas");
        document.body.appendChild(canvas_obj);
        canvas_obj.classList.add('d-none');
        var ctx = canvas_obj.getContext("2d");

        //1280x3600
        // canvas_obj.width = imgobj_1.width*2;
        // canvas_obj.height = imgobj_1.height*2;

        // กำหนดขนาดของแคนวาส
        canvas_obj.width = imgobj_1.width;
        canvas_obj.height = imgobj_1.height;
        ctx.fillStyle = "#000000";
        ctx.fillRect(0, 0, canvas_obj.width, canvas_obj.height);

        ctx.drawImage(imgobj_1, 0, 0, canvas_obj.width, canvas_obj.height);

        var imgs = canvas_obj.toDataURL("image/png");
        var bg_size = imgs.length;
        var timerInterval;
        var count_a = 0;
        var export_status = false;

        // ฟังก์ชันคำนวณอัตราส่วนภาพ
        function aspectRatio(img, x, y, w, h) {
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

            return [xOffset, yOffset, newWidth, newHeight]
        }

        // Export
        if (sessionStorage['export']) {
            var data = JSON.parse(sessionStorage['export']);
            let del_start = document.getElementById('btn-start');
            del_start.classList.add('d-none');
            // let mp = 1;
            // let ds = 0.35;
            var xOffset, yOffset, newWidth, newHeight = NaN
            countData = 0
            Object.entries(config).forEach(([k, v]) => {
                if (countData == 0) {
                    imgobj_2.src = data[countData];
                    [xOffset, yOffset, newWidth, newHeight] = aspectRatio(imgobj_2, v.left, v.top, v.width, v.height)
                    ctx.drawImage(imgobj_2, xOffset, yOffset, newWidth, newHeight);
                } else if (countData == 1) {
                    imgobj_3.src = data[countData];
                    [xOffset, yOffset, newWidth, newHeight] = aspectRatio(imgobj_3, v.left, v.top, v.width, v.height)
                    ctx.drawImage(imgobj_3, xOffset, yOffset, newWidth, newHeight);
                } else if (countData == 2) {
                    imgobj_4.src = data[countData];
                    [xOffset, yOffset, newWidth, newHeight] = aspectRatio(imgobj_4, v.left, v.top, v.width, v.height)
                    ctx.drawImage(imgobj_4, xOffset, yOffset, newWidth, newHeight);
                } else if (countData == 3) {
                    imgobj_5.src = data[countData];
                    [xOffset, yOffset, newWidth, newHeight] = aspectRatio(imgobj_5, v.left, v.top, v.width, v.height)
                    ctx.drawImage(imgobj_5, xOffset, yOffset, newWidth, newHeight);
                } else if (countData == 4) {
                    imgobj_6.src = data[countData];
                    [xOffset, yOffset, newWidth, newHeight] = aspectRatio(imgobj_6, v.left, v.top, v.width, v.height)
                    ctx.drawImage(imgobj_6, xOffset, yOffset, newWidth, newHeight);
                } else if (countData == 5) {
                    imgobj_7.src = data[countData];
                    [xOffset, yOffset, newWidth, newHeight] = aspectRatio(imgobj_7, v.left, v.top, v.width, v.height)
                    ctx.drawImage(imgobj_7, xOffset, yOffset, newWidth, newHeight);
                } else if (countData == 6) {
                    imgobj_8.src = data[countData];
                    [xOffset, yOffset, newWidth, newHeight] = aspectRatio(imgobj_8, v.left, v.top, v.width, v.height)
                    ctx.drawImage(imgobj_8, xOffset, yOffset, newWidth, newHeight);
                } else if (countData == 7) {
                    imgobj_9.src = data[countData];
                    [xOffset, yOffset, newWidth, newHeight] = aspectRatio(imgobj_9, v.left, v.top, v.width, v.height)
                    ctx.drawImage(imgobj_9, xOffset, yOffset, newWidth, newHeight);
                } else if (countData == 8) {
                    imgobj_10.src = data[countData];
                    [xOffset, yOffset, newWidth, newHeight] = aspectRatio(imgobj_10, v.left, v.top, v.width, v.height)
                    ctx.drawImage(imgobj_10, xOffset, yOffset, newWidth, newHeight);
                }
                countData++
            });

            function displayResult(picId, imgs) {
                Swal.fire({
                    title: '',
                    html: `
                                        <p>จะปิดในอีก <b>0</b> วินาที.</p>
                                        <br>
                                        <div class="d-flex justify-content-center align-items-center">
                                            <figure class="m-0 mx-auto">
                                                <img src="${imgs}" alt="" width="250px">
                                            </figure>
                                            <div class="position-absolute top-10 start-50 translate-middle card p-3 bg-light rounded shadow">
                                                <div id="qrcode" class="v-loading="PanoramaInfo.bgenerateing">
                                                    <!-- QR Code will be generated here -->
                                                </div>
                                            </div>
                                        </div>
                                `,
                    timer: 120000,
                    /* showConfirmButton: true, */
                    confirmButtonText: 'Refresh',
                    showDenyButton: true,
                    denyButtonText: 'Close',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    /* timerProgressBar: true, */
                    didOpen: () => {
                        /* Swal.showLoading() */
                        const b = Swal.getHtmlContainer().querySelector('b')
                        let qrcode = new QRCode(document.getElementById("qrcode"), {
                            text: "<?= ENPOINT_URL_DOWNLOAD ?>/download.php?id=" + picId,
                            width: 150,
                            height: 150,
                            colorDark: "#363636",
                            colorLight: "#f5f5f5",
                            correctLevel: QRCode.CorrectLevel.L
                        });
                        timerInterval = setInterval(() => {
                            b.textContent = Math.round(Swal.getTimerLeft() / 1000);
                            if (count_a > 1) {
                                count_a = 0;

                                // QRCODE Check - เมื่อแสกน qrcode แล้วจะลบ cache รูปและเริ่มใหม่
                                $.ajax({
                                    type: "GET",
                                    url: "download.php?checksession=" + picId,
                                    success: async (respc) => {
                                        let resc = JSON.parse(respc);
                                        if (resc['status'] == 'success') {
                                            // ล้างข้อมูล cache รูปที่ถ่ายทั้งหมด
                                            sessionStorage.removeItem('export');
                                            sessionStorage.removeItem('picId');

                                            Swal.increaseTimer(-999999)
                                            await delay(5000);
                                            location.reload();
                                        }
                                    }
                                });

                            }
                            count_a++;
                        }, 1000)
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    } else if (result.dismiss === Swal.DismissReason.timer || result.isDenied) {
                        // ล้างข้อมูล cache รูปที่ถ่ายทั้งหมด
                        sessionStorage.removeItem('export');
                        sessionStorage.removeItem('picId');
                        location.reload();
                    }
                })
            }

            // ส่งออกรูปเป็นและสร้าง QRCODE
            imgs = canvas_obj.toDataURL("image/png");
            if (bg_size >= imgs.length && (sessionStorage['retry2export'] && sessionStorage['retry2export'] < 3)) {
                sessionStorage.setItem('retry2export', sessionStorage['retry2export']++); 
                location.reload();
            } else {
                sessionStorage.removeItem('retry2export');
                export_status = true;
                del_start.classList.remove('d-none');
                imgs = canvas_obj.toDataURL("image/png");
                
                picId = ''
                if (sessionStorage['picId']) {
                    picId = sessionStorage['picId']
                }

                $.ajax({
                    type: "POST",
                    url: "export.php",
                    data: {
                        export: '',
                        data: imgs,
                        id: picId
                    },
                    beforeSend: (e) => {
                        let xloading = document.getElementById('wl');
                        document.getElementById('d').classList.remove('d-none');
                        document.getElementById('start-loader').classList.add('d-none');
                        del_start.classList.add('d-none');
                        xloading.classList.remove('d-none');
                        console.log('waiting..');
                    },
                    success: (resp) => {
                        console.log('done.');
                        let res = JSON.parse(resp);
                        if (res['status'] == "success") {

                            // บันทึกรหัสรูปภาพ
                            sessionStorage.setItem('picId', res['msg']);
                            displayResult(res['msg'], imgs);

                        } else {
                            console.log("error");
                        }
                    }
                });
                
            }
        }

        function export_pic() {
            var jsonString = JSON.stringify(arr_pic);
            sessionStorage.removeItem('export');
            if (sessionStorage['picId']) {
                sessionStorage.removeItem('picId');
            }
            sessionStorage.setItem('export', jsonString);
        }

        if (!Cookies.get('device')) {
            getCameraSelection();
        } else {
            if (export_status == false) {
                bodyhidden.classList.remove('d-none');
            }
            document.getElementById('doodle').classList.remove('d-none');
            camera_device = Cookies.get('device');
        }
    </script>
    <script>
        window.onload = function() {
            document.addEventListener("contextmenu", function(e) {
                e.preventDefault();
            }, false);
            document.addEventListener("keydown", function(e) {
                if (e.ctrlKey && e.shiftKey && e.keyCode == 73) {
                    disabledEvent(e);
                }
                if (e.ctrlKey && e.shiftKey && e.keyCode == 74) {
                    disabledEvent(e);
                }
                if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
                    disabledEvent(e);
                }
                if (e.ctrlKey && e.keyCode == 85) {
                    disabledEvent(e);
                }
                if (event.keyCode == 123) {
                    disabledEvent(e);
                }
            }, false);

            function disabledEvent(e) {
                if (e.stopPropagation) {
                    e.stopPropagation();
                } else if (window.event) {
                    window.event.cancelBubble = true;
                }
                e.preventDefault();
                return false;
            }
        }
    </script>
</body>

</html>