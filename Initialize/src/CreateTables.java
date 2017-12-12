import java.sql.Connection;
import java.sql.DatabaseMetaData;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

public class CreateTables {

    public static void main(String[] args) {
        System.out.println("Hello World!");

        String url = "jdbc:mysql://localhost:3306/cs353db";
        String username = "root";
        String password = "";

        try (
            Connection con = DriverManager.getConnection(url, username, password)) {
            System.out.println("Connected!");

            Statement stmt = con.createStatement();

		    // Drop tables
            System.out.println("\nDeleting previous tables (if any)...");
            DatabaseMetaData metadata = con.getMetaData();
            String sql = null;

            String[] tables = {"Comment",  "Message", "Category_Topic","Vote", "Follows", "Banned",
                                "Admin", "Moderator", "Post","Content","Category","Topic","User"};

            System.out.println( "\nDropping tables:");

            for (String currentTable: tables){
                System.out.println( currentTable);

                ResultSet resultSet = metadata.getTables( "cs353db", null, currentTable, null);

                if(resultSet.next()){
                    System.out.println( "Dropping " + currentTable);
                    sql = "DROP TABLE `" + currentTable + "`;";
                    stmt.executeUpdate(sql);
                }

            }
            System.out.println( "\nCreating new tables...");

            // USER
            sql = "CREATE TABLE User (" +
                    "username VARCHAR(32) PRIMARY KEY," +
                    "password VARCHAR(32) NOT NULL," +
                    "email_address VARCHAR(32) NOT NULL," +
                    "joined_date DATE) ENGINE = InnoDB;";

            stmt.executeUpdate(sql);
            System.out.println( "User created!");


            // ADMIN
            sql = "CREATE TABLE Admin (" +
                    "username VARCHAR(32) PRIMARY KEY," +
                    "password VARCHAR(32) NOT NULL," +
                    "email_address VARCHAR(32) NOT NULL," +
                    "joined_date DATE," +
                    "admin_token VARCHAR(32) NOT NULL) ENGINE = InnoDB;";

            stmt.executeUpdate(sql);
            System.out.println("Admin created!");

            //CONTENT
            sql = "CREATE TABLE Content (" +
                    "cont_id  INT AUTO_INCREMENT PRIMARY KEY, "+
                    "timestamp DATE,"+
                    "content VARCHAR(800) NOT NULL,"+
                    "content_type VARCHAR(10) NOT NULL,"+
                    "username VARCHAR(32) NOT NULL,"+
                    "net_vote INT," +
                    "FOREIGN KEY(username) REFERENCES User(username)) ENGINE = InnoDB;";

            stmt.executeUpdate(sql);
            System.out.println("Content created!");

            //CATEGORY
            sql = "CREATE TABLE Category ("+
                    "name VARCHAR(100) PRIMARY KEY) ENGINE = InnoDB;";

            stmt.executeUpdate(sql);
            System.out.println("Category created!");


            //TOPIC
            sql = "CREATE TABLE Topic ("+
                    "topic_name VARCHAR(50) PRIMARY KEY) ENGINE = InnoDB;";

            stmt.executeUpdate(sql);
            System.out.println("Topic created!");

            //MODERATOR
            sql = "CREATE TABLE Moderator (" +
                    "username VARCHAR(32) PRIMARY KEY," +
                    "password VARCHAR(32) NOT NULL," +
                    "email_address VARCHAR(32) NOT NULL," +
                    "joined_date DATE,"+
                    "category_name VARCHAR(100),"+
                    "FOREIGN KEY (category_name) REFERENCES Category(name))ENGINE = InnoDB;";

            stmt.executeUpdate(sql);
            System.out.println("Moderator created!");


            // POST
            sql = "CREATE TABLE Post (" +
                    "cont_id INT PRIMARY KEY," +
                    "post_title VARCHAR(50) NOT NULL," +
                    "post_type VARCHAR(50) NOT NULL," +
                    "belongs VARCHAR(50) NOT NULL," +
                    "FOREIGN KEY (belongs) REFERENCES Topic(topic_name)," +
                    "FOREIGN KEY (cont_id) REFERENCES Content(cont_id)) ENGINE = InnoDB;";

            stmt.executeUpdate(sql);
            System.out.println("Post created!");


            //COMMENT
            sql = "CREATE TABLE Comment (" +
                    "cont_id INT NOT NULL," +
                    "username VARCHAR(32) NOT NULL," +
                    "dst_cont_id INT," +
                    "timestamp DATE,"+
                    "comment VARCHAR(800) NOT NULL,"+
                    "PRIMARY KEY (cont_id, username)," +
                    //"FOREIGN KEY (cont_id) REFERENCES Content(cont_id)," +
                    "FOREIGN KEY (dst_cont_id) REFERENCES Content(cont_id)," +
                    "FOREIGN KEY (username) REFERENCES User(username)) ENGINE = InnoDB;";

            stmt.executeUpdate(sql);
            System.out.println("Comment created!");


            //MESSAGE
            sql = "CREATE TABLE Message (" +
                    "message_id INT AUTO_INCREMENT PRIMARY KEY, "+
                    "dst_name VARCHAR(32) NOT NULL," +
                    "rcv_name VARCHAR(32) NOT NULL," +
                    "timestamp DATE," +
                    "message VARCHAR(32) NOT NULL," +
                    "FOREIGN KEY (dst_name) REFERENCES User(username)," +
                    "FOREIGN KEY (rcv_name) REFERENCES User(username)) ENGINE = InnoDB;";

            stmt.executeUpdate(sql);
            System.out.println("Message created!");


            //CATEGORY_TOPIC
            sql = "CREATE TABLE Category_Topic ("+
                    "category_name varchar(50) NOT NULL,"+
                    "topic_name varchar(50) NOT NULL,"+
                    "FOREIGN KEY (category_name) REFERENCES Category(name),"+
                    "FOREIGN KEY (topic_name) REFERENCES Topic(topic_name))ENGINE = InnoDB;";

            stmt.executeUpdate(sql);
            System.out.println("Category_Topic created!");


            //VOTE
            sql = "CREATE TABLE Vote ("+
                    "username varchar(32) NOT NULL,"+
                    "cont_id INT NOT NULL,"+
                    "vote BOOLEAN NOT NULL,"+
                    "PRIMARY KEY(username, cont_id),"+
                    "FOREIGN KEY (username) REFERENCES User(username),"+
                    "FOREIGN KEY (cont_id) REFERENCES Content(cont_id))ENGINE = InnoDB;";

            stmt.executeUpdate(sql);
            System.out.println("Vote created!");


            //FOLLOWS
            sql = "CREATE TABLE Follows ("+
                    "follower varchar(32) NOT NULL,"+
                    "following varchar(32) NOT NULL,"+
                    "PRIMARY KEY(follower,following),"+
                    "FOREIGN KEY (follower) REFERENCES User(username),"+
                    "FOREIGN KEY (following) REFERENCES User(username))ENGINE = InnoDB;";

            stmt.executeUpdate(sql);
            System.out.println("Follows created!");

            //BANNED
            sql = "CREATE TABLE Banned("+
                    "username varchar(32),"+
                    "category_name varchar(100),"+
                    "PRIMARY KEY (username, category_name),"+
                    "FOREIGN KEY (username) REFERENCES User(username),"+
                    "FOREIGN KEY (category_name) REFERENCES Category(name))ENGINE = InnoDB;";

            stmt.executeUpdate(sql);
            System.out.println("Banned created!");


            System.out.println("\n\nAll tables are created!");
        } catch (SQLException e) {
            throw new IllegalStateException( e.getMessage(), e);
        }
    }
}