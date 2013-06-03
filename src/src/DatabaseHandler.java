package src;

import com.google.gson.Gson;
import java.sql.*;
import java.text.DateFormat;
import java.util.ArrayList;
import java.util.logging.Level;
import java.util.logging.Logger;
import java.text.SimpleDateFormat;
import java.util.Date;

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
            stm = conn.createStatement(ResultSet.TYPE_SCROLL_SENSITIVE, ResultSet.CONCUR_READ_ONLY);
            risultatoQuery = stm.executeQuery("SELECT * FROM categoria ORDER BY nome_cat ASC;");
            risultatoQuery.last();
            int numCat = risultatoQuery.getRow();
            categorie = new Categoria[numCat - 1];//-1 perchè la categoria 100 non la invierò al client
            risultatoQuery.beforeFirst();
            while (risultatoQuery.next()) {
                if (risultatoQuery.getInt("id_categoria") != 100) {//non voglio che sia possibile segliere la categoria "senza Categoria"
                    categorie[i++] = new Categoria(risultatoQuery.getInt("id_categoria"), risultatoQuery.getString("nome_cat"));
                }
            }
            stm.close();
            risultatoQuery.close();
        } catch (SQLException ex) {
            Logger.getLogger(DatabaseHandler.class.getName()).log(Level.SEVERE, null, ex);
        }
        return new Gson().toJson(categorie);
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
        return new Gson().toJson(listaComuni);//Mando la Stringa al client come JSON
    }

    public int inserisciAnnuncio(String dataAnnuncioFromClient, String descrizione, String creatore, int categoria) {
        if (descrizione != null && creatore != null && categoria > 0 && dataAnnuncioFromClient != null) {
            try {
                pstm = conn.prepareStatement("INSERT INTO annuncio(data_inserimento, data_annuncio, descrizione, creatore, categoria) VALUES(?,?,?,?,?)");
                DateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm");
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
            pstm.close();
            risultatoQuery.close();
        } catch (SQLException ex) {
            Logger.getLogger(DatabaseHandler.class.getName()).log(Level.SEVERE, null, ex);
        } finally {
            return esito;
        }
    }

    String getAnnuncio(int id_annuncio) {
        return new Gson().toJson(new SearchAnnuncio(conn).byId(id_annuncio));
    }

    int richiediAnnuncio(int id_annuncio, String creatore, String richiedente) {
        esito = -3;
        try {
            pstm = conn.prepareStatement("SELECT richiesto FROM annuncio WHERE id_annuncio = ? AND creatore = ?;", ResultSet.TYPE_SCROLL_SENSITIVE, ResultSet.CONCUR_READ_ONLY);
            pstm.setInt(1, id_annuncio);
            pstm.setString(2, creatore);
            risultatoQuery = pstm.executeQuery();
            risultatoQuery.next();
            if (!risultatoQuery.getBoolean(1)) {//se l'annuncio non è già richiesto
                if (!creatore.equals(richiedente)) {
                    pstm = conn.prepareStatement("UPDATE annuncio SET richiesto = true, richiedente = ? WHERE id_annuncio = ? AND creatore = ?;", ResultSet.TYPE_SCROLL_SENSITIVE, ResultSet.CONCUR_READ_ONLY);
                    pstm.setString(1, richiedente);
                    pstm.setInt(2, id_annuncio);
                    pstm.setString(3, creatore);
                    pstm.executeUpdate();
                    esito = 0;
                } else {
                    esito = -4; //richiedente = creatore
                }
            } else {
                esito = -2;//Annuncio già richiesto
            }
        } catch (SQLException ex) {
            Logger.getLogger(DatabaseHandler.class.getName()).log(Level.SEVERE, null, ex);
            esito = -1; //Annuncio non trovato - Errore SQL
        }
        return esito;
    }

    String cercaAnnunci(String creatore, String provincia, String comune, int categoria, boolean all) {
        Annuncio tmp = new Annuncio();
        int nParamtro = 0;
        int errore = 0;
        ArrayList<Annuncio> annunci = new ArrayList<Annuncio>();
        String query = "SELECT id_annuncio FROM annuncio JOIN utente ON creatore=username WHERE username=username ";
        if (creatore != null && creatore != "") {
            query += "AND creatore='" + creatore + "' ";
        }
        if (provincia != null && provincia != "") {
            query += "AND provincia='" + provincia + "' ";
        }
        if (comune != null && comune != "") {
            query += "AND citta='" + comune + "' ";
        }
        if (categoria != -1) {
            query += "AND categoria=" + categoria + " ";
        }
        try {
            stm = conn.createStatement(ResultSet.TYPE_SCROLL_SENSITIVE, ResultSet.CONCUR_READ_ONLY);
            risultatoQuery = stm.executeQuery(query);
            while (risultatoQuery.next()) {
                Annuncio an = new SearchAnnuncio(conn).byId(risultatoQuery.getInt("id_annuncio"));
                if (an.getCodiceErrore() == 0 && (!an.isRichiesto() || all)) {
                    annunci.add(an);
                }
            }
        } catch (SQLException ex) {
            annunci.clear();
            Logger.getLogger(DatabaseHandler.class.getName()).log(Level.SEVERE, null, ex);
        } finally {
            if (annunci.isEmpty()) {
                tmp.setCodiceErrore(-10);
                annunci.add(tmp);
            }
        }
        return new Gson().toJson(annunci);
    }

    boolean eliminaCategoria(int id_categoria) {
        boolean esito = true;
        try {
            stm = conn.createStatement();
            esito = (stm.executeUpdate("DELETE FROM categoria WHERE id_categoria=" + id_categoria + ";") > 0) ? true : false;
        } catch (SQLException ex) {
            Logger.getLogger(DatabaseHandler.class.getName()).log(Level.SEVERE, null, ex);
            esito = false;
        }
        return esito;
    }

    boolean modificaCategoria(int id_categoria, String nuovoNome) {
        boolean esito = true;
        try {
            pstm = conn.prepareStatement("UPDATE categoria SET nome_cat=? WHERE id_categoria=?");
            pstm.setString(1, nuovoNome);
            pstm.setInt(2, id_categoria);
            esito = (pstm.executeUpdate() > 0) ? true : false;
            pstm.close();
        } catch (SQLException ex) {
            Logger.getLogger(DatabaseHandler.class.getName()).log(Level.SEVERE, null, ex);
            esito = false;
        }
        return esito;
    }

    boolean isAdmin(String username) {
        boolean admin = false;
        try {
            stm = conn.createStatement();
            risultatoQuery = stm.executeQuery("SELECT admin FROM utente WHERE username='" + username + "';");
            risultatoQuery.next();
            admin = risultatoQuery.getBoolean("admin");
        } catch (SQLException ex) {
            Logger.getLogger(DatabaseHandler.class.getName()).log(Level.SEVERE, null, ex);
        }
        return admin;
    }

    String getUtenti() {
        ArrayList<String> listaUtenti = new ArrayList<String>();
        try {
            stm = conn.createStatement();
            risultatoQuery = stm.executeQuery("SELECT username FROM utente ORDER BY username ASC;");
            while (risultatoQuery.next()) {
                listaUtenti.add(risultatoQuery.getString("username"));
            }
        } catch (SQLException ex) {
            Logger.getLogger(DatabaseHandler.class.getName()).log(Level.SEVERE, null, ex);
        }
        return new Gson().toJson(listaUtenti);
    }

    boolean eliminaUtente(String username) {
        boolean esito = true;
        try {
            stm = conn.createStatement();
            esito = (stm.executeUpdate("DELETE FROM utente WHERE username='" + username + "';") > 0) ? true : false;
        } catch (SQLException ex) {
            Logger.getLogger(DatabaseHandler.class.getName()).log(Level.SEVERE, null, ex);
            esito = false;
        }
        return esito;
    }

    String getUtente(String username) {
        return new Gson().toJson(new SearchUtente(conn).byId(username));
    }

    int modificaUtente(String username, String password, String email, String indirizzo, String cap, String citta, String provincia, boolean admin, String oldUsername) {
        esito = -1;
        try {
            String query = "UPDATE utente SET username=?, email=?, indirizzo=?, cap=?, citta=?, provincia=?, admin=? WHERE username=? ";
            pstm = conn.prepareStatement(query, ResultSet.TYPE_SCROLL_SENSITIVE, ResultSet.CONCUR_READ_ONLY);
            pstm.setString(1, username);
            pstm.setString(2, email);
            pstm.setString(3, indirizzo);
            pstm.setString(4, cap);
            pstm.setString(5, citta); //Codice ISTAT
            pstm.setString(6, provincia);
            pstm.setBoolean(7, admin);
            pstm.setString(8, oldUsername);
            esito = (pstm.executeUpdate() > 0) ? 0 : -2;
            pstm.close();
            if (password != null && !password.equals("")) {
                pstm = conn.prepareStatement("UPDATE utente SET password=? WHERE username=?");
                pstm.setString(1, BCrypt.hashpw(password, BCrypt.gensalt()));
                pstm.setString(2, username);//uso il nuovo username
                esito = (pstm.executeUpdate() > 0) ? 0 : -2;
                pstm.close();
            }

        } catch (SQLException ex) {
            Logger.getLogger(DatabaseHandler.class.getName()).log(Level.SEVERE, null, ex);
            esito = -2;
            System.err.println("Errore generico: " + ex);
        }
        return esito;
    }

    int modificaAnnuncio(int id_annuncio, String data_annuncio, String descrizione, String richiedente, int id_categoria) {
        esito = -1;
        try {
            String query = "UPDATE annuncio SET data_annuncio=?, descrizione=?, categoria=? WHERE id_annuncio=? ";
            pstm = conn.prepareStatement(query, ResultSet.TYPE_SCROLL_SENSITIVE, ResultSet.CONCUR_READ_ONLY);
            pstm.setString(1, data_annuncio);
            pstm.setString(2, descrizione);
            pstm.setInt(3, id_categoria);
            pstm.setInt(4, id_annuncio);
            esito = (pstm.executeUpdate() > 0) ? 0 : -2;
            pstm.close();
            if (richiedente != null && !richiedente.equals("") && !richiedente.equals("Non Richiesto")) {
                pstm = conn.prepareStatement("UPDATE annuncio SET richiedente=?, richiesto=true WHERE id_annuncio=?");
                pstm.setString(1, richiedente);
                pstm.setInt(2, id_annuncio);
                esito = (pstm.executeUpdate() > 0) ? 0 : -2;
                pstm.close();
            } else if (richiedente != null && richiedente.equals("Non Richiesto")) {
                pstm = conn.prepareStatement("UPDATE annuncio SET richiedente=?, richiesto=false WHERE id_annuncio=?");
                pstm.setNull(1, Types.VARCHAR);
                pstm.setInt(2, id_annuncio);
                esito = (pstm.executeUpdate() > 0) ? 0 : -2;
                pstm.close();
            }

        } catch (SQLException ex) {
            Logger.getLogger(DatabaseHandler.class.getName()).log(Level.SEVERE, null, ex);
            esito = -2;
            System.err.println("Errore generico: " + ex);
        }
        return esito;
    }    

    boolean eliminaAnnuncio(int id_annuncio) {
        boolean esito = true;
        try {
            stm = conn.createStatement();
            esito = (stm.executeUpdate("DELETE FROM annuncio WHERE id_annuncio="+ id_annuncio) > 0) ? true : false;
        } catch (SQLException ex) {
            Logger.getLogger(DatabaseHandler.class.getName()).log(Level.SEVERE, null, ex);
            esito = false;
        }
        return esito;
    }
}
