delimiter $$
DROP TRIGGER IF EXISTS EliminaInattivo $$
CREATE TRIGGER EliminaInattivo
BEFORE DELETE ON Noleggio
FOR EACH ROW

BEGIN

declare X int;

update Strumenti set Disponibilita = true where Codice= old.Strumento;

select count(*) from Noleggio where Cliente= old.Cliente into X;

IF X = 1
	then
	delete from Utenti where Username = old.Cliente and Attivo = false;

END IF;

END; $$

delimiter ;

