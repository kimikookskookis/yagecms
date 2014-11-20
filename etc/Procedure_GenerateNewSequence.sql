DROP PROCEDURE IF EXISTS generateNewSequence;
DELIMITER //
CREATE PROCEDURE generateNewSequence(
	IN  iTable VARCHAR(25),
	IN  iNumber SMALLINT UNSIGNED,
	OUT oResult TINYINT(2)
)
BEGIN
	
	DECLARE mMaxID INT UNSIGNED;
	DECLARE mIteration SMALLINT UNSIGNED DEFAULT 0;
	
	SELECT MAX(value)+1 INTO mMaxID
	FROM sequence
	WHERE tablename = iTable;
	
	IF mMaxID IS NULL THEN
		SET mMaxID = 1;
	END IF;
	
	WHILE mIteration < iNumber DO
		INSERT INTO sequence (`tablename`, `value`)
		VALUES (iTable, mMaxID);
		
		SET mMaxID = mMaxID + 1;
		SET mIteration = mIteration + 1;
	END WHILE;
	
END //
DELIMITER ;