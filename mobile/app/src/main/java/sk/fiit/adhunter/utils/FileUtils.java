package sk.fiit.adhunter.utils;

import android.os.Environment;
import android.util.Log;

import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.text.SimpleDateFormat;
import java.util.Date;

/**
 * Created by jerry on 26. 11. 2014.
 */
public class FileUtils {
    private static final String TAG = "FileUtils";

    public static final String DIRECTORY_MAIN = "MyCameraApp";
    public static final String DIRECTORY_UPLOAD = "to_upload";
    public static final String JSON_FILE_NAME = "photos-to-upload.json";
    public static final int MEDIA_TYPE_IMAGE = 1;
    public static final int MEDIA_TYPE_COMPRESSED = 2; //BASE64

    /**
     * Creates a File for saving an image or video.
     * @param type Type of media.
     * @return File created, but empty so far
     */
    public static File getOutputMediaFile(int type, boolean isWifiOrMobileOn) {
        if(!Environment.getExternalStorageState().equals(Environment.MEDIA_MOUNTED)) {
            Log.d(TAG, "SD Card is not mounted!");
            return null;
        }

        File mediaStorageDir = getMainDirectory();
        if(!mediaStorageDir.exists()) {
            if(!mediaStorageDir.mkdirs()) {
                Log.d(TAG, "failed to create " + DIRECTORY_MAIN + "directory");
                return null;
            }
        }

        //ak WiFi nie je zapnuta, vytvori sa priecinok to_upload, kde sa budu ukladat fotky odfotene v offline rezime
        if(!isWifiOrMobileOn) {
            mediaStorageDir = getUploadDirectory();
            if(!mediaStorageDir.exists()) {
                if(!mediaStorageDir.mkdirs()) {
                    Log.d(TAG, "failed to create " + DIRECTORY_UPLOAD + "directory");
                    return null;
                }
            }
        }

        //Create a media file name
        String timeStamp = new SimpleDateFormat("yyyyMMdd_HHmmss").format(new Date());
        File mediaFile;

        //Create a file depending on file type
        if(type == MEDIA_TYPE_IMAGE) {
            mediaFile = new File(mediaStorageDir.getPath() + File.separator +
                    "IMG_" + timeStamp + ".jpg");
        } else if(type == MEDIA_TYPE_COMPRESSED) {
            mediaFile = new File(mediaStorageDir.getPath() + File.separator +
                    "IMG_" + timeStamp + "_base64.jpg");
        } else {
            return null;
        }
        return mediaFile;
    }

    public static File getMainDirectory() {
        return new File(Environment.getExternalStoragePublicDirectory(
                Environment.DIRECTORY_PICTURES), DIRECTORY_MAIN);
    }

    public static File getUploadDirectory() {
        return new File(Environment.getExternalStoragePublicDirectory(
                Environment.DIRECTORY_PICTURES + File.separator + DIRECTORY_MAIN), DIRECTORY_UPLOAD);
    }

    public static boolean isUploadDirectoryEmpty() {
        File file = new File(getUploadDirectory().getAbsolutePath());
        return file.isDirectory() && file.list().length == 0;
    }

    public static void deleteFolder(String filename) {
        java.io.File f = new java.io.File(filename);
        if (f.isFile()) {
            f.delete();
        }
        if (f.isDirectory() && !f.getName().equals(".") && !f.getName().equals("..")) {
            String[] files = f.list();
            for (String item : files) {
                deleteFolder(filename + "/" + item);
            }
        }
        if (f.isDirectory()) {
            f.delete();
        }
    }

    // * * * * * JSON IS NOT USED IN THE APP ANYMORE, BUT THE CODE MIGHT BE USEFUL IN THE FUTURE * *
    public static File writeToJson(File path, String jsonObjectString) {
        File file = new File(path + File.separator + JSON_FILE_NAME);
        Log.d(TAG, "file = " + file.getAbsolutePath());
        if(!file.exists()) {
            try {
                // creates the file
                file.createNewFile();
                // creates a FileWriter Object
                FileWriter writer = new FileWriter(file, true);
                // Writes first '[' to the file, as the beginning of JSON
                writer.write("[");
                // Writes the content to the file
                writer.write(jsonObjectString);
                writer.flush();
                writer.close();
            } catch (IOException e) {
                e.printStackTrace();
            }
        } else {
            Log.d(TAG, "file already exists, gonna append");
            try {
                // creates a FileWriter Object
                FileWriter writer = new FileWriter(file, true);
                // Writes the content to the file
                // If JSON is about to be sent, jsonObjectString will be ']' as the end of JSON
                writer.write("," + jsonObjectString);
                writer.flush();
                writer.close();
            } catch (IOException e) {
                e.printStackTrace();
            }
        }
        return file;
    }

    public static File writeToJson(File path, String jsonObjectString, boolean isEndOfJson) {
        File file = new File(path + File.separator + JSON_FILE_NAME);
        if(isEndOfJson) {
            try {
                // creates a FileWriter Object
                FileWriter writer = new FileWriter(file, true);
                // Writes the content to the file
                writer.write(jsonObjectString);
                writer.flush();
                writer.close();
            } catch (IOException e) {
                e.printStackTrace();
            }
        } else {
            writeToJson(path, jsonObjectString);
        }
        return file;
    }
    //* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
}
