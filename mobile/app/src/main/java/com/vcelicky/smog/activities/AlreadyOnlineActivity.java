package com.vcelicky.smog.activities;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.os.Bundle;
import android.util.Log;

import com.vcelicky.smog.AsyncTaskCompleteListener;
import com.vcelicky.smog.R;
import com.vcelicky.smog.abs.BaseActivity;
import com.vcelicky.smog.models.Photo;
import com.vcelicky.smog.tasks.UploadPhotoTask;
import com.vcelicky.smog.utils.FileUtils;
import com.vcelicky.smog.utils.SerializationUtils;
import com.vcelicky.smog.utils.Strings;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.util.ArrayList;
import java.util.List;

/**
 * Created by jerry on 5. 11. 2014.
 */
public class AlreadyOnlineActivity extends BaseActivity {
    private static final String TAG = AlreadyOnlineActivity.class.getSimpleName();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_online);
        Log.d(TAG, "Started");

        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle("Odoslať fotky");
        builder.setMessage("Niekoľko Vami odfotených reklám zatiaľ nebolo odoslaných z dôvodu " +
                "chýbajúceho pripojenia na internet. Chcete ich teraz odoslať?");
        // Add the buttons
        builder.setPositiveButton("Ano", new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog, int id) {
                // User clicked OK button
                Log.d(TAG, "YES");
                dialog.dismiss();
                List<Photo> photoList;
                photoList = (ArrayList)deserializeList();
                Log.d(TAG, "Pocet fotiek: " + photoList.size());

                //move all the photos taken in offline mode to the main directory
                if(!FileUtils.isUploadDirectoryEmpty()) {
                    File files[] = FileUtils.getUploadDirectory().listFiles();
                    for(File f : files) {
                        String fileName = f.getName();
                        log(TAG, "fileName = " + fileName);
                        f.renameTo(new File(FileUtils.getMainDirectory() + File.separator + fileName));
                    }
                    log(TAG, "all the files have been successfully moved to the main directory");
                }
                //photos upload
                new UploadPhotoTask(AlreadyOnlineActivity.this,
                        new UploadPhotoCompleteListener())
                        .execute(photoList.toArray(new Photo[photoList.size()]));
            }
        });
        builder.setNegativeButton("Nie", new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog, int id) {
                // User cancelled the dialog
                Log.d(TAG, "NO");
                if(deserializedFileExists(Strings.SERIALIZED_LIST)) {
                    getApplicationContext().deleteFile(Strings.SERIALIZED_LIST);
                    Log.d(TAG, "file Strings.SERIALIZED_LIST was removed");
                }
                finish();
            }
        });

        // Create the AlertDialog
        AlertDialog dialog = builder.create();
        dialog.show();
    }

    private class UploadPhotoCompleteListener implements AsyncTaskCompleteListener {
        @Override
        public void onTaskComplete() {
            Log.d(TAG, "OFFLINE photos successfully uploaded! :)");
            finish();
        }
    }

}
