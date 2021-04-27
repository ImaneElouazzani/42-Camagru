function hasGetUserMedia() {
  return !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
}

function removeLastStickers(containerId) {
  var videoDiv = document.getElementById(containerId);
  var stickers = videoDiv.querySelectorAll(":scope > img");

  stickers.forEach((img) => img.remove());
}

var res = document.getElementById("result");
var stickDiv = document.getElementById("stickers");
var stickers = stickDiv.querySelectorAll("img");

if (hasGetUserMedia()) {
  stickers.forEach(function addListener(sticker) {
    sticker.addEventListener("click", function () {
      removeLastStickers("liveVideo");
      var videoDiv = document.getElementById("liveVideo");
      var img = document.createElement("img");
      img.setAttribute("src", sticker.src);
      img.setAttribute("id", "sticker");
      img.setAttribute("name", sticker.name);
      img.style.position = "absolute";
      img.style.left = "50%";
      img.style.top = "10px";
      img.style.transform = "translateX(-50%)";
      videoDiv.appendChild(img);
      document.getElementById("snap").disabled = false;
    });
  });
} else {
  stickers.forEach(function addListener(sticker) {
    sticker.addEventListener("click", function () {
      // Create img tag and set proper attributes.
      var stickerContainer = document.getElementById("sticker-container");
      var img = document.createElement("img");
      img.setAttribute("src", sticker.src);
      img.setAttribute("id", "sticker");
      img.setAttribute("name", sticker.name);
      // Set img position.
      stickerContainer.style.display = "inline-block";
      stickerContainer.style.position = "absolute";
      stickerContainer.style.left = "50%";
      stickerContainer.style.top = "20px";
      stickerContainer.style.transform = "translateX(-50%)";

      removeLastStickers("sticker-container");
      stickerContainer.appendChild(img);
    });
  });
}
