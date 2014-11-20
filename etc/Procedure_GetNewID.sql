DROP PROCEDURE IF EXISTS getNewID;
DELIMITER //
CREATE PROCEDURE getNewID(
	IN  iTable VARCHAR(25),
	IN  iGUID CHAR(36),
	OUT oNewID INT UNSIGNED,
	OUT oResult TINYINT(2)
)
BEGIN
	
	-- DECLARE mIsReserved BIT(1) DEFAULT 0;
	DECLARE mTableExists INTEGER UNSIGNED;
	
	SELECT value INTO mTableExists
	FROM sequence
	WHERE tablename = iTable
	LIMIT 1;
	
	-- IF mTableExists IS NULL THEN
	-- 	CALL `generateNewSequence`(iTable, 10, @p0);
	-- END IF;
		
	WHILE oNewID IS NULL DO
		
		UPDATE sequence
		SET
			reserved = iGUID,
			test = test + 1
		WHERE
			    tablename = iTable
			AND reserved IS NULL
		LIMIT 1;
		
		SELECT
			`value` INTO oNewID
		FROM sequence
		WHERE
			reserved = iGUID;
		
	END WHILE;
		
	-- CALL `generateNewSequence`(iTable, 2, @p0);
	
END //
DELIMITER ;