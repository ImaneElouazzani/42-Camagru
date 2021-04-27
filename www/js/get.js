var container = document.getElementById('img-container');

let url = "get.php";
fetch(
  url,
  {
    method: "GET"
  }
)
.then(function(res) {
    return res.text();
})
.then(function(data) {
    var tab = data.split("\n");
    tab.forEach(function(elem) {
        var img = document.createElement('img');
        img.src = elem;
        console.log(elem);
        // img.style.width = 400;
        // img.style.height = 400;
        img.style.width = "30%";
        container.appendChild(img);
    });
    /*var img = document.createElement('img');
    img.src = data;
    container.appendChild(img);*/
})

/*

div id container
    img
    img
/div

*/