var addresses = document.querySelectorAll(".address-open");

for (var i = 0; i < addresses.length; i++) {
    addresses[i].addEventListener("click", function () {
        this.classList.toggle("active");

        var addressPane = this.nextElementSibling;
        if (addressPane.style.maxHeight) {
            addressPane.style.maxHeight = null;
        } else {
            addressPane.style.maxHeight = addressPane.scrollHeight + "px";
        }
    });
}