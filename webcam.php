<?php
    session_start();
    require '/var/www/html/inc/header.php';
?>

<div id="stickers">
  <img name="duck.png" src="assets/duck.png">
  <img name="heart.png" src="assets/heart.png">
  <img name="smiley.png" src="assets/smiley.png">
</div>

<div id="content" class="d-flex flex-column">
  <div class="d-flex justify-content-around">
    <div id="liveVideo"  class="card" >
          <video id="video" class="card-img-top" autoplay ></video>
          <button class="btn btn-dark" id="snap" disabled>Prendre une photo</button>
          <input id="fileInput" type="file" disabled />
          <button id="upload" disabled>Upload</button>
    </div>
  <div  class="card" style="width:50%">
    <div id="img-container" style="flex-wrap: wrap;" class="d-flex justify-content-around"></div>
  </div>
</div>
<div style="position: relative;" class="card" id="result">
    <div id="sticker-container">
    </div>
    <canvas id="canvas" class="card-img-top"></canvas>
    <button class="btn btn-dark" id="save" disabled>Sauvegarder</button>
</div>
<script src="js/stickers.js"></script>
<script src="js/webcam.js"></script>
<script src="js/get.js"></script>
<?php
    require '/var/www/html/inc/footer.php'
?>