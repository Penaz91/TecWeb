delimiter $$
DROP PROCEDURE IF EXISTS NuovoNoleggio $$
CREATE PROCEDURE NuovoNoleggio(U varchar(30), S varchar(30), DI date, DT date, Q int)

BEGIN

declare QM int; /*QUANTITA MASSIMA DI MAGAZZINO*/
declare QN int; /*QUANTITA ATTUALMENTE NON DISPONIBILE PERCHE GIA NOLEGGIATA*/
declare Du int; /*DURATA NOLEGGIO*/

select DATEDIFF(DT, DI) into Du;

IF Du < 0
	then
	signal sqlstate '45000'
	set message_text = 'Selezionata una Data di Fine Noleggio antecedente a quella di Inizio Noleggio.';
END IF;

IF Q < 0
	then
	signal sqlstate '45000'
	set message_text = 'Selezionata una Quantita negativa.';
END IF;

select QuantitaMAX into QM
from Strumentazione
where Nome = S;

IF Q > QM
		then
		signal sqlstate '45000'
		set message_text = 'Quantita richiesta eccede quella disponibile.';
END IF;

IF exists(select sum(Quantita) from Noleggio where Strumento = S and ( (DataInizioNoleggio<=DI and DataFineNoleggio>=DI) or (DataInizioNoleggio<=DT and DataFineNoleggio>=DT) or (DataInizioNoleggio>=DI and DataFineNoleggio<=DT) ) )
	then
	select sum(Quantita) into QN
	from Noleggio
	where Strumento = S and ( (DataInizioNoleggio<=DI and DataFineNoleggio>=DI) or (DataInizioNoleggio<=DT and DataFineNoleggio>=DT) or (DataInizioNoleggio>=DI and DataFineNoleggio<=DT) );
	
	IF Q + QN > QM
		then
		signal sqlstate '45000'
		set message_text = 'Quantita richiesta eccede quella disponibile.';
	
		else /*QUANTITA DISPONIBILE SUFFICIENTE*/
		IF exists(select * from Noleggio where Cliente = U and Strumento = S and DataInizioNoleggio = DI and DataFineNoleggio = DT)
			then	
			UPDATE Noleggio SET Quantita = Quantita+Q where Cliente = U and Strumento = S and DataInizioNoleggio = DI and DataFineNoleggio = DT;
		
			else /*NUOVO NOLEGGIO*/
			INSERT Noleggio values(U, S, DI, DT, Q, Du);
		END IF;
	END IF;
	
	else /*NON ESISTE IN NOLEGGIO*/
	INSERT Noleggio values(U, S, DI, DT, Q, Du);

END IF;

END; $$

delimiter ;
