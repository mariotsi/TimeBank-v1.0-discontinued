/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package src;

import java.sql.*;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author Simone
 */
public class DatabaseHandler {

    static final String URL = "jdbc:postgresql://localhost:5432/TimeBank";
    static final String USER = "Simone";
    static final String PSW = "chiara";
    private Connection conn = null;
    private String driver = "org.postgresql.Driver";
    private Statement stm = null;
    private PreparedStatement pstm = null;
    private ResultSet risultatoQuery = null;
    private int esito = 0;

    public DatabaseHandler() {
        try {
            Class.forName(driver); //Carica il driver JDBC
            //connessione al DB
            conn = DriverManager.getConnection(URL, USER, PSW);
            stm = conn.createStatement();

            stm.executeUpdate("SET SEARCH_PATH TO TimeBank;");
            stm.close();
        } catch (SQLException e) {
            e.printStackTrace();
        } catch (ClassNotFoundException ex) {
            ex.printStackTrace();
        }

    }

   
    public int creaUtente(String username, String password, String email, String indirizzo, String cap, String citta, String provincia) {
        try {
            pstm = conn.prepareStatement("SELECT * FROM utente WHERE username=?", ResultSet.TYPE_SCROLL_SENSITIVE, ResultSet.CONCUR_READ_ONLY);
            pstm.setString(1, username);
            risultatoQuery = pstm.executeQuery();
            risultatoQuery.last();
            if (risultatoQuery.getRow() == 0) //L'utente non esiste, inseriscilo
            {
                pstm.close();
                risultatoQuery.close();
                pstm = conn.prepareStatement("INSERT INTO utente VALUES (?,?,?,0,0,0,0,?,?,?,?);", ResultSet.TYPE_SCROLL_SENSITIVE, ResultSet.CONCUR_READ_ONLY);
                pstm.setString(1, username);
                pstm.setString(2, password);
                pstm.setString(3, email);
                pstm.setString(4, indirizzo);
                pstm.setString(5, cap);
                pstm.setString(6, citta); //Codice ISTAT
                pstm.setString(7, provincia);
                esito = pstm.executeUpdate();
            } else {
            
            System.err.println("Utente già esistente");
            esito=-1;
            }
        } catch (SQLException ex) {
            Logger.getLogger(DatabaseHandler.class.getName()).log(Level.SEVERE, null, ex);
            esito=-2;
            System.err.println("Errore generico");
        }
        return esito;
    }
}
