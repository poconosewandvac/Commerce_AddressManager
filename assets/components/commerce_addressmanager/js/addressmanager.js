(function() {
    var addresses = document.querySelectorAll(".address-open");
    var deleteButtons = document.querySelectorAll(".address-button-delete");
    var activeAddress = document.querySelector(".address-open.active");
    
    function openAddress(target) {
        var addressPane = target.nextElementSibling;
        if (addressPane.style.maxHeight) {
            addressPane.style.maxHeight = null;
        } else {
            addressPane.style.maxHeight = addressPane.scrollHeight + "px";
        }
    }
    
    // Set active address to expand and scroll to it (address with error)
    if (activeAddress) {
        openAddress(activeAddress);
        activeAddress.scrollIntoView();
    }
    
    // Add the event listener for expando
    for (var i = 0; i < addresses.length; i++) {
        addresses[i].addEventListener("click", function (e) {
            this.classList.toggle("active");
            openAddress(e.target);
        });
    }
    
    // Add delete prompt to delete buttons
    for (var i = 0; i < deleteButtons.length; i++) {
        deleteButtons[i].addEventListener("click", function (e) {
            if (!confirm("Are you sure you want to delete this address?")) e.preventDefault();
        });
    }
})();