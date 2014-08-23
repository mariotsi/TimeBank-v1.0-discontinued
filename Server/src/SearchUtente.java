/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package src;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author Simone
 */
public class SearchUtente {

    
    private String username;//1
    private String email;//3
    private String indirizzo;//8
    private String cap;
    private String citta;
    private String provincia;
    private boolean admin;
    private DatabaseHandler db;
    private final Connection conn;
    private PreparedStatement pstm;
    private ResultSet risultatoQuery;
    private int codiceErrore=0;
    public SearchUtente(Connection conn) {
        this.conn = conn;
    }

    public Utente byId(String username) {
        this.username = username;
        try {
            pstm = conn.prepareStatement("SELECT * FROM utente WHERE username=?", ResultSet.TYPE_SCROLL_SENSITIVE, ResultSet.CONCUR_READ_ONLY);
            pstm.setString(1, username);
            risultatoQuery = pstm.executeQuery();
            risultatoQuery.last();
            codiceErrore=risultatoQuery.getRow()==0?-2:0;
            risultatoQuery.beforeFirst();
            while (risultatoQuery.next()) {
                email = risultatoQuery.getString(3);
                indirizzo = risultatoQuery.getString(8);
                cap = risultatoQuery.getString(9);
                citta = risultatoQuery.getString(10);
                provincia = risultatoQuery.getString(11);
                admin = risultatoQuery.getBoolean(12);
            }
            risultatoQuery.close();
            pstm.close();
        } catch (SQLException ex) {
            Logger.getLogger(SearchAnnuncio.class.getName()).log(Level.SEVERE, null, ex);
            codiceErrore = -1; //-1 errore SQL, -2 Utente non trovato, 0 OK
        }
        return new Utente(username, email, indirizzo, cap, citta, provincia, admin, codiceErrore);
    }
}
