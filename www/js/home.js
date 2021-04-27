var container = document.getElementById("container");

function getImages() {
  var urlParams = new URLSearchParams(window.location.search);
  var page = urlParams.get("page") || 0;

  let url = `/home.php?page=${page}`;

  fetch(url, {
    method: "GET",
  })
    .then(function (res) {
      console.log(res);
      return res.json();
    })
    .then(function (data) {
      container.innerHTML = "";
      data.forEach((image) => {
        console.log(image.image);
        var div = document.createElement("div");
        var img = document.createElement("img");
        var body = document.createElement("div");
        var like = document.createElement("button");
        var comment = document.createElement("button");
        var input = document.createElement("input");
        var label = document.createElement("label");
        var div1 = document.createElement("div");
        var div2 = document.createElement("div");
        var form1 = document.createElement("form");
        var form2 = document.createElement("form");
        var photoIdInput = document.createElement("input");
        var show = document.createElement("button");
        var etiquette = document.createElement("div");
        var deletePost = document.createElement("button");
        var topDiv = document.createElement("div");

        img.src = image.image;
        body.style.height = "50%";
        div.style.width = "50%"; 
        like.className = "bi bi-heart btn btn-danger";
        like.name = "like";
        like.disabled = image.likesCount >= 1;
        comment.className = "bi bi-chat-left-text-fill btn btn-dark comment-btn";
        comment.name = "comment";
        input.className = "comment-input";
        input.name = "comment-input";
        img.className = "card-img-top";
        div.className = "card card-2";
        body.className = "card-body";
        label.className = "like-label";
        show.className = "show btn btn-dark comment-btn";
        show.id = image.id;
        show.innerHTML = "show comments";
        etiquette.className = "etiquette";
        etiquette.innerHTML = `${image.pseudo} a publié le ${image.date}`;
        label.name = "like-label";
        form1.method = "post";
        form1.action = "index.php";
        form2.method = "post";
        form2.action = "index.php";
        photoIdInput.name = "photo_id";
        photoIdInput.className = "photo_id";
        
        photoIdInput.value = image.id;
        photoIdInput.type = "hidden";
        label.innerHTML = image.likes || 0;
        deletePost.className = "btn btn-dark bi bi-trash";
        topDiv.className = "topDiv d-flex justify-content-between";
        topDiv.appendChild(etiquette);
        if (image.is_deletable == true) {
          topDiv.appendChild(deletePost);
        }
        div1.appendChild(label);
        div1.appendChild(like);
        div2.appendChild(input);
        div2.appendChild(comment);
        form1.appendChild(div1);
        form2.appendChild(div2);
        form2.appendChild(photoIdInput.cloneNode());
        form1.appendChild(photoIdInput.cloneNode());
        body.appendChild(form1);
        body.appendChild(form2);

        div.appendChild(topDiv);
        div.appendChild(img);
        div.appendChild(body);
        div.appendChild(show);
        div.appendChild(photoIdInput.cloneNode());
        container.appendChild(div);
        if (image.is_connected == false) {
          like.disabled = true;
          comment.disabled = true;
          input.disabled = true;
        }
        show.addEventListener("click", function () {
          fetch(`getComment.php?photoId=${image.id}`, {
            method: "GET",
          })
            .then((res) => res.json())
            .then(function (data) {
              console.log(data);

              data.forEach(function (elem) {
                var commentaire = document.createElement("div");
                commentaire.innerHTML = elem.comment;
                commentaire.className = "commentaire";
                div.appendChild(commentaire);
                show.disabled = true;
              });
            });
        });
        deletePost.addEventListener("click", function () {
          if (confirm("Voulez vous vraiment supprimer ? ") == true) {
            fetch("/delete_image.php", {
              method: "POST",
              headers: {
                "Content-Type": "application/x-www-form-urlencoded",
              },
              body: "photo_id=" + image.id,
            }).then((res) => {
              if (res.ok && res.status == 200) div.remove();
            });
          }
        });
      });
    });
}

getImages();
