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
            System.out.println( "\nDropping triggers...");

            stmt.executeUpdate("DROP TRIGGER IF EXISTS update_netvote_after_insert" );
            stmt.executeUpdate("DROP TRIGGER IF EXISTS update_netvote_after_delete" );
            stmt.executeUpdate("DROP TRIGGER IF EXISTS update_netvote_after_update" );
            stmt.executeUpdate("DROP TRIGGER IF EXISTS delete_content_after_delete_comment" );

            System.out.println( "\nDropping views...");
            stmt.executeUpdate("DROP VIEW IF EXISTS homepage_view" );
            stmt.executeUpdate("DROP VIEW IF EXISTS bestofea_week_view" );

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
                    "moderator_token VARCHAR(32) NOT NULL,"+
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
                    "cont_id INT NOT NULL, " +
                    "username VARCHAR(32) NOT NULL, " +
                    "dst_cont_id INT, " +
                    "parent_post INT,"+
                    "PRIMARY KEY (cont_id, username), " +
//                    "FOREIGN KEY (cont_id) REFERENCES Content(cont_id)," +
                    "FOREIGN KEY (parent_post) REFERENCES Post(cont_id)," +
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

            System.out.println( "\nCreating triggers...");

            sql = "CREATE TRIGGER update_netvote_after_insert " +
                    "AFTER INSERT ON Vote " +
                    "FOR EACH ROW BEGIN " +
                    "IF NEW.vote THEN SET @val = 1; " +
                    "ELSE SET @val = -1; " +
                    "END IF; " +
                    "SET @cont_id = NEW.cont_id; " +
                    "UPDATE Content SET net_vote = net_vote + @val " +
                    "WHERE cont_id = @cont_id; "+
                    "END;";
            stmt.executeUpdate(sql);

            sql = "CREATE TRIGGER update_netvote_after_delete " +
                    "AFTER DELETE ON Vote " +
                    "FOR EACH ROW BEGIN " +
                    "IF OLD.vote THEN SET @val = -1; " +
                    "ELSE SET @val = 1; " +
                    "END IF; " +
                    "SET @cont_id = OLD.cont_id; " +
                    "UPDATE Content SET net_vote = net_vote + @val " +
                    "WHERE cont_id = @cont_id; "+
                    "END;";
            stmt.executeUpdate(sql);

            sql = "CREATE TRIGGER update_netvote_after_update " +
                    "AFTER UPDATE ON Vote " +
                    "FOR EACH ROW BEGIN " +
                    "IF NEW.vote THEN SET @val = 2; " +
                    "ELSE SET @val = -2; " +
                    "END IF; " +
                    "SET @cont_id = OLD.cont_id; " +
                    "UPDATE Content SET net_vote = net_vote + @val " +
                    "WHERE cont_id = @cont_id; "+
                    "END;";
            stmt.executeUpdate(sql);
            
            sql =   "CREATE TRIGGER delete_content_after_delete_comment  AFTER DELETE on Comment " +
	        		"FOR EACH ROW " +
	        		"BEGIN " +
	        		"DELETE FROM content " +
	        		"WHERE Content.cont_id = Comment.cont_id; " +
	                "END;";
            stmt.executeUpdate(sql);

            System.out.println( "\nTriggers added.");

            System.out.println( "\nCreating views...");
            sql = "SELECT net_vote, C.cont_id, timestamp, content, content_type, username, post_title, "+
                    "post_type, belongs,category_name " +
                    "FROM Post AS P, Content AS C, Category_Topic AS CT " +
                    "WHERE P.cont_id = C.cont_id AND P.belongs = CT.topic_name "+
                    "ORDER BY net_vote DESC";

            stmt.executeUpdate("CREATE VIEW homepage_view AS (" + sql + ")");


            sql = "SELECT max(net_vote) as net_vote, C.cont_id, timestamp, content, content_type," +
                    " username, post_title, post_type, belongs,category_name " +
                    " FROM Post AS P, Content AS C, Category_Topic AS CT " +
                    " WHERE P.cont_id = C.cont_id AND P.belongs = CT.topic_name AND timestamp > now() - INTERVAL 1 WEEK" +
                    " GROUP BY category_name";

            stmt.executeUpdate("CREATE VIEW bestofea_week_view AS (" + sql + ")");

        } catch (SQLException e) {
            throw new IllegalStateException( e.getMessage(), e);
        }
    }
}
