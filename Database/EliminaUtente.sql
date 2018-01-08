delimiter $$
DROP PROCEDURE IF EXISTS EliminaUtente $$
CREATE PROCEDURE EliminaUtente(U varchar(30))

BEGIN

IF exists(select * from Noleggio where Cliente=U)
	then
	update Utenti set Attivo = false where Username = U;
	
	else
	delete from Utenti where Username = U;
	
END IF;

END; $$

delimiter ;
