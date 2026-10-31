
USE fitbook;

DELIMITER //



-- 2) Note moyenne et nombre d'avis d'une salle
DROP PROCEDURE IF EXISTS sp_note_moyenne_salle//
CREATE PROCEDURE sp_note_moyenne_salle(IN p_salle_id INT)
BEGIN
    SELECT p_salle_id AS salle_id,
           COALESCE(ROUND(AVG(note), 2), 0) AS note_moyenne,
           COUNT(*) AS nombre_avis
    FROM avis
    WHERE salle_id = p_salle_id AND statut_moderation = 'valide';
END//


DELIMITER ;