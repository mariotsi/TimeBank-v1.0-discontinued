/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package src;

/**
 *
 * @author Simone
 */
public class TimeBankServer {

    DatabaseHandler db = new DatabaseHandler();

    public int inserisciUtente(String username, String password, String email, String indirizzo, String cap, String citta, String provincia) {
        int esito = -3;
        esito = db.creaUtente(username, password, email, indirizzo, cap, citta, provincia);

        return esito;
    }
}
