/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package src;

/**
 *
 * @author Simone
 */
public class TimeBankServer {

    /**
     * Sample method
     */
    public String hello(String name) {
        
       DatabaseHandler db = new DatabaseHandler();
        return "Hel5lyo " + name+db;
    }    
}
