delimiter $$
DROP FUNCTION IF EXISTS VerificaDisponibilita $$
CREATE FUNCTION VerificaDisponibilita(S varchar(30), DI date, DT date)
RETURNS INTEGER DETERMINISTIC

BEGIN

declare QM int; /*QUANTITA MASSIMA DI MAGAZZINO*/
declare QN int; /*QUANTITA ATTUALMENTE NON DISPONIBILE PERCHE GIA NOLEGGIATA*/

select QuantitaMAX into QM
from Strumentazione
where Nome = S;

IF exists(select* from Noleggio where Strumento = S and ( (DataInizioNoleggio<=DI and DataFineNoleggio>=DI) or (DataInizioNoleggio<=DT and DataFineNoleggio>=DT) or (DataInizioNoleggio>=DI and DataFineNoleggio<=DT) ) )
	then
	select sum(Quantita) into QN
	from Noleggio
	where Strumento = S and ( (DataInizioNoleggio<=DI and DataFineNoleggio>=DI) or (DataInizioNoleggio<=DT and DataFineNoleggio>=DT) or (DataInizioNoleggio>=DI and DataFineNoleggio<=DT) );
	RETURN QM-QN;
	
	else /*NON C'E NOLEGGIO*/
	RETURN QM;

END IF;

END; $$

delimiter ;
