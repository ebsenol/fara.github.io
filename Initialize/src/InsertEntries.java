import java.sql.Connection;
import java.sql.DatabaseMetaData;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;

public class InsertEntries {
    public static void main(String[] args) {
        String url = "jdbc:mysql://localhost:3306/cs353db";
        String username = "root";
        String password = "";

        try (Connection con = DriverManager.getConnection(url, username, password)) {
            System.out.println("Connected!");

            Statement stmt = con.createStatement();
            DatabaseMetaData metadata = con.getMetaData();
            String sql = null;

            //Add users
            sql = "INSERT INTO User VALUES ('berku', 'merku', 'merku@b.com', now() - interval 1 year);";
            stmt.executeUpdate(sql);
            sql = "INSERT INTO User VALUES ('figali', '123123', 'fig@berku.com', now() - interval 1 year);";
            stmt.executeUpdate(sql);
            sql = "INSERT INTO User VALUES ('elena', '123321', 'elena@cina.com',now() - interval 1 year);";
            stmt.executeUpdate(sql);
            sql = "INSERT INTO User VALUES ('dias', '123456', 'alymbekov@dias.com', now() - interval 1 year);";
            stmt.executeUpdate(sql);
            sql = "INSERT INTO User VALUES ('toxic', 'toxic123', 'iam@toxic.com', now() - interval 1 year);";
            stmt.executeUpdate(sql);

            //Add admins
            sql = "INSERT INTO Admin VALUES ('Admin', 'Admin', 'Admin@Admin.com', now() - interval 1 year, 'qowenqwedjnsa');";
            stmt.executeUpdate(sql);

            //Add category
            sql = "INSERT INTO Category VALUES ('Sports');";
            stmt.executeUpdate(sql);
            sql = "INSERT INTO Category VALUES ('Science');";
            stmt.executeUpdate(sql);

            //Add topics
            sql = "INSERT INTO Topic VALUES ('Football');";
            stmt.executeUpdate(sql);
            sql = "INSERT INTO Topic VALUES ('Basketball');";
            stmt.executeUpdate(sql);
            sql = "INSERT INTO Topic VALUES ('Biology');";
            stmt.executeUpdate(sql);
            sql = "INSERT INTO Topic VALUES ('Computer Science');";
            stmt.executeUpdate(sql);

            //Add category_topic
            sql = "INSERT INTO Category_Topic VALUES ('Science','Biology');";
            stmt.executeUpdate(sql);
            sql = "INSERT INTO Category_Topic VALUES ('Science','Computer Science');";
            stmt.executeUpdate(sql);
            sql = "INSERT INTO Category_Topic VALUES ('Sports','Football');";
            stmt.executeUpdate(sql);
            sql = "INSERT INTO Category_Topic VALUES ('Sports','Basketball');";
            stmt.executeUpdate(sql);


            //Add content
            sql = "INSERT INTO Content VALUES (1230,now() - interval 1 day, 'this is an example topic content','post', 'berku', 0);";
            stmt.executeUpdate(sql);
            sql = "INSERT INTO Content VALUES (1231,now() - interval 1 day, 'this is an example topic content','post', 'dias', 0);";
            stmt.executeUpdate(sql);
            sql = "INSERT INTO Content VALUES (1232,now() - interval 1 day, 'www.beinsports.com.tr','post', 'berku', 0);";
            stmt.executeUpdate(sql);

            //Add moderator
            sql = "INSERT INTO Moderator VALUES ('messici', '123123', 'messi@barca.com', now() - interval 1 year, 'Sports');";
            stmt.executeUpdate(sql);
            sql = "INSERT INTO Moderator VALUES ('darwin', '123123', 'adnan@oktar.com', now() - interval 1 year, 'Science');";
            stmt.executeUpdate(sql);

            //Add post
            sql = "INSERT INTO Post VALUES (1231, 'Footbal post', 'text', 'Football');";
            stmt.executeUpdate(sql);
            sql = "INSERT INTO Post VALUES (1232, 'Basketball post', 'link', 'Basketball');";
            stmt.executeUpdate(sql);
            sql = "INSERT INTO Post VALUES (1230, 'Last week of biology', 'text', 'Biology');";
            stmt.executeUpdate(sql);

            //Add comment
            sql = "INSERT INTO Comment VALUES (21230, 'berku', 1230,1230);";
            stmt.executeUpdate(sql);
            sql = "INSERT INTO Comment VALUES (21231, 'berku', 1231 ,1231);";
            stmt.executeUpdate(sql);


            sql = "INSERT INTO Content VALUES (21230,now() - interval 1 day, 'wow, nice post!','comment', 'berku', 0);";
            stmt.executeUpdate(sql);
            sql = "INSERT INTO Content VALUES (21231,now() - interval 1 day, 'hmmm such a post!','comment', 'berku', 0);";
            stmt.executeUpdate(sql);
            //Add banned
            sql = "INSERT INTO Banned VALUES ('toxic', 'Science');";
            stmt.executeUpdate(sql);

            //Add votes
            sql = "INSERT INTO Vote VALUES ('berku',1231, True);";
            stmt.executeUpdate(sql);
            sql = "INSERT INTO Vote VALUES ('dias',1231, True);";
            stmt.executeUpdate(sql);

            System.out.println("\n\nAll tables are created!");
        } catch (SQLException e) {
            throw new IllegalStateException( e.getMessage(), e);
        }
    }
}
