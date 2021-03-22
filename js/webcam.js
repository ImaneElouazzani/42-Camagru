//access camera
var res = document.getElementById("result");
var canvas = document.getElementById("canvas");
var context = canvas.getContext("2d");

if (hasGetUserMedia()) {
  const constraints = { video: true };
  navigator.mediaDevices.getUserMedia(constraints).then((stream) => {
    video.srcObject = stream;
  });
  //take image
  var width;
  var height;

  let video = document.getElementById("video");
  let canvas = document.getElementById("canvas");
  let photo = document.getElementById("photo");

  video.addEventListener("loadedmetadata", function () {
    height = video.videoHeight;
    width = video.videoWidth;
  });

  document.getElementById("snap").addEventListener("click", function () {
    canvas.width = width;
    canvas.height = height;
    context.drawImage(video, 0, 0, width, height);
    document.getElementById("save").disabled = false;
  });
} else {
  let videoTag = document.getElementById("video");
  let snap = document.getElementById("snap");
  let uploadInput = document.getElementById("fileInput");

  // Remove video and button.
  videoTag.remove();
  snap.remove();

  document.getElementById("fileInput").disabled = false;
  document.getElementById("upload").disabled = false;

  // Create new image object.
  var img = new Image();

  // Create new fileReader.
  var fileReader = new FileReader();

  canvas.hidden = "true";
  upload.addEventListener("click", function () {
    fileReader.onload = function () {
      img.src = fileReader.result;

      // image style.
      img.style.maxWidth = "600px";
      img.style.maxHeight = "600px";
      res.prepend(img);
      img.onload = function () {
        canvas.width = img.width;
        canvas.height = img.height;
        context.drawImage(img, 0, 0, img.width, img.height);
        document.getElementById("save").disabled = false;
      };
    };
    fileReader.readAsDataURL(uploadInput.files[0]);
    //document.getElementById("save").disabled = false;
  });
}

//save image
document.getElementById("save").addEventListener("click", function (e) {
  var dataUrl = canvas.toDataURL("image/jpeg");
  document.getElementById("save").disabled = true;
  var sticker = document.getElementById("sticker");
  imageData = "pic=" + encodeURIComponent(dataUrl);
  stickerData = "sticker=" + sticker.name;
  var data = imageData + "&" + stickerData;
  var url = "save.php";
  if (imageData) {
    fetch(url, {
      method: "POST",
      headers: {
        "Content-type": "application/x-www-form-urlencoded",
      },
      body: data,
    }).catch(function (err) {
      console.error(err);
    });
  }
});

function hasGetUserMedia() {
  return !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
}
