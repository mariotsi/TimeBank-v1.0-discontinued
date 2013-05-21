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
import java.sql.Timestamp;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author Simone
 */
public class SearchAnnuncio {

    private int id_annuncio;
    private Timestamp data_inserimento;
    private Timestamp data_annuncio;
    private boolean richiesto;
    private String descrizione;
    private String richiedente;
    private String creatore;
    private int categoria;
    private DatabaseHandler db;
    private final Connection conn;
    private PreparedStatement pstm;
    private ResultSet risultatoQuery;
    private int codiceErrore=0;

    public SearchAnnuncio(Connection conn) {
        this.conn = conn;
       
      

    }
    public Annuncio byId(int id_annuncio){
      this.id_annuncio=id_annuncio;
        try {
            pstm = conn.prepareStatement("SELECT * FROM annuncio WHERE id_annuncio=?", ResultSet.TYPE_SCROLL_SENSITIVE, ResultSet.CONCUR_READ_ONLY);
            pstm.setInt(1, id_annuncio);
            risultatoQuery=pstm.executeQuery();
            risultatoQuery.next();
            data_inserimento=risultatoQuery.getTimestamp(2);
            data_annuncio=risultatoQuery.getTimestamp(3);
            richiesto=risultatoQuery.getBoolean(4);
            descrizione=risultatoQuery.getString(5);
            richiedente=risultatoQuery.getString(6);
            creatore=risultatoQuery.getString(7);
            categoria=risultatoQuery.getInt(8);
            risultatoQuery.close();
            pstm.close();
                    
        } catch (SQLException ex) {
            Logger.getLogger(SearchAnnuncio.class.getName()).log(Level.SEVERE, null, ex);
            codiceErrore=-1;
        }
    return new Annuncio(id_annuncio, data_inserimento, data_annuncio, richiesto, descrizione, richiedente, creatore, categoria, codiceErrore);
    }
}
