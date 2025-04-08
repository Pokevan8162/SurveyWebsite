import java.security.NoSuchAlgorithmException;
import java.security.SecureRandom;
import java.util.Base64;

public class PasswordSalt {
	
	public String season() { // season that password with some salt
	    byte[] salt = new byte[16]; // 16 bytes = 128 bits
	    new SecureRandom().nextBytes(salt);
	    return Base64.getEncoder().encodeToString(salt);
	}
	
	public static void main(String[] args) throws NoSuchAlgorithmException {
		PasswordSalt salter = new PasswordSalt();
		String salt = salter.season();
		System.out.println(salt); // print to console so PHP can grab it
	}
}
