import java.sql.Connection;
import java.sql.Date;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.security.SecureRandom;
import java.util.Arrays;
import java.util.Base64;

public class PasswordHash {

	public String hashPassword(String password) throws NoSuchAlgorithmException {
		MessageDigest md = MessageDigest.getInstance("SHA3-256");
        
    	byte[] byteHash = md.digest(password.getBytes());
    	String hashedPassword = bytesToHex(byteHash);

		return hashedPassword;
	}
	
	public static void main(String[] args) throws NoSuchAlgorithmException {
		PasswordHash hasher = new PasswordHash();
		String password = args[0];
		password = hasher.hashPassword(password);
		System.out.println(password); // print to console so PHP can grab it
	}
}
