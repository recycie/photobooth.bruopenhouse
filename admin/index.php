<?php 
die("Access Denied");
session_start();
date_default_timezone_set('Asia/Bangkok');

if(isset($_GET['login'])){
    if($_GET['login'] === 'cs@openhouse'){
        $_SESSION['LAST_ACTIVITY'] = time();
        $_SESSION['userid'] = 'admin';
        echo "success";
        exit;
    }
}

if(isset($_GET['logout'])){
    session_unset();
    session_destroy();
    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.js" integrity="sha512-CX7sDOp7UTAq+i1FYIlf9Uo27x4os+kGeoT7rgwvY+4dmjqV0IuE/Bl5hVsjnQPQiTOhAX1O2r2j5bjsFBvv/A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        setTimeout((e) => {
            window.location = ''
        }, 100);
    </script>
    <?php
}

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 86400)) {
    session_unset();
    session_destroy();
}

$directory = "../uptmp";
$images = glob($directory . "/*.png");
$new_images = array();
foreach($images as $img){
    $new_images[filemtime($img)] = $img;
}
krsort($new_images);


?>
<!DOCTYPE html>
<html lang="th" >
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>BRU Open House | Project v6.2111</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="<?= $_SERVER["REQUEST_SCHEME"]?>://<?= $_SERVER["HTTP_HOST"] ?>/css/bootstrap.css" rel="stylesheet">

        <script src="../js/qrcode.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-rc.2/lazyload.js"></script>
    </head>
    <body>
        <?php 
        if(isset($_SESSION['userid']) && ($_SESSION['userid'] === 'admin')){ ?>
        <div class="section section-body">
            <div class="container">
                <div class="section-title">
                    <h2 class="title gray">Photo <small class="subtitle is-6">(<?= sizeof($images)?> รูป)</small></h2>
                    <a class="button is-link" href="?download">Download</a>
                    <hr>
                </div>
                <div class="topupcard-wrapper">
                    <div class="row">
                        <?php
                        $i=0;
                        foreach($new_images as $image){
                            if($i>10){
                                break;
                            }
                            $i++;
                            // $type = pathinfo($image, PATHINFO_EXTENSION);
                            // $data = file_get_contents($image);
                            // $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                          ?>
                            <div class="col-lg-3 col-sm-4 col-6">
                                <a class="card card-topup" href="<?= $_SERVER["REQUEST_SCHEME"]?>://<?= $_SERVER["HTTP_HOST"] ?>/download.php?id=<?= basename($image, ".png") ?>">
                                    <div class="card-photo">
                                        <img class="lazyload" src="<?= $image ?>" width="299.5" height="901">
                                        <div id="qrcode-<?= $image ?>" style="margin:auto;top: 35%;left: 50%;position: absolute; padding: 10px;background: #f5f5f5;border-radius: 15px;transform: translate(-50%, -50%);" v-loading="PanoramaInfo.bgenerateing"></div>
                                    </div>
                                    <div class="card-body text-center">
                                        <small><?= basename($image, ".png") ?></small>
                                    </div>
                                </a>
                            </div>
                            <script>
                                qrcode = new QRCode(document.getElementById("qrcode-<?= $image ?>"), {
                                    text: "<?= $_SERVER["REQUEST_SCHEME"]?>://<?= $_SERVER["HTTP_HOST"] ?>/download.php?id=<?= basename($image, ".png") ?>",
                                    width: 100,
                                    height: 100,
                                    colorDark: "#363636",
                                    colorLight: "#f5f5f5",
                                    correctLevel: QRCode.CorrectLevel.L
                                });
                            </script>
                          <?php
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
        <script>
            $("img.lazyload").lazyload();
        </script>
        <?php
        }else{ ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.js" integrity="sha512-CX7sDOp7UTAq+i1FYIlf9Uo27x4os+kGeoT7rgwvY+4dmjqV0IuE/Bl5hVsjnQPQiTOhAX1O2r2j5bjsFBvv/A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>

            Swal.fire({
                title: 'LOGIN',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                allowOutsideClick: false,
                allowEscapeKey: false,
                showCancelButton: false,
                confirmButtonText: 'Login',
                showLoaderOnConfirm: true,
                preConfirm: (login) => {
                    return fetch(`index.php?login=${login}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText)
                        }
                        return response
                    })
                    .catch(error => {
                        Swal.showValidationMessage(
                        `รหัสผ่านไม่ถูกต้อง`
                        )
                    })
                },
                allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: `Login Success!`,
                        })
                        setTimeout((e) => {
                            window.location = '?success'
                        }, 3000);
                    }
            })
        </script>
        
        
        <?php
        }
        ?>
    </body>
</html>
