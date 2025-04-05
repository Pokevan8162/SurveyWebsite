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
	
	public String season() { // season that password with some salt
	    byte[] salt = new byte[16]; // 16 bytes = 128 bits
	    new SecureRandom().nextBytes(salt);
	    return Base64.getEncoder().encodeToString(salt);
	}

	private String bytesToHex(byte[] bytes) {
        StringBuilder hexString = new StringBuilder();
        for (byte b : bytes) {
            hexString.append(String.format("%02x", b));
        }
        return hexString.toString();
    }
	
	public static void main(String[] args) throws NoSuchAlgorithmException {
		PasswordHash hasher = new PasswordHash();
		String salt = hasher.season();
		System.out.println(salt); // print to console so PHP can grab it
	}
}
