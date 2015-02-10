package com.vcelicky.smog.activities;

import android.app.AlertDialog;
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

        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle(getString(R.string.send_photos));
        builder.setMessage(getString(R.string.not_yet_send));
        builder.setCancelable(false);

        builder.setPositiveButton("Áno", new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog, int id) {
                // User clicked OK button
                dialog.dismiss();
                List<Photo> photoList;
                photoList = (ArrayList)deserializeList();

                //move all the photos taken in offline mode to the main directory
                if(!FileUtils.isUploadDirectoryEmpty()) {
                    File files[] = FileUtils.getUploadDirectory().listFiles();
                    for(File f : files) {
                        String fileName = f.getName();
                        f.renameTo(new File(FileUtils.getMainDirectory() + File.separator + fileName));
                    }
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
                if(SerializationUtils.serializedFileExists(AlreadyOnlineActivity.this, Strings.SERIALIZED_LIST)) {
                    deleteFile(Strings.SERIALIZED_LIST);
                }
                finish();
            }
        });

        AlertDialog dialog = builder.create();
        dialog.show();
    }

    private class UploadPhotoCompleteListener implements AsyncTaskCompleteListener {
        @Override
        public void onTaskComplete() {
            deleteFile(Strings.SERIALIZED_LIST);
            finish();
        }
    }
}
