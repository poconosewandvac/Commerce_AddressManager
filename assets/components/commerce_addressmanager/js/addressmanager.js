var addresses = document.querySelectorAll(".address-open");
var deleteButtons = document.querySelectorAll(".address-button-delete");

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

for (var i = 0; i < deleteButtons.length; i++) {
    deleteButtons[i].addEventListener("click", function (e) {
        if (!confirm("Are you sure you want to delete this address?")) e.preventDefault();
    });
}