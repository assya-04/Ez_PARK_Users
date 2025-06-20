document.addEventListener("DOMContentLoaded", function() {
    const menuButton = document.getElementById("web_menu_button");
    const menu = document.getElementById("web_menu");
    const colorButton = document.getElementById("change_color_button");
    const header = document.getElementById("header");

    menuButton.addEventListener("click", function() {
        menu.style.display = (menu.style.display === "none" || menu.style.display === "") ? "block" : "none";
    });
    function randomColor() {
        let letters = "0123456789ABCDEF";
        let color = "#";
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    colorButton.addEventListener("click", function() {
        header.style.backgroundColor = randomColor();
    });
    colorButton.addEventListener("click", function() {
        let newColor = randomColor();
        header.style.backgroundColor = newColor;
        menuButton.style.backgroundColor = newColor;
    });


});

export const toggleDeleteReservation = async (id) => {
    const response = await fetch(`index.php?component=accueilUsers&action=annuler_reservation&id=${id}`, {
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        }
    });

    const text = await response.text();
    try {
        return JSON.parse(text);
    } catch (err) {
        console.error("Réponse non JSON reçue :", text);
        throw err;
    }
}

export const toggleUpdateReservation = async (id, dateDebut, dateFin, numeroPlace) => {
    const response = await fetch(`index.php?component=accueilUsers&action=modifier_reservation&id=${id}&dateDebut=${dateDebut}&dateFin=${dateFin}&numeroPlace=${numeroPlace}`, {
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        }
    });

    const text = await response.text();
    try {
        return JSON.parse(text);
    } catch (err) {
        console.error("Réponse non JSON reçue :", text);
        throw err;
    }
}
