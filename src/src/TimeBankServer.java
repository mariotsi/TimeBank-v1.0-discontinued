/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package src;

import com.google.gson.Gson;

/**
 *
 * @author Simone
 */
public class TimeBankServer {

    DatabaseHandler db = new DatabaseHandler();

    public int inserisciUtente(String username, String password, String email, String indirizzo, String cap, String citta, String provincia) {
        return db.inserisciUtente(username, password, email, indirizzo, cap, citta, provincia);
    }

    public String[] getProvince() {
        return db.getProvince();
    }

    public String getCategorie() {
        return db.getCategorie();
    }

    public String getComuniPerProvincia(String provincia) {
        return db.getComuniPerProvincia(provincia);
    }

    public int inserisciAnnuncio(String dataAnnuncioFromClient, String descrizione, String creatore, int categoria) {
        return db.inserisciAnnuncio(dataAnnuncioFromClient, descrizione, creatore, categoria);
    }

    public int loginUtente(String username, String password) {
        return db.loginUtente(username, password);
    }

    public String getAnnuncio(int id_annuncio) {
        return db.getAnnuncio(id_annuncio);
    }

    public int richiediAnnuncio(int id_annuncio, String creatore, String richiedente) {
        return db.richiediAnnuncio(id_annuncio, creatore, richiedente);
    }
}
