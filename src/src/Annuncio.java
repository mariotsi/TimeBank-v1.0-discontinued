/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package src;

import java.sql.Timestamp;

/**
 *
 * @author Simone
 */
public class Annuncio {
    private int id_annuncio;
    private Timestamp data_inserimento;
    private Timestamp data_annuncio;
    private boolean richiesto;
    private String descrizione;
    private String richiedente;
    private String creatore;
    private int categoria;
    private int codiceErrore;

    Annuncio(int id_annuncio, Timestamp data_inserimento, Timestamp data_annuncio, boolean richiesto, String descrizione, String richiedente, String creatore, int categoria, int codiceErrore) {
        this.id_annuncio=id_annuncio;
        this.data_inserimento=data_inserimento;
        this.data_annuncio=data_annuncio;
        this.richiesto=richiesto;
        this.descrizione=descrizione;
        this.richiedente=richiedente;
        this.creatore=creatore;
        this.categoria=categoria;
        this.codiceErrore=codiceErrore;
    }
    
}
