delimiter $$
DROP TRIGGER IF EXISTS EliminaInattivo $$
CREATE TRIGGER EliminaInattivo
BEFORE DELETE ON Noleggio
FOR EACH ROW

BEGIN

declare X int;

update Strumenti set Disponibilita = true where Codice= old.Strumento COLLATE utf8_general_ci;

select count(*) from Noleggio where Cliente= old.Cliente COLLATE utf8_general_ci into X;

IF X = 1
	then
	delete from Utenti where Username = old.Cliente COLLATE utf8_general_ci and Attivo = false;

END IF;

END; $$

delimiter ;

