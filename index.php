<?php
die("Access Denied");
session_start();
function generateRandomString($length = 10) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}


$imgs = glob("*.png");
foreach($imgs as $i ){
    $impath = $i;
    if(isset($_SESSION['count'])){
        $_SESSION['count']++;
        if($_SESSION['count'] >= 3){
            $impath = generateRandomString(). ".png";
            rename($i, $impath);
            $_SESSION['count'] = 0;
        }
    }else{
        $_SESSION['count'] = 1;
    }
}

$type = pathinfo($impath, PATHINFO_EXTENSION);
$data = file_get_contents($impath);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

?>
<!DOCTYPE html>
<html lang="th" >
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>BRU Open House | Project v8.1259</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.2/css/bootstrap.min.css" integrity="sha512-CpIKUSyh9QX2+zSdfGP+eWLx23C8Dj9/XmHjZY2uDtfkdLGo0uY12jgcnkX9vXOgYajEKb/jiw67EYm+kBf+6g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.js" integrity="sha512-CX7sDOp7UTAq+i1FYIlf9Uo27x4os+kGeoT7rgwvY+4dmjqV0IuE/Bl5hVsjnQPQiTOhAX1O2r2j5bjsFBvv/A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <style>
            html {
                overflow: hidden;
            }
            /* body {
                background: linear-gradient(-45deg, #000, #000, rgb(38 38 38), #000);
                background-size: 400% 400%;
                animation: gradient 15s ease infinite;
                margin: 0; 
                height: 100vh; 
                overflow: hidden;
            } */

            @keyframes gradient {
                0% {
                    background-position: 0% 50%;
                }
                50% {
                    background-position: 100% 50%;
                }
                100% {
                    background-position: 0% 50%;
                }
            }
            .start{
                position: absolute;
                top: 40%;
                left: 50%;
                transform: translate(-50%, -50%);
                text-align: center;
            }
            .overlay {
                position: absolute;
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-align: center;
                    -ms-flex-align: center;
                        align-items: center;
                -webkit-box-pack: center;
                    -ms-flex-pack: center;
                        justify-content: center;
            }
            .overlay h1 {
                font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
                color: rgb(255, 255, 255);
                font-size: 4rem;
                font-weight: 600;
                margin: -20rem 2rem 0;
                mix-blend-mode: hard-light;
                padding: 5px 15px;
                text-align: center;
            }
            .pic{
                margin-top: 15px;
                margin-bottom: 15px;
            }
            .pic-left {
                display: flex;
                position: absolute;
                top: 115%;
                border: 2px solid #303030;
                padding: 5px;
                border-radius: 3px;
                justify-content: space-evenly;
                opacity: 0.8;
            }
            .angles {
                background: linear-gradient(to right, #a6afc0 2px, transparent 2px) 0 0, linear-gradient(to bottom, #a6afc0 2px, transparent 2px) 0 0, linear-gradient(to left, #a6afc0 2px, transparent 2px) 100% 0, linear-gradient(to bottom, #a6afc0 2px, transparent 2px) 100% 0, linear-gradient(to left, #a6afc0 2px, transparent 2px) 100% 100%, linear-gradient(to top, #a6afc0 2px, transparent 2px) 100% 100%, linear-gradient(to right, #a6afc0 2px, transparent 2px) 0 100%, linear-gradient(to top, #a6afc0 2px, transparent 2px) 0 100%;
                background-repeat: no-repeat;
                background-size: 14px 14px;
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                opacity: 0.48;
                transition: background-size 0.2s cubic-bezier(0.455, 0.03, 0.515, 0.955);
            }
            .frame {
                display: inline-block;
                position: relative;
                top: 50%;
                left: 50%;
                transform: translate(-50%, 0);
                padding: 10px;
            }


            .screenshot-image {
                width: 150px;
                height: 90px;
                border-radius: 4px;
                border: 2px solid whitesmoke;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.1);
                background: white;
            }

            .display-cover {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 60%;
                margin: auto;
                position: relative;
            }

            video {
                width: 100%;
                background: rgba(0, 0, 0, 0.2);
                /* -moz-transform: scaleX(-1);
                -o-transform: scaleX(-1);
                -webkit-transform: scaleX(-1);
                transform: scaleX(-1);
                filter: FlipH;
                -ms-filter: "FlipH"; */
            }

            .take-screenshot{
                position: fixed;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                height: 100%;
                background: transparent;
                border: transparent;
            }
            @keyframes text {
                0%{
                    color: #66d9fb;
                    margin-bottom: -40px;
                }
                30%{
                    letter-spacing: 25px;
                    margin-bottom: -40px;
                }
                85%{
                    letter-spacing: 8px;
                    margin-bottom: -40px;
                }
            }
            @keyframes textx2 {
                0%{
                    color: transparent;
                }
                30%{
                    color: transparent;
                }
                85%{
                    color: transparent;
                }
            }
            .xloader {
                color: #fff;
                font-family: Consolas, Menlo, Monaco, monospace;
                font-weight: bold;
                font-size: 78px;
                opacity: 0.8;
            }
            .xloader:before {
                content: "{";
                display: inline-block;
                animation: pulse 0.4s alternate infinite ease-in-out;
            }
            .xloader:after {
                content: "}";
                display: inline-block;
                animation: pulse 0.4s 0.3s alternate infinite ease-in-out;
            }

            @keyframes pulse {
                to {
                transform: scale(0.8);
                opacity: 0.5;
                }
            }

            .pd-btn {
                margin: 0.3125em;
            }

            .start-loader {
                width: 16px;
                height: 16px;
                border-radius: 50%;
                background-color: #fff;
                box-shadow: 32px 0 #fff, -32px 0 #fff;
                position: relative;
                animation: flash 0.5s ease-in infinite alternate;
                margin-top: 15px;
            }
            .start-loader::before , .start-loader::after {
                content: '';
                position: absolute;
                left: -64px;
                top: 0;
                background: #FFF;
                width: 16px;
                height: 16px;
                border-radius: 50%;
                transform-origin: 35px -35px;
                transform: rotate(45deg);
                animation: hitL 0.5s ease-in infinite alternate;
            }

            .start-loader::after {
                left: 64px;
                transform: rotate(-45deg);
                transform-origin: -35px -35px;
                animation: hitR 0.5s ease-out infinite alternate;
            }

            @keyframes flash {
                0%  , 100%{
                    background-color: rgba(255, 255, 255, 0.50);
                    box-shadow: 32px 0 rgba(255, 255, 255, 0.50), -32px 0 rgba(255, 255, 255, 0.50);
                }
                25% {
                    background-color: rgba(255, 255, 255, 0.50);
                    box-shadow: 32px 0 rgba(255, 255, 255, 0.50), -32px 0 rgba(255, 255, 255, 1);
                }
                50% {
                    background-color: rgba(255, 255, 255, 1);
                    box-shadow: 32px 0 rgba(255, 255, 255, 0.50), -32px 0 rgba(255, 255, 255, 0.50);
                }
                75% {
                    background-color: rgba(255, 255, 255, 0.50);
                    box-shadow: 32px 0 rgba(255, 255, 255, 1), -32px 0 rgba(255, 255, 255, 0.50);
                }
            }

            @keyframes hitL {
                0% {
                    transform: rotate(45deg);
                    background-color: rgba(255, 255, 255, 1);
                }
                25% , 100% {
                    transform: rotate(0deg);
                    background-color: rgba(255, 255, 255, 0.50);
                }
            }

            @keyframes hitR {
                0% , 75% {
                    transform: rotate(0deg);
                    background-color: rgba(255, 255, 255, 0.50);
                }
                100% {
                    transform: rotate(-35deg);
                    background-color: rgba(255, 255, 255, 1);
                }
            }

            .force-loader{
                position: absolute;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%);
                z-index: 1000;
            }

            .blur{
                filter: blur(8px);
            }
        </style>

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
                /* contain: content; */
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
                    0%, 95.01%, 100% {
                    transform: translateZ(0) rotate(0);
                    opacity: 0;
                    }

                    10% {
                    opacity: 1;
                    }

                    95% {
                    transform: translateZ(30vmin) rotateZ(@var(--deg));
                    }
                }
                )
            }
        </style>
    </head>
    <body>
        <?php 
        $items = [
            "empty"=>"",
            "difference"=>"difference",
            "exclusion"=>"exclusion",
            "plus-lighter"=>"plus-lighter",
            "luminosity"=>"luminosity",
        ];
        ?>
        <css-doodle use="var(--rule)" class="d-none" id="doodle" style="position: absolute;mix-blend-mode: <?= $items[array_rand($items)] ?>;opacity: 0.5;"></css-doodle>
        <div class="h-100 d-flex align-items-center justify-content-center flex-column d-none" id="d">

            <button class="button is-large is-responsive is-primary is-light title" id="btn-start" style="font: 2.5rem sans-serif;color: transparent;font-weight: 800;letter-spacing: 2px;position: relative;border-width: 1px;border-style: solid;border-image: radial-gradient(70% 6000% at 50% 100%,#bd3ff6 0,#66d9fb 60%) 1;background: radial-gradient(70% 6000% at 50% 100%,#bd3ff6 0,#66d9fb 60%);-webkit-background-clip: text;-webkit-animation: text 1.5s 1;"><i class="fa-solid fa-camera fa-xl fa-pull-left"> </i>S T A R T</button>
            <span class="xloader d-none" id="wl"></span>
            <span class="start-loader d-none" id="start-loader"></span>

            <div class="display-cover d-none" id="cdisplay">
                <video autoplay></video>
                <canvas class="d-none"></canvas>
    
                <div class="video-options d-none">
                    <select name="" id="" class="custom-select">
                        <option value=""></option>
                    </select>
                </div>
            </div>

            <div class="d-none" id="ts">
                <button class="take-screenshot" id="take-pic"></button>
            </div>

            <div class="pic-left d-none" id="pic"></div>
            <span class="d-none" style="color:white;" id="pic-count">0/3</span>

            <div class="overlay"><h1 id="take-cd"></h1></div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/3.0.1/js.cookie.js" integrity="sha512-DJw15+xxGmXB1/c6pvu2eRoVCGo5s6rdeswkFS4HLFfzNQSc6V71jk6t+eMYzlyakoLTwBrKnyhVc7SCDZOK4Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="js/qrcode.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="js/feather.min.js"></script>

        <script language="JavaScript">
            var imgobj_1 = new Image();
            var imgobj_2 = new Image();
            var imgobj_3 = new Image();
            var imgobj_4 = new Image();
            var imgobj_5 = new Image();
            //imgobj_1.src = "<?= $impath ?>";
            imgobj_1.src = "<?= $base64 ?>";


            let arr_pic = [];
            let nload = document.getElementById('start-loader');
            let bodyhidden = document.getElementById('d');

            function delay(time) {
                return new Promise(resolve => setTimeout(resolve, time));
            }

            $(document).keydown(function (event) {
                if (event.ctrlKey && event.keyCode == 116) { 
                    Cookies.remove('device');
                }
            });

            document.onreadystatechange = async function() {
                if (document.readyState !== "complete") {
                    nload.classList.add('d-none');
                } else {
                    await delay(1500);
                    if (export_status == false){
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

            var take_ = 0;
            var suggest_msg = '';
            var max_picture = 4;
            const doScreenshot = async () => {
                var start_loader = document.getElementById('wl');

                if(arr_pic.length < max_picture && take_ != 1){
                    take_ = 1;
                    let c = $("#take-cd");
                    for(let a = 4; a >= 0; a--){
                        c.html(a);
                        await delay(1000);
                    }
                    c.html("");

                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    // canvas.getContext('2d').translate(canvas.width, 0);
                    // canvas.getContext('2d').scale(-1,1);
                    canvas.getContext('2d').drawImage(video, 0, 0);
            
                    cdisplay.classList.add('blur');
                    // start_loader.classList.add('force-loader');
                    // start_loader.classList.remove('d-none');

                    count_picture = arr_pic.length + 1;
                    Swal.fire({
                        html: '<small>'+count_picture+'/'+max_picture+'</small>',
                        imageUrl: canvas.toDataURL('image/webp'),
                        imageWidth: 450,
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
                            if (arr_pic.length == 0){
                                pic.classList.remove('d-none');
                            }
                            arr_pic.push(canvas.toDataURL('image/jpeg'));
                            if (arr_pic.length == max_picture){
                                screenshot.setAttribute('disabled', '')
                                export_pic();
                                location.reload();
                            }
                        }
                    });
                    take_ = 0;

                    // $.ajax({
                    //     type: "POST",
                    //     url: "https://face.api-matriz.net/predict",
                    //     data: {
                    //         img: canvas.toDataURL('image/webp')
                    //     },
                    //     beforeSend: (e) => {
                    //         // del_start.classList.add('d-none');
                    //         console.log('waiting..');
                    //     },
                    //     success: (resp) => {
                    //         start_loader.classList.add('d-none');
                    //         start_loader.classList.remove('force-loader');
                    //         console.log(resp);
                    //         suggest_msg = resp['result'];
                    //     }
                    // }).then(() => {
                    //     Swal.fire({
                    //         title: suggest_msg,
                    //         text: '',
                    //         imageUrl: canvas.toDataURL('image/webp'),
                    //         imageWidth: 500,
                    //         imageAlt: 'photobooth',
                    //         showCancelButton: true,
                    //         allowOutsideClick: false,
                    //         allowEscapeKey: false,
                    //         confirmButtonText: 'ยืนยัน',
                    //         cancelButtonText: 'ถ่ายใหม่',
                    //         buttonsStyling: false,
                    //         customClass: {
                    //             confirmButton: "button is-link pd-btn",
                    //             cancelButton: "button pd-btn"
                    //         }
                    //     }).then((result) => {
                    //         cdisplay.classList.remove('blur');
                    //         if (result.isConfirmed) {
                    //             if (arr_pic.length == 0){
                    //                 pic.classList.remove('d-none');
                    //             }
                    //             // var pic_source = '<img src="'+canvas.toDataURL('image/webp')+'" alt="" style="width: 33%;">';
                    //             // var a = pic.innerHTML;
                    //             // a = a + pic_source;
                    //             // pic.innerHTML = a;
                    //             arr_pic.push(canvas.toDataURL('image/jpeg'));
                    //             if (arr_pic.length == 2){
                    //                 screenshot.setAttribute('disabled', '')
                    //                 export_pic();
                    //                 location.reload();
                    //             }
                    //         }
                    //     });
                    //     take_ = 0;
                    // });
                }else{
                    console.log("Take to limit.");
                }
            };

            screenshot.onclick = doScreenshot;

            const startStream = async (constraints) => {
                play.classList.add('d-none');
                nload.classList.add('d-none');
                wl.classList.remove('d-none');
                const stream = await navigator.mediaDevices.getUserMedia(constraints);
                handleStream(stream);
            };


            const handleStream = async (stream) => {
                video.srcObject = stream;
                await delay(500);
                wl.classList.add('d-none');
                cdisplay.classList.remove('d-none');
                screenshot.classList.remove('d-none');
                nload.classList.add('d-none');
                document.getElementById('ts').classList.remove('d-none');
                document.body.style.background= "#fff";
            };


            const getCameraSelection = async () => {
                const devices = await navigator.mediaDevices.enumerateDevices();
                const videoDevices = devices.filter(device => device.kind === 'videoinput');

                var options;
                var device_name = [];
                videoDevices.map(videoDevice => {
                    options += '<option value="'+videoDevice.deviceId+'">'+videoDevice.label+'</option>';
                });

                Swal.fire({
                    title: 'Setup Camera',
                    html: '<small>เลือกกล้อง<small><div class="control has-icons-left">'
                        +'<div class="select is-large">'
                        +'<select id="optionDevice">'+options+'</select>'
                        +'</div>'
                        +'</div>',
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

            var canvas_obj = document.createElement("canvas");
            document.body.appendChild(canvas_obj);
            canvas_obj.classList.add('d-none');
            var ctx = canvas_obj.getContext("2d");



            //1280x3600
            canvas_obj.width = imgobj_1.width*2;
            canvas_obj.height = imgobj_1.height*2;
            ctx.fillStyle = "#000000";
            ctx.fillRect(0, 0, canvas_obj.width, canvas_obj.height);
            
            ctx.drawImage(imgobj_1, 0, 0, canvas_obj.width, canvas_obj.height);

            var imgs = canvas_obj.toDataURL("image/png");
            var bg_size = imgs.length;
            var timerInterval;
            var count_a = 0;
            var export_status = false;
            
            if(sessionStorage['export']){
                var data = JSON.parse(sessionStorage['export']);
                let del_start = document.getElementById('btn-start');
                del_start.classList.add('d-none');
                let mp = 1;
                let ds = 0.35;
                for (let c in data){
                    if(c == 0){
                        imgobj_2.src = data[c];
                        var new_width = (canvas_obj.width + imgobj_2.width) * ds;
                        var pec_width = ((imgobj_2.width-new_width)/imgobj_2.width);

                        var new_height = imgobj_2.height - (imgobj_2.height * pec_width);
                        var xOffset = (canvas_obj.width  - new_width ) * 0.5;
                        var yOffset = (canvas_obj.height  - new_height ) * 0.03;
                        ctx.drawImage(imgobj_2, xOffset, yOffset, new_width, new_height);
                        console.log(imgobj_2.width, imgobj_2.height);
                    }
                    if (c == 1){
                        imgobj_3.src = data[c];
                        yOffset = (yOffset + new_height) * 1.05;
                        ctx.drawImage(imgobj_3, xOffset, yOffset, new_width, new_height);
                    }
                    if (c == 2){
                        imgobj_4.src = data[c];
                        yOffset = (yOffset + new_height) * 1.024;
                        ctx.drawImage(imgobj_4, xOffset, yOffset, new_width, new_height);
                    }

                    if (c == 3){
                        imgobj_5.src = data[c];
                        yOffset = (yOffset + new_height) * 1.013;
                        ctx.drawImage(imgobj_5, xOffset, yOffset, new_width, new_height);
                    }

                }
                imgs = canvas_obj.toDataURL("image/png");

                if(bg_size >= imgs.length){
                    location.reload();
                }else{
                    export_status = true;
                    sessionStorage.removeItem('export');
                    del_start.classList.remove('d-none');
                    imgs = canvas_obj.toDataURL("image/png");
                    $.ajax({
                        type: "POST",
                        url: "export.php",
                        data: {
                            export: '',
                            data: imgs
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
                            if(res['status'] == "success"){
                                Swal.fire({
                                    title: '',
                                    html: '<figure class=""><img src="'+imgs+'" alt="" width="250px" ></figure><br><div id="qrcode" style="margin:auto;top: 35%;left: 50%;position: absolute; padding: 10px;background: #f5f5f5;border-radius: 15px;transform: translate(-50%, -50%);" v-loading="PanoramaInfo.bgenerateing"></div><br>จะปิดในอีก <b></b> วินาที.',
                                    timer: 120000,
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    timerProgressBar: true,
                                    didOpen: () => {
                                        Swal.showLoading()
                                        const b = Swal.getHtmlContainer().querySelector('b')
                                        let qrcode = new QRCode(document.getElementById("qrcode"), {
                                            text: "<?= $_SERVER["REQUEST_SCHEME"]?>://<?= $_SERVER["HTTP_HOST"] ?>/<?= trim(parse_url(dirname($_SERVER['REQUEST_URI']), PHP_URL_PATH), '/') ?>/download.php?id="+res['msg'],
                                            width: 100,
                                            height: 100,
                                            colorDark: "#363636",
                                            colorLight: "#f5f5f5",
                                            correctLevel: QRCode.CorrectLevel.L
                                        });
                                        timerInterval = setInterval(() => {
                                            b.textContent = Math.round(Swal.getTimerLeft()/1000);
                                            if(count_a > 1){
                                                count_a = 0;
                                                $.ajax({
                                                    type: "GET",
                                                    url: "download.php?checksession="+res['msg'],
                                                    success: async (respc) => {
                                                        let resc = JSON.parse(respc);
                                                        if(resc['status'] == 'success'){
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
                                    if (result.dismiss === Swal.DismissReason.timer) {
                                        location.reload();
                                    }
                                })
                            }else{
                                console.log("error");
                            }

                        }
                    });
                }
            }
            
            function export_pic(){
                var jsonString = JSON.stringify(arr_pic);
                sessionStorage.removeItem('export');
                sessionStorage.setItem('export', jsonString);
            }

            if(!Cookies.get('device')){
                getCameraSelection();
            }else{
                if (export_status == false){
                    bodyhidden.classList.remove('d-none');
                }
                document.getElementById('doodle').classList.remove('d-none');
                camera_device = Cookies.get('device');
            }

        </script>
        <script>
            window.onload = function () {
                document.addEventListener("contextmenu", function (e) {
                    e.preventDefault();
                }, false);
                document.addEventListener("keydown", function (e) {
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