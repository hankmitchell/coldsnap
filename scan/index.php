    <?php

    extract($_GET);
    extract($_POST);
    date_default_timezone_set('America/New_York');
    $timestamp = date('Y-m-d--') . date('H-i-s');

    if ($s) {
        // header("Access-Control-Allow-Origin: *");
        // header("Content-Type: text/plain; charset=utf-8");
        $j = json_decode(file_get_contents('../fac.json'), true);

        foreach ($j as $k => $v) {
            foreach ($v['assets'] as $k2 => $v2) {
                if ($v2['uid'] == $s) {
                    $js = json_encode($v2);
                    $out = "<script> var j = $js;</script>";
                }
            }
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Asset scan <?php echo $s ?></title>
    </head>

    <style>
        * {
            font-family: 'Segoe UI', sans-serif;
            font-size: 20px;
            background: #333;
            color: #eee;
        }

        input,
        textarea {
            border: 0;
            background: #eee;
            color: #333;
            padding: 12px;
            border-radius: 12px;
            width: 400px;
            font-size: 20px;
        }

        .bbtn {

            border-radius: 104px;
            padding: 52px;
            border: 0;
            color: white;
            line-height: 20px;
            background: rgb(22, 22, 22);
            color: white;
        }

        .grey {
            background: grey;
            opacity: .3;

        }

        .yes {
            opacity: 1;
            background: #189918;

        }

        .no {
            opacity: 1;
            background: #c31818;
        }



        @media all and (max-width:800px) {

            textarea,
            input {
                width: 90%;
            }
        }
    </style>

    <body>

        <?php echo $out ?>

        <script>
            var sampleQRurl = ` http://silvercrayon.com/apps/qrac/gen.php?data=http%3A%2F%2F192.168.0.126%2Fstampede%2Fscan%2F%3Fs%3DVnqcxRghmsCf65edtrs`;
        </script>


        <div id="outer" style="max-width: 900px; margin: auto;text-align:center">
            <div id="info"></div>
            <br><br>
            <div>Your name <br> <input type="text" id="tname" class="simp" onkeyup="sub()"></div>
            <br><br><br><br>
            Is the assest in working condition ?<br>
            <div style="padding:12px;text-align:center; margin:auto;"></div>
            <button id="yes" class="bbtn grey" onclick="set('yes')"> yes </button>
            <button id="no" class="bbtn grey" onclick="set('no')">no</button>

            <div id="camout">
                <div>
                    <div id="cam" style="display: none;">
                        <label for="files" class="btn">Select Image</label>
                        <input type="file" accept="image/*" capture="camera" name="picture" id="picture" style="opacity:0;">
                    </div>
                    <img src="clicktoscan.png" alt="" style="width:321px; cursor: pointer;" onclick="getImage()">
                    <div id="msg"></div>
                </div>

                <div id="ubtn" style="display:none;">
                    <button onclick="upload()" class="bbtn uload">upload</button>
                </div>
            </div>

            <div>Notes:</div>
            <div><textarea name="" id="notes" class="simp" onkeyup="sub()"></textarea></div>
            <button style="width:100%; padding:30px; color:white; background: #2196f3; border:0; border-radius:12px;" onclick="send()">submit report</button>

            <br><br>
            <div><img id="qr"></div>
            <div id="msg"></div>
        </div>
        </div>
        <script>
            if (localStorage.tname) {
                tname.value = localStorage.tname;
            }

            var obj = j;
            obj.timestamp = '<?php echo $timestamp ?>';

            var imgurl = `http://silvercrayon.com/apps/qrac/display.php?data=`;
            imgurl += btoa(location.href);
            gid('qr').src = imgurl;
            gid('qr').outerHTML += '<br><br>' + location.href;

            var out = '';
            for (let a in obj) {
                out += `<div><b>${a}</b>: ${obj[a]}</div>`;
            }
            gid('info').innerHTML = out;

            function set(y) {
                for (let a of document.getElementsByClassName('grey')) {
                    a.classList.remove("yes");
                    a.classList.remove("no");
                }
                if (y == 'yes') {
                    gid('yes').classList.add("yes");
                }
                if (y == 'no') {
                    gid('no').classList.add("no");
                }
                obj.status = y;
                sub();
            }

            function sub() {
                for (let a of document.getElementsByClassName('simp')) {
                    obj[a.id] = a.value.trim();
                }
                localStorage.tname = gid("tname").value.trim();
                obj.tname = localStorage.tname;
                obj.geoGPS = geoGPS;
                gid("msg").innerHTML = JSON.stringify(obj);
            }

            var p = gid('picture');

            function getImage() {
                p.click();
                p.style.opacity = 1;
            }

            function gid(id) {
                return (document.getElementById(id));
            }

            var d = new Date();
            var month = d.getMonth() + 1;
            var year = d.getFullYear();
            var day = d.getDate();
            var id = j.uid;
            var timestamp = `${year}-${month}-${day}--${id}`;
            var assetName = timestamp;
            j.photo = assetName;

            function random2(length) {
                var r = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ".split("");
                shuffle(r);
                var o = '';
                for (var i = 0; i < length; i++) {
                    o += r[i];
                }
                return (o);
            }

            function callback() {
                console.log('callback')
            }

            function shuffle(a) {
                var c = a.length,
                    t, r;
                while (0 !== c) {
                    r = Math.floor(Math.random() * c);
                    c -= 1;
                    t = a[c];
                    a[c] = a[r];
                    a[r] = t;
                }
                return a;
            }

            function convertFileToBase64(p, callback) {
                if (!p.files || p.files.length === 0) {
                    console.error("No file selected");
                    return;
                }

                const file = p.files[0];
                const reader = new FileReader();

                reader.onload = function(event) {
                    callback(event.target.result); // The base64 string
                };

                reader.onerror = function(error) {
                    console.error("Error reading file:", error);
                };

                reader.readAsDataURL(file);
            }
            p.addEventListener("change", function() {
                convertFileToBase64(this, function(base64String) {
                    window.assetImage = base64String;
                    console.log(assetImage);
                    // gid('msg').innerHTML = assetImage;
                    gid('ubtn').style.display = 'block';
                    upload();
                });
            });

            function upload() {
                gid('camout').innerHTML = '<div>Please wait, data is being written...</div>';
                const fd = new FormData();
                fd.append('assetImage', assetImage);
                fd.append('assetName', assetName);
                fd.append('uid', j.uid);

                const options = {
                    method: 'POST',
                    //mode: 'no-cors',
                    body: fd
                };
                fetch(`processImage.php`, options)
                    .then(r => {
                        return r.text()
                    })
                    .then(contents => {
                        console.log(contents);
                        gid('camout').innerHTML = `<div>Asset updated! <a href="javascript://" onclick="location.reload();">click to refresh</a></div>
                    
               
                
                <div style="padding:30px;"><img style="max-width:600px;width:100%;" src="${window.assetImage}"></div>`;
                    });
            }


            function gid(id) {
                return (document.getElementById(id));
            }

            function send() {
                const fd = new FormData();
                fd.append('assetReport', JSON.stringify(obj));
                fd.append('uid', obj.uid);
                const options = {
                    method: 'POST',
                    //mode: 'no-cors',
                    body: fd
                };
                fetch(`../sync.php`, options)
                    .then(r => {
                        return r.text()
                    })
                    .then(contents => {
                        console.log(contents);
                        gid('outer').innerHTML = '<div style="padding:20px;">Submitted</div><div><button onclick="location.reload()" class="bbtn">Reload</button></div>';
                    });
            }

            window.geoGPS = `39.3489 -76.5712`; // set default
            function showPosition(position) {
                // needs to be "https" or "file" context
                window.geolocationLong = Math.floor(position.coords.longitude * 10000) / 10000;
                window.geolocationLat = Math.floor(position.coords.latitude * 10000) / 10000;
                window.geoGPS = `${geolocationLat} ${geolocationLong}`;
                console.log(geoGPS);
            }
            navigator.geolocation.getCurrentPosition(showPosition);
        </script>
    </body>