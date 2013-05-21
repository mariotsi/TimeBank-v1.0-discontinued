/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package src;


import com.google.gson.Gson;
import java.sql.*;
import java.text.DateFormat;
import java.util.ArrayList;
import java.util.logging.Level;
import java.util.logging.Logger;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;

/**
 *
 * @author Simone
 */
public class DatabaseHandler {

    static final String URL = "jdbc:postgresql://localhost/TimeBank";
    static final String USER = "postgres";
    static final String PSW = "chiara";
    private Connection conn = null;
    private final String driver = "org.postgresql.Driver";
    private Statement stm = null;
    private PreparedStatement pstm = null;
    private ResultSet risultatoQuery = null;
    private int esito = 0;
    private final int NUMERO_MAX_COMUNI_PER_PROVINCIA = 350;

    public DatabaseHandler() {
        try {
            Class.forName(driver); //Carica il driver JDBC
            //connessione al DB
            conn = DriverManager.getConnection(URL, USER, PSW);
            stm = conn.createStatement();

            stm.executeUpdate("SET SEARCH_PATH TO TimeBank;");
            stm.close();
        } catch (SQLException | ClassNotFoundException e) {
            System.err.println("errore generico: " + e);
        }

    }

    public int inserisciUtente(String username, String password, String email, String indirizzo, String cap, String citta, String provincia) {
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
                pstm.setString(2, BCrypt.hashpw(password, BCrypt.gensalt()));
                pstm.setString(3, email);
                pstm.setString(4, indirizzo);
                pstm.setString(5, cap);
                pstm.setString(6, citta); //Codice ISTAT
                pstm.setString(7, provincia);
                esito = pstm.executeUpdate();
                pstm.close();
                risultatoQuery.close();
            } else {
                System.err.println("Utente già esistente");
                esito = -1;
            }
        } catch (SQLException ex) {
            //Logger.getLogger(DatabaseHandler.class.getName()).log(Level.SEVERE, null, ex);
            esito = -2;
            System.err.println("Errore generico: " + ex);

        }
        return esito;
    }

    public String[] getProvince() {
        ArrayList<String> result = new ArrayList<>(110);
        ResultSet rs;
        try {
            stm = conn.createStatement();
            rs = stm.executeQuery("SELECT DISTINCT provincia FROM comune ORDER BY provincia ASC;");
            while (rs.next()) {
                result.add(rs.getString("provincia"));
            }
            stm.close();
            rs.close();
            //Collections.sort(result);
        } catch (SQLException ex) {
            Logger.getLogger(DatabaseHandler.class.getName()).log(Level.SEVERE, null, ex);
        }

        return result.toArray(new String[result.size()]);
    }

    public String getCategorie() {
        Categoria[] categorie = null;
        int i = 0;
        try {
            stm = conn.createStatement(ResultSet.TYPE_SCROLL_SENSITIVE,ResultSet.CONCUR_READ_ONLY);
            
            risultatoQuery = stm.executeQuery("SELECT * FROM categoria ORDER BY nome_cat ASC;");
            risultatoQuery.last();
            int numCat = risultatoQuery.getRow();
            categorie = new Categoria[numCat];
            risultatoQuery.beforeFirst();
            while (risultatoQuery.next()) {
                categorie[i++] = new Categoria(risultatoQuery.getInt("id_categoria"),risultatoQuery.getString("nome_cat"));
            }
            stm.close();
            risultatoQuery.close();

        } catch (SQLException ex) {
            Logger.getLogger(DatabaseHandler.class.getName()).log(Level.SEVERE, null, ex);
        }
        Gson gson = new Gson();
        return gson.toJson(categorie);
    }

    public String getComuniPerProvincia(String provincia) {
        Comune[] listaComuni = null;
        int temp = 0;
        try {
            pstm = conn.prepareStatement("SELECT codice_istat, nome FROM comune WHERE provincia=? ORDER BY nome ASC;", ResultSet.TYPE_SCROLL_SENSITIVE, ResultSet.CONCUR_READ_ONLY);
            pstm.setString(1, provincia);
            risultatoQuery = pstm.executeQuery();
            risultatoQuery.last();
            listaComuni = new Comune[risultatoQuery.getRow()];//Creo un array con la dimensioni pari al numero di comuni per quella provincia
            risultatoQuery.beforeFirst();
            while (risultatoQuery.next()) {
                listaComuni[temp++] = new Comune(risultatoQuery.getString("codice_istat"), risultatoQuery.getString("nome"));//Inserisco i comuni (codice_istat e nome) nell'array
            }
            pstm.close();
            risultatoQuery.close();
        } catch (SQLException ex) {
            Logger.getLogger(DatabaseHandler.class.getName()).log(Level.SEVERE, null, ex);
        }
        Gson gson = new Gson();

        return gson.toJson(listaComuni);//Mando la Stringa al client come JSON
    }

    public int inserisciAnnuncio(String dataAnnuncioFromClient, String descrizione, String creatore,  int categoria) {
        if (descrizione != null && creatore != null && categoria > 0 && dataAnnuncioFromClient != null) {
            try {
                pstm = conn.prepareStatement("INSERT INTO annuncio(data_inserimento, data_annuncio, descrizione, creatore, categoria) VALUES(?,?,?,?,?)");
                /*
                Calendar calendarioJava = Calendar.getInstance();
                DateFormat formatoDataOraClient = new SimpleDateFormat("YYYY-MM-dd HH:mm");
                Date calAdesso = calendarioJava.getTime();
                Date calAnnuncio = formatoDataOraClient.parse(dataAnnuncioFromClient);
                String calAdessoStr = formatoDataOraClient.format(calAdesso);
                calAdesso = formatoDataOraClient.parse(calAdessoStr);
                java.sql.Timestamp data_inserimento = new java.sql.Timestamp(calAdesso.getTime());
                java.sql.Timestamp data_annuncio = new java.sql.Timestamp(calAnnuncio.getTime());*/
                DateFormat dateFormat = new SimpleDateFormat("yyyy/MM/dd HH:mm:ss");
                Date adesso = new Date();
                
                pstm.setString(1, dateFormat.format(adesso));
                pstm.setString(2, dataAnnuncioFromClient);
                pstm.setString(3, descrizione);
                pstm.setString(4, creatore);
                pstm.setInt(5, categoria);                
                esito = pstm.executeUpdate();
                pstm.close();
            } catch (SQLException e) {
                System.err.println("Errore SQL Generico: " + e);
                esito = -2;
            }
        } else {
            esito = -1;
        }
        return esito;
    }

    public int loginUtente(String username, String password) {
        esito = -3;
        try {
            pstm = conn.prepareStatement("SELECT password FROM utente WHERE username=?;", ResultSet.TYPE_SCROLL_SENSITIVE, ResultSet.CONCUR_READ_ONLY);
            pstm.setString(1, username);
            risultatoQuery = pstm.executeQuery();
            if (risultatoQuery.last()) {//Controllo se la Query ha prodotto risultati
                esito = BCrypt.checkpw(password, risultatoQuery.getString("password")) ? 0 : -1; //0-Ok   1-Password Sbagliata
            } else {
                esito = -2;//Utente non trovato
            }
        } catch (SQLException ex) {
            Logger.getLogger(DatabaseHandler.class.getName()).log(Level.SEVERE, null, ex);
        } finally {
            return esito;
        }
    }
}
