document.addEventListener('DOMContentLoaded', function () {

    // Get the modal
    var modal = document.getElementById('myModal');

    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var img = document.querySelector('.thumbnail');
    var modalImg = document.getElementById("img01");
    var downloadLink = document.getElementById("downloadLink");

    img.onclick = function () {
        modal.style.display = "block";
        modalImg.src = this.src;
        downloadLink.href = this.src;
    };

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        modal.style.display = "none";
    };

    // Close the modal when the user presses the Escape key
    document.addEventListener('keydown', function (event) {
        if (event.key === "Escape") {
            modal.style.display = "none";
        }
    });

});
