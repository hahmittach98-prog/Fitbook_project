USE fitbook;

DELIMITER //

DROP PROCEDURE IF EXISTS sp_reservations_utilisateur//
CREATE PROCEDURE sp_reservations_utilisateur(IN p_user_id INT)
BEGIN
    SELECT r.id, r.statut, r.date_reservation,
           se.date_seance, se.heure_debut, se.heure_fin,
           c.nom AS cours, sa.nom AS salle
    FROM reservation r
    JOIN seance se ON se.id = r.seance_id
    JOIN cours c ON c.id = se.cours_id
    JOIN salle sa ON sa.id = se.salle_id
    WHERE r.user_id = p_user_id
    ORDER BY se.date_seance DESC;
END//

DELIMITER ;