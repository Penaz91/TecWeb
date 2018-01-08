delimiter $$
DROP PROCEDURE IF EXISTS NuovaPrenotazione $$
CREATE PROCEDURE NuovaPrenotazione(U varchar(30), S varchar(30), F varchar(30), D date, O time, Du smallint)

BEGIN

declare H int;
declare aux1 time;
declare prima int;
declare durata int;
declare aux2 time;
declare dopo int;

SELECT HOUR(O) into H;

IF H + Du >24
	then
	signal sqlstate '45000'
	set message_text = 'Prenotazione oltre orario di chiusura';
END IF;

IF H < 12
	then
	signal sqlstate '45000'
	set message_text = 'Prenotazione prima del orario di apertura';
END IF;

IF exists(select * from Prenotazioni where SalaPrenotata=S and DataPrenotazione=D and OrarioPrenotazione<=O)
	then
	select OrarioPrenotazione, DurataPrenotazione into aux1, durata
	from Prenotazioni
	where DataPrenotazione = D and SalaPrenotata=S and OrarioPrenotazione < O
	having OrarioPrenotazione >= all (select OrarioPrenotazione
																		from Prenotazioni
																		where DataPrenotazione = D and SalaPrenotata = S and OrarioPrenotazione < O);
																		
	select HOUR(aux1) into prima;

	IF H - prima - durata >=0
		then
		IF exists(select * from Prenotazioni where SalaPrenotata=S and DataPrenotazione=D and OrarioPrenotazione>=O)
			then
			select OrarioPrenotazione into aux2
			from Prenotazioni
			where DataPrenotazione = D and SalaPrenotata=S and OrarioPrenotazione >= O
			having OrarioPrenotazione <= all (select OrarioPrenotazione
																		from Prenotazioni
																		where DataPrenotazione = D and SalaPrenotata = S and OrarioPrenotazione >= O);
																					
			select HOUR(aux2) into dopo;
			
			IF H + Du <= dopo
				then
				insert Prenotazioni values (U, S, F, D, O, Du);
				
				else /*RICHIESTA SI ACCAVALLA CON PRENOTAZIONE SUCCESSIVA*/
				signal sqlstate '45000'
				set message_text = 'Sala richiesta Occupata da una prenotazione successiva, selezionare durata minore se si vuole mantenere lo stesso orario';
			END IF;
			
			else /*NON ESISTE PRENOTAZIONE SUCCESSIVA & PRECEDENTE NON CREA PROBLEMI*/
			insert Prenotazioni values (U, S, F, D, O, Du);
		END IF;
	
		else
		signal sqlstate '45000'
		set message_text = 'Sala richiesta ancora occupata da una prenotazione precedente. Selezionare un orario diverso';
	END IF;
		
	else /*NON ESISTE PRENOTAZIONE PRECEDENTE*/
		IF exists(select * from Prenotazioni where SalaPrenotata=S and DataPrenotazione=D and OrarioPrenotazione>=O)
			then
			select OrarioPrenotazione into aux2
			from Prenotazioni
			where DataPrenotazione = D and SalaPrenotata=S and OrarioPrenotazione >= O
			having OrarioPrenotazione <= all (select OrarioPrenotazione
																	from Prenotazioni
																	where DataPrenotazione = D and SalaPrenotata = S and OrarioPrenotazione >= O);
																					
			select HOUR(aux2) into dopo;
			
			IF H + Du <= dopo
				then
				insert Prenotazioni values (U, S, F, D, O, Du);
				
				else /*RICHIESTA SI ACCAVALLA CON PRENOTAZIONE SUCCESSIVA*/
				signal sqlstate '45000'
				set message_text = 'Sala richiesta Occupata da una prenotazione successiva, selezionare durata minore se si vuole mantenere lo stesso orario';
			END IF;
			
			else /*NON ESISTE PRENOTAZIONE SUCCESSIVA*/
			insert Prenotazioni values (U, S, F, D, O, Du);
		END IF;
	
END IF;	

END; $$

delimiter ;
