package JeremyWebsite;

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

	// Add java server to referenced libraries

    private String query;
    private static final String DB_USER = "username"; // change
    private static final String DB_PASSWORD = "password"; // change
    private static final String databaseName = "name"; // change
    private static final String DB_URL = "jdbc:sqlserver://localhost:1433;databaseName=" + databaseName + ";encrypt=true;trustServerCertificate=true";

    public Connection connect() {
        Connection connection = null;
        try {
            connection = DriverManager.getConnection(DB_URL, DB_USER, DB_PASSWORD);
            System.out.println("Connected to the database successfully!");
        } catch (SQLException e) {
            System.err.println("Connection failed! " + e.getMessage());
        }
        return connection;
    }

	public String hashPassword(String username, String password) throws NoSuchAlgorithmException {
		String salt = season();
		System.out.println("Password before salt: " + password);
		password = password + salt;
		System.out.println("Password after salt: " + password);
		MessageDigest md = MessageDigest.getInstance("SHA3-256");
        
    byte[] byteHash = md.digest(password.getBytes());
    String hashedPassword = bytesToHex(byteHash);
		query = "INSERT INTO Users (Email, Password, Palt) VALUES (?, ?, ?)";
		try (Connection connection = connect(); PreparedStatement preparedStatement = connection.prepareStatement(query)) {
	        preparedStatement.setString(1, username);
	        preparedStatement.setString(2, hashedPassword);
          preparedStatement.setString(3, salt);

	        int rowsInserted = preparedStatement.executeUpdate();
	        if (rowsInserted > 0) {
	            System.out.println("Password inserted successfully for user: " + username);
	        } else {
	            System.out.println("Failed to insert password.");
            }

	    } catch (SQLException e) {
	        System.err.println("Database error: " + e.getMessage());
      }
		return hashedPassword;
	}
	
	public String season() { // season that password with some salt
	    byte[] salt = new byte[16]; // 16 bytes = 128 bits
	    new SecureRandom().nextBytes(salt);
	    return Base64.getEncoder().encodeToString(salt);
	}

	public void checkPassword(String username, String password) {
		// Checks if the password exists at the username (for password validation used in hashPassword and forgotPassword)
	}
	
	public void forgotPassword() {
		// Sends email to user with instructions
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
		String password = "password";
		System.out.println(password);
		password = hasher.hashPassword("username", password);
		System.out.println(password);
	}
}
