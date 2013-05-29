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
    private String data_inserimento;
    private String data_annuncio;
    private boolean richiesto;
    private String descrizione;
    private String richiedente;
    private String creatore;
    private int id_categoria;
    private String nome_cat;
    private int codiceErrore = -2;
    private String nomeComune;
    private String provincia;
    private DatabaseHandler db;
    private final Connection conn;
    private PreparedStatement pstm;
    private ResultSet risultatoQuery;

    public SearchAnnuncio(Connection conn) {
        this.conn = conn;
    }

    public Annuncio byId(int id_annuncio) {
        this.id_annuncio = id_annuncio;
        try {
            pstm = conn.prepareStatement("SELECT * FROM annuncio,categoria,utente,comune WHERE id_annuncio=? AND categoria=id_categoria AND creatore=username AND citta=codice_istat", ResultSet.TYPE_SCROLL_SENSITIVE, ResultSet.CONCUR_READ_ONLY);
            pstm.setInt(1, id_annuncio);
            risultatoQuery = pstm.executeQuery();
            while (risultatoQuery.next()) {
                data_inserimento = risultatoQuery.getString(2);
                data_annuncio = risultatoQuery.getString(3);
                richiesto = risultatoQuery.getBoolean(4);
                descrizione = risultatoQuery.getString(5);
                richiedente = risultatoQuery.getString(6);
                creatore = risultatoQuery.getString(7);
                id_categoria = risultatoQuery.getInt(8);
                nome_cat = risultatoQuery.getString(10);
                provincia = risultatoQuery.getString(21);
                nomeComune = risultatoQuery.getString(24);
                codiceErrore = 0;
            }
            risultatoQuery.close();
            pstm.close();
        } catch (SQLException ex) {
            Logger.getLogger(SearchAnnuncio.class.getName()).log(Level.SEVERE, null, ex);
            codiceErrore = -1; //-1 errore SQL, -2 Annuncio non trovato, 0 OK
        }
        return new Annuncio(id_annuncio, data_inserimento, data_annuncio, richiesto, descrizione, richiedente, creatore, id_categoria, nome_cat, codiceErrore, provincia, nomeComune);
    }
}
