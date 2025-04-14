import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

public class PasswordHash {

	public String hashPassword(String password) throws NoSuchAlgorithmException {
		MessageDigest md = MessageDigest.getInstance("SHA3-256");
		byte[] byteHash = md.digest(password.getBytes());
    		String hashedPassword = bytesToHex(byteHash);

		return hashedPassword;
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
		String password = args[0];
		password = hasher.hashPassword(password);
		System.out.println(password); // print to console so PHP can grab it
	}
}
