/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package src;

import com.google.gson.Gson;
import java.lang.reflect.Type;
import java.util.HashMap;

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
    
    public String[] getProvince(){
        
        
        return db.getProvince();
    }
    
    public String getComuniPerProvincia(String provincia){
        
        
   String dd=db.getComuniPerProvincia(provincia);
        
        return dd;
    }
    public int inserisciAnnuncio(String descrizione, String richiedente, String creatore, int categoria) {
        int esito = -3;
        esito = db.setAnnuncio(descrizione, richiedente, creatore, categoria);
        return esito;
    }

}
