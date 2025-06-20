import { toggleDeleteReservation } from "../Services/accueilUsers.js";
import { toggleUpdateReservation } from "../Services/accueilUsers.js";
export const handleDeleteReservation = () => {
    const deleteReservations = document.querySelectorAll(".delete-link");

    deleteReservations.forEach(deleteReservation => {
        if (deleteReservation.dataset.listenerAttached === "true") return;

        deleteReservation.addEventListener("click", async (e) => {
            e.preventDefault();

            const reservationId = deleteReservation.getAttribute("data-id");
            if (!reservationId) return;

            const result = await toggleDeleteReservation(reservationId);

            if (result.hasOwnProperty("success")) {
                const reservationRow = document.querySelector(`tr[data-reservation-id="${reservationId}"]`);

                if (reservationRow) {
                    // Récupère les infos de la réservation
                    const dateDebut = reservationRow.children[0].textContent;
                    const dateFin = reservationRow.children[1].textContent;
                    const numeroPlace = reservationRow.children[2].textContent;

                    // Supprime la ligne de la table actuelle
                    reservationRow.remove();

                    // Crée une nouvelle ligne pour l'historique
                    const historiqueTableBody = document.querySelector("#historique table tbody");
                    const newRow = document.createElement("tr");

                    newRow.innerHTML = `
                        <td>${dateDebut}</td>
                        <td>${dateFin}</td>
                        <td>${numeroPlace}</td>
                        <td>Annulée</td>
                    `;

                    historiqueTableBody.appendChild(newRow);
                }
            } else {
                console.error("Erreur lors de l'annulation :", result.error);
            }
        });

        deleteReservation.dataset.listenerAttached = "true";
    });
};

export const handleUpdateReservation = () => {
    const updateReservations = document.querySelectorAll(".update-link");

    updateReservations.forEach(updateReservation => {
        if (updateReservation.dataset.listenerAttached === "true") return;

        updateReservation.addEventListener("click", async (e) => {
            e.preventDefault();

            const reservationId = updateReservation.getAttribute("data-id");
            if (!reservationId) return;

            const dateDebut = prompt("Entrez la nouvelle date de début (format YYYY-MM-DD HH:MM:SS) :");
            const dateFin = prompt("Entrez la nouvelle date de fin (format YYYY-MM-DD HH:MM:SS) :");
            const numeroPlace = prompt("Entrez le nouveau numéro de place :");

            if (!dateDebut || !dateFin || !numeroPlace) return;

            const result = await toggleUpdateReservation(reservationId, dateDebut, dateFin, numeroPlace);

            if (result.hasOwnProperty("success")) {
                const reservationRow = document.querySelector(`tr[data-reservation-id="${reservationId}"]`);

                if (reservationRow) {
                    // Met à jour les infos de la réservation
                    reservationRow.children[0].textContent = dateDebut;
                    reservationRow.children[1].textContent = dateFin;
                    reservationRow.children[2].textContent = numeroPlace;
                }
            } else {
                console.error("Erreur lors de la mise à jour :", result.error);
            }
        });

        updateReservation.dataset.listenerAttached = "true";
    });
}
