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

    public String cercaAnnunci(String creatore, String provincia, String comune, int categoria, boolean all) {
        return db.cercaAnnunci(creatore, provincia, comune, categoria, all);
    }

    public boolean eliminaCategoria(int id_categoria) {
        return db.eliminaCategoria(id_categoria);
    }

    public boolean modificaCategoria(int id_categoria, String nuovoNome) {
        return db.modificaCategoria(id_categoria, nuovoNome);
    }
    
    public boolean isAdmin(String username){
        return db.isAdmin(username);
    }
    
    public String getUtenti(){
        return db.getUtenti();
    }
    
    public String getUtente(String username){
        return db.getUtente(username);
    }
    
    public boolean eliminaUtente(String username){
        return db.eliminaUtente(username);
    }
    public int modificaUtente(String username, String password, String email, String indirizzo, String cap, String citta, String provincia, boolean admin, String oldUsername) {
        return db.modificaUtente(username, password, email, indirizzo, cap, citta, provincia, admin, oldUsername);
    }
}
