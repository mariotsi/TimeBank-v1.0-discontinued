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
public class Comune {

    private String codice_istat;
    private String nome;

    public Comune(String codice_istat, String nome) {
        this.codice_istat = codice_istat;
        this.nome = nome;
    }
}
