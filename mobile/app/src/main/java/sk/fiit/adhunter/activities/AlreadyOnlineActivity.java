package sk.fiit.adhunter.activities;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;

import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;
import retrofit.mime.TypedByteArray;
import retrofit.mime.TypedString;
import sk.fiit.adhunter.AsyncTaskCompleteListener;
import sk.fiit.adhunter.R;
import sk.fiit.adhunter.abs.BaseActivity;
import sk.fiit.adhunter.models.Photo;
import sk.fiit.adhunter.services.io.GetUploadResponse;
import sk.fiit.adhunter.tasks.UploadPhotoTask;
import sk.fiit.adhunter.utils.Config;
import sk.fiit.adhunter.utils.FileUtils;
import sk.fiit.adhunter.utils.SerializationUtils;
import sk.fiit.adhunter.utils.Strings;

import java.io.File;
import java.util.ArrayList;
import java.util.List;

/**
 * Created by jerry on 5. 11. 2014.
 */
public class AlreadyOnlineActivity extends BaseActivity {
    private static final String TAG = AlreadyOnlineActivity.class.getSimpleName();

    private List<Photo> mPhotoList;
    private int currentPhotoBeingSent = 0;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_online);
        Log.d(TAG, "Started");

        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle("Odoslať fotky");
        builder.setMessage("Niekoľko Vami odfotených reklám zatiaľ nebolo odoslaných z dôvodu " +
                "chýbajúceho pripojenia na internet. Chcete ich teraz odoslať?");
        builder.setCancelable(false);
        // Add the buttons
        builder.setPositiveButton("Ano", new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog, int id) {
                // User clicked OK button
                Log.d(TAG, "YES");
                dialog.dismiss();
                mPhotoList = (ArrayList)deserializeList();
                Log.d(TAG, "Pocet fotiek: " + mPhotoList.size());

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
                if (mPhotoList != null && mPhotoList.size() > 0) {
                    uploadMultiplePhotos();
                }

//                new UploadPhotoTask(AlreadyOnlineActivity.this,
//                        new UploadPhotoCompleteListener())
//                        .execute(mPhotoList.toArray(new Photo[mPhotoList.size()]));
            }
        });
        builder.setNegativeButton("Nie", new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog, int id) {
                // User cancelled the dialog
                Log.d(TAG, "NO");
                if(SerializationUtils.serializedFileExists(AlreadyOnlineActivity.this, Strings.SERIALIZED_LIST)) {
                    deleteFile(Strings.SERIALIZED_LIST);
                    Log.d(TAG, "file Strings.SERIALIZED_LIST was removed");
                }
                finish();
            }
        });

        // Create the AlertDialog
        AlertDialog dialog = builder.create();
        dialog.show();
    }

    public void uploadMultiplePhotos() {

        showProgressDialog(this, "Vaše úlovky sa práve odosielajú na server...", DIALOG_HORIZONTAL, mPhotoList.size());

        getServiceInterface().uploadPhoto(new TypedByteArray("image/jpeg", mPhotoList.get(currentPhotoBeingSent).getImageByteArray()),
                new TypedString(String.valueOf(mPhotoList.get(currentPhotoBeingSent).getLatitude())),
                new TypedString(String.valueOf(mPhotoList.get(currentPhotoBeingSent).getLongitude())),
                new TypedString(Config.DEVICE_ID),
                new TypedString(mPhotoList.get(currentPhotoBeingSent).getComment()),
                new TypedString(mPhotoList.get(currentPhotoBeingSent).getBillboardType()),
                new TypedString(mPhotoList.get(currentPhotoBeingSent).getOwner()),
                new TypedString(Build.MODEL),
                uploadResponse);

        log(TAG, "BILLBOARD-OWNER = " + mPhotoList.get(currentPhotoBeingSent).getOwner());
        log(TAG, "LATITUDE = " + mPhotoList.get(currentPhotoBeingSent).getLatitude());

    }

    private Callback<GetUploadResponse> uploadResponse = new Callback<GetUploadResponse>() {
        @Override
        public void success(GetUploadResponse getUploadResponse, Response response) {

            if (getUploadResponse.status.equals("ok")) {
                try {
                    log(TAG, (currentPhotoBeingSent + 1) + ". fotka OK");

                    currentPhotoBeingSent++;
                    if (currentPhotoBeingSent >= mPhotoList.size()) {
                        deleteFile(Strings.SERIALIZED_LIST);
                        dismissProgressDialog();
                        toastLong("Na server bolo úspešne nahratých " + currentPhotoBeingSent + " fotiek!");
                        finish();
                        // avoiding index out of bounds, upload finished
                        return;
                    }
                    log(TAG, "BILLBOARD-OWNER = " + mPhotoList.get(currentPhotoBeingSent).getOwner());
                    log(TAG, "LATITUDE = " + mPhotoList.get(currentPhotoBeingSent).getLatitude());
                    updateProgressDialog(currentPhotoBeingSent);

                    Thread.sleep(100);
                    getServiceInterface().uploadPhoto(new TypedByteArray("image/jpeg", mPhotoList.get(currentPhotoBeingSent).getImageByteArray()),
                            new TypedString(String.valueOf(mPhotoList.get(currentPhotoBeingSent).getLatitude())),
                            new TypedString(String.valueOf(mPhotoList.get(currentPhotoBeingSent).getLongitude())),
                            new TypedString(Config.DEVICE_ID),
                            new TypedString(mPhotoList.get(currentPhotoBeingSent).getComment()),
                            new TypedString(mPhotoList.get(currentPhotoBeingSent).getBillboardType()),
                            new TypedString(mPhotoList.get(currentPhotoBeingSent).getOwner()),
                            new TypedString(Build.MODEL),
                            uploadResponse);

                } catch (InterruptedException e) {
                    e.printStackTrace();
                    toastShort("Chyba pri posielani " + currentPhotoBeingSent + ". fotky!");
                }
            }

        }

        @Override
        public void failure(RetrofitError error) {
            toastLong(error.getMessage());
        }
    };

    private class UploadPhotoCompleteListener implements AsyncTaskCompleteListener {
        @Override
        public void onTaskComplete() {
            Log.d(TAG, "OFFLINE photos successfully uploaded! :)");
            deleteFile(Strings.SERIALIZED_LIST);
            finish();
        }
    }
}
