delimiter $$
DROP PROCEDURE IF EXISTS NuovoNoleggio $$
CREATE PROCEDURE NuovoNoleggio(U varchar(30), S char(8), DI date, DT date)

BEGIN

declare d bool;
declare Du int;

select Disponibilita into d
from Strumenti
where Codice = S;

IF d = true
	then
	select DATEDIFF(DT, DI) into Du;
	insert Noleggio values (U, S, DI, DT, Du);
	update Strumenti set Disponibilita = false where Codice = S COLLATE utf8_general_ci;
	
	else
	signal sqlstate '45000'
	set message_text = 'Strumento richiesto non disponibile al momento';

END IF;

END; $$

delimiter ;
