/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package src;

import java.sql.*;

/**
 *
 * @author Simone
 */
public class DatabaseHandler {

    static final String URL = "jdbc:postgresql://localhost:5432/postgres";
    static final String USER = "postgres";
    static final String PSW = "chiara";
    private Connection conn = null;
    private String driver = "org.postgresql.Driver";

    public DatabaseHandler() {
        try {

            Class.forName(driver); //Carica il driver JDBC
        } catch (Exception ex) {
            ex.printStackTrace();
        }

        //connessione al DB
        try {

            conn = DriverManager.getConnection(URL, USER, PSW);
            System.err.println(conn.toString());
        } catch (Exception e) {
            e.printStackTrace();

        }
    }
}
