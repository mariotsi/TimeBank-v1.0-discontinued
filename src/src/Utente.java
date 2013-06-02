/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package src;

/**
 *
 * @author Simone
 */
class Utente{
private String username;//1
    private String email;//3
    private String indirizzo;//8
    private String cap;
    private String citta;
    private String provincia;
    private boolean admin;
    private int codiceErrore;    
    
    public Utente(String username, String email, String indirizzo, String cap, String citta, String provincia, boolean admin, int codiceErrore) {
        this.username=username;
        this.email=email;
        this.indirizzo=indirizzo;
        this.cap=cap;
        this.citta=citta;
        this.provincia=provincia;
        this.admin=admin;
        this.codiceErrore=codiceErrore;
    }
    
}
