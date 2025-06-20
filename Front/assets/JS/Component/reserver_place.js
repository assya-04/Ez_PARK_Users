
document.getElementById("type_place").addEventListener("change", function () {
    const typeSelectionne = this.value;
    const options = document.querySelectorAll("#place_id option");

    options.forEach(option => {
        if (option.value) {
            option.style.display = option.textContent.includes(typeSelectionne) ? "block" : "none";
        }
    });
});

