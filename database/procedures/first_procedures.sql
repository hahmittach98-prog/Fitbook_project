USE fitbook;

DELIMITER //

-- 1) Nombre de places restantes pour une séance

DROP PROCEDURE IF EXISTS sp_places_restantes//
CREATE PROCEDURE sp_places_restantes(IN p_seance_id INT)
BEGIN
    SELECT s.id,
           s.capacite_max,
           COUNT(r.id) AS reservations_confirmees,
           s.capacite_max - COUNT(r.id) AS places_restantes
    FROM seance s
    LEFT JOIN reservation r ON r.seance_id = s.id AND r.statut = 'confirmee'
    WHERE s.id = p_seance_id
    GROUP BY s.id, s.capacite_max;
END//





DELIMITER ;