package sk.fiit.adhunter.models;

import android.os.Environment;

import sk.fiit.adhunter.utils.FileUtils;

import java.io.BufferedOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;

/**
 * Created by jerry on 18. 2. 2015.
 */
public class User {

    private static final String TAG = "User.class";
    private static final int BUFFER_SIZE = 8 * 1024;

    private static final String dirName = "adhunter";
    public static String filePath = Environment.getDataDirectory() + "/data/sk.fiit.adhunter" + "/files/";
    public static String fileName = ".user";
    public static User instance = null;
    private final Object lock = new Object();

    //User fields
    private String username;
    private String password;
    private String uid;

    public static void createNewUser(String username, String password, String uid) throws Exception {
        deleteApplicationDirectory();

        User user = new User();
        user.username = username;
        user.password = password;
        user.uid = uid;

        user.saveState();
        User.instance = user;
    }

    public static boolean deleteApplicationDirectory() {
        User.instance = null;
        FileUtils.deleteFolder(User.dirName);
        File f = new File(User.filePath + User.fileName);
        return f.delete();
    }

    public void saveState() throws Exception {
        File f = new File(User.filePath + fileName);
        f.mkdirs();
        f.delete();

        synchronized (lock) {
            ObjectOutputStream oos = null;
            try {
                BufferedOutputStream os = new BufferedOutputStream(new FileOutputStream(User.filePath + fileName), BUFFER_SIZE);
                oos = new ObjectOutputStream(os);

                oos.writeObject(username);
                oos.writeObject(password);
                oos.writeObject(uid);
                oos.flush();

            } finally {
                if (oos != null) {
                    oos.close();
                }
            }
        }
    }

    public User loadState() throws Exception {
        ObjectInputStream ois = null;
        try {
            FileInputStream is = new FileInputStream(User.filePath + fileName);
            ois = new ObjectInputStream(is);
            username = (String) ois.readObject();
            password = (String) ois.readObject();
            uid = (String) ois.readObject();
        } finally {
            if (ois != null) {
                ois.close();
            }
        }
        return this;
    }

    public void updateState(String accessToken, String refreshToken) throws Exception {
        // not used yet... maybe useful when user logs out and afterwards logs in with different credentials
        saveState();
    }

    public static User getInstance() {
        if(User.instance != null || isLogged())
            return User.instance;
        return null;
    }

    public static boolean isLogged() {

        try{
            if(User.instance != null)
                return true;
            File f = new File(User.filePath + User.fileName);
            if (f.exists()) {
                User.instance = new User().loadState();
                return true;
            }
        }catch(Exception e){
            e.printStackTrace();
        }
        return false;
    }

    public String getUsername() {
        return username;
    }

}
