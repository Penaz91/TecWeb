delimiter $$
DROP TRIGGER IF EXISTS EliminaInattivo $$
CREATE TRIGGER EliminaInattivo
BEFORE DELETE ON Noleggio
FOR EACH ROW

BEGIN

declare X int;

select count(*) from Noleggio where Cliente= old.Cliente into X;

IF X = 1
	then
	delete from Utenti where Username = old.Cliente and Attivo = false;

END IF;

END; $$

DROP TRIGGER IF EXISTS ControllaInsert $$
CREATE TRIGGER ControllaInsert
BEFORE INSERT ON Sale
FOR EACH ROW

BEGIN

IF exists(select * from Sale where Name=new.Name and Function=new.Function)
	then
	signal sqlstate '45000'
	set message_text = '7';
END IF;

END; $$

DROP TRIGGER IF EXISTS ControllaUpdate $$
CREATE TRIGGER ControllaUpdate
BEFORE UPDATE ON Sale
FOR EACH ROW

BEGIN

IF exists(select * from Sale where Name=new.Name and Function=new.Function)
	then
	signal sqlstate '45000'
	set message_text = '7';
END IF;

END; $$

delimiter ;

