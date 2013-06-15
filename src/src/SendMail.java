/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package src;

 
import java.util.Properties;
import javax.mail.Message;
import javax.mail.MessagingException;
import javax.mail.PasswordAuthentication;
import javax.mail.Session;
import javax.mail.Transport;
import javax.mail.internet.InternetAddress;
import javax.mail.internet.MimeMessage;
 
public class SendMail {
	public SendMail(String emailCreatore,String emailRichiedente,String testoAnnuncio, String richiedente, String creatore) {
		Properties props = new Properties();
		props.put("mail.smtp.host", "smtp.gmail.com");
		props.put("mail.smtp.socketFactory.port", "465");
		props.put("mail.smtp.socketFactory.class",
				"javax.net.ssl.SSLSocketFactory");
		props.put("mail.smtp.auth", "true");
		props.put("mail.smtp.port", "465");
 
		Session session = Session.getDefaultInstance(props,
			new javax.mail.Authenticator() {
				protected PasswordAuthentication getPasswordAuthentication() {
					return new PasswordAuthentication("noreply.timebank@gmail.com","sistemiapertiedistribuiti");
				}
			});
 
		try {//email al creatore dell'annuncio
 
			Message message = new MimeMessage(session);
			message.setFrom(new InternetAddress("noreply.timebank@gmail.com"));
			message.setRecipients(Message.RecipientType.TO,
					InternetAddress.parse(emailCreatore));
			message.setSubject(richiedente+" ha richiesto un tuo annuncio!");
			message.setText("Ciao "+creatore+",\n"+
					"\n\n"+richiedente+" ha richiesto questo tuo annuncio:\n\n"+
                                        "\""+testoAnnuncio+"\"\n\nMettetevi in contatto, Questa è la sua mail: "+emailRichiedente);
 
			Transport.send(message);
 
			System.out.println("Done");
 
		} catch (MessagingException e) {
			throw new RuntimeException(e);
		}
                
                try {//email al destinatario dell'annuncio
 
			Message message = new MimeMessage(session);
			message.setFrom(new InternetAddress("noreply.timebank@gmail.com"));
			message.setRecipients(Message.RecipientType.TO,
					InternetAddress.parse(emailRichiedente));
			message.setSubject("Hai correttamente richiesto l'annuncio di "+creatore);
			message.setText("Ciao "+richiedente+",\n"+
					"\n\nHai richiesto questo annuncio di "+creatore+":\n\n"+
                                        "\""+testoAnnuncio+"\"\n\nMettetevi in contatto, Questa è la sua mail: "+emailCreatore);
 
			Transport.send(message);
 
			System.out.println("Done");
 
		} catch (MessagingException e) {
			throw new RuntimeException(e);
		}
	}
}