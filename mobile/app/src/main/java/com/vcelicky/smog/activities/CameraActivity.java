package com.vcelicky.smog.activities;

import android.content.Intent;
import android.content.pm.PackageManager;
import android.hardware.Camera;
import android.hardware.Camera.PictureCallback;
import android.os.Bundle;
import android.util.Base64;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.FrameLayout;
import android.widget.Toast;

import com.vcelicky.smog.AsyncTaskCompleteListener;
import com.vcelicky.smog.models.Photo;
import com.vcelicky.smog.tasks.UploadPhotoTask;
import com.vcelicky.smog.utils.FileUtils;
import com.vcelicky.smog.utils.SerializationUtils;
import com.vcelicky.smog.utils.Strings;
import com.vcelicky.smog.views.CameraPreview;
import com.vcelicky.smog.R;
import com.vcelicky.smog.abs.BaseActivity;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

/**
 * Created by jerry on 10. 10. 2014.
 */
public class CameraActivity extends BaseActivity implements View.OnClickListener{
    private static final String TAG = CameraActivity.class.getSimpleName();
    public static final int MEDIA_TYPE_COMPRESSED = 2; //BASE64

    public static List<Photo> photoList = new ArrayList<Photo>();
    private Camera mCamera;
    private CameraPreview mPreview;
    private FrameLayout mFramePreview;
    private File mCompressedFile;
    private boolean isWifiOrMobileOn;
    private boolean isPreviewStopped;

    private Button captureButton;
    private Button uploadButton;
    private Button addButton;
    private Button testButton;

    public static Photo currentPhoto;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        Log.d(TAG, "onCreate()");
        //onResume() is called now
    }

    @Override
    protected void onStart() {
        super.onStart();
        Log.d(TAG, "onStart()");

    }

    @Override
    protected void onStop() {
        super.onStop();
//        serializePhotoList();
        Log.d(TAG, "onStop()");
        mLocationManager.removeUpdates(this);
    }

    @Override
    protected void onPause() {
        super.onPause();
        releaseCamera();
        Log.d(TAG, "onPause()");
    }

    @Override
    protected void onResume() {
        super.onResume();
        Log.d(TAG, "onResume()");
        log(TAG, "photoList size = " + photoList.size());
        if(!checkCameraHardware()) finish();
        if(SerializationUtils.serializedFileExists(this, Strings.SERIALIZED_LIST)) {
            photoList = (ArrayList)deserializeList();
        }
        setContentView(R.layout.activity_camera);
        //Create an instance of Camera
        mCamera = getCameraInstance();
//        setCameraParams();
        if(FileUtils.isUploadDirectoryEmpty()) log(TAG, "Yes, it's empty");
        else log(TAG, "There's something");

        setPreviews();
        initListeners();
        checkNetworkStatus();
        requestLocationUpdate();
    }

    private void setCameraParams() {
        Camera.Parameters params;
        params = mCamera.getParameters();
        params.setFlashMode(Camera.Parameters.FLASH_MODE_AUTO);
        params.setFocusMode(Camera.Parameters.FOCUS_MODE_CONTINUOUS_PICTURE);
        params.setSceneMode(Camera.Parameters.SCENE_MODE_AUTO);
        params.setWhiteBalance(Camera.Parameters.WHITE_BALANCE_AUTO);
        params.setExposureCompensation(0);
        params.setJpegQuality(100);

        List<Camera.Size> sizes = params.getSupportedPictureSizes();
        Camera.Size size = sizes.get(0);
        params.setPictureSize(size.width, size.height);
        mCamera.setParameters(params);
    }

    private void setPreviews() {
        //Create our Preview and set it as the content of our activity
        mPreview = new CameraPreview(this, mCamera);
        mFramePreview = (FrameLayout) findViewById(R.id.camera_preview);
        mFramePreview.addView(mPreview);
    }

    @Override
    public void onClick(View view) {
        if(view.getId() == R.id.button_capture) {
            if(isPreviewStopped) {
                mCamera.startPreview();
                captureButton.setBackgroundResource(R.drawable.capture_selector);
                uploadButton.setVisibility(View.GONE);
                addButton.setVisibility(View.GONE);
                isPreviewStopped = false;
            } else {
                //get an image from the camera; here the user gets first time after taking photo
                if(mCurrentLocation != null) {
                    mCamera.takePicture(null, null, mPicture);
                    isPreviewStopped = true;
                    captureButton.setBackgroundResource(R.drawable.refresh_selector);
                    uploadButton.setVisibility(View.VISIBLE);
                    addButton.setVisibility(View.VISIBLE);
                } else {
                    toastLong("Zatiaľ sa nepodarilo získať Vaše GPS súradnice. Skúste to opäť o chvíľu, prípadne zapnite GPS vo Vašom telefóne.");
                }
            }
        } else if(view.getId() == R.id.button_upload) {
            if(isWifiOrMobileConnected(CameraActivity.this)) {
                //photo uploads; button_upload is being showed ONLY after photo has been taken, so the photo surely exists
                log(TAG, "uploading photo(s)...");
                new UploadPhotoTask(CameraActivity.this, new UploadPhotoCompleteListener()).execute(currentPhoto);
            } else {
                //save photo to the ArrayList and notify user about uploading photo next time he connects to the internet
                photoList.add(currentPhoto);
                serializeList(photoList);
                toastLong("Momentálne nie ste pripojený. Vaša fotka sa uložila a odoslať ju budete môcť pri najbližšom pripojení na internet.");
            }

        } else if(view.getId() == R.id.button_add) {
//            toastLong("Na funkcii pridávania dodatočných informácií o fotografii sa pracuje.");
            Intent intent = new Intent(CameraActivity.this, AdditionalnfoActivity.class);
            startActivity(intent);
        }
    }

    /**
     * Initializes all the listeners used in this activity.
     */
    private void initListeners() {
        isPreviewStopped = false;
        captureButton = (Button) findViewById(R.id.button_capture);
        captureButton.setOnClickListener(this);
        uploadButton = (Button) findViewById(R.id.button_upload);
        uploadButton.setOnClickListener(this);
        addButton = (Button) findViewById(R.id.button_add);
        addButton.setOnClickListener(this);
    }

    private void deserializeTest() {
        try {
            List<Photo> testList;
            Log.d(TAG, "pred inicializovanim fis");
            FileInputStream fis = this.openFileInput(Strings.SERIALIZED_LIST);
            Log.d(TAG, "po inicizliaovani fis");
            testList = (ArrayList)SerializationUtils.deserialize(fis);
            photoList = testList;
            toastLong("Size = " + testList.size());
//            Log.d(TAG, "Size = " + testList.size() + " " + testList.get(0).getLatitude());
            fis.close();
        } catch (FileNotFoundException e) {
            // if internal serialized file has already been removed before...
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    /**
     * Checks whether a device has got a camera inside.
     * @return true if does, false otherwise
     */
    private boolean checkCameraHardware() {
        if(getPackageManager().hasSystemFeature(PackageManager.FEATURE_CAMERA)) {
            //this device has a camera
            return true;
        } else {
            //no camera on this device
            return false;
        }
    }

    /**
     * Release the camera when it's not used anymore or Activity that uses it is paused.
     */
    private void releaseCamera() {
        if (mCamera != null) {
            mPreview.getHolder().removeCallback(mPreview);
            mCamera.release();
            mCamera = null;
        }
    }

    /**
     * Initializes Camera object by opening Camera.
     * @return if success, then initialized Camera
     */
    public static Camera getCameraInstance() {
        Camera c = null;
        try {
            c = Camera.open();
        } catch (Exception e) {
            // Camera is not available (in use or does not exist)
        }
        return c; //returns null if camera is unavailable
    }

    private PictureCallback mPicture = new PictureCallback() {

        @Override
        public void onPictureTaken(byte[] bytes, Camera camera) {

            mCompressedFile = FileUtils.getOutputMediaFile(MEDIA_TYPE_COMPRESSED, isWifiOrMobileOn);
            Log.d(TAG, mCompressedFile.getAbsolutePath());
            if(mCompressedFile == null) {
                Log.d(TAG, "Error creating media file, check storage permissions!");
                return;
            }

            //Fill the file with image/video bytes
            try {
                //Transform to Base64 file
                String imageDataString = Base64.encodeToString(bytes, Base64.DEFAULT);
                byte[] imageByteArray = Base64.decode(imageDataString, Base64.DEFAULT);

                //Create Base64 image
                FileOutputStream fos = new FileOutputStream(mCompressedFile);
                fos.write(imageByteArray);
                fos.close();

                currentPhoto = new Photo();
//                currentPhoto.setPath(mCompressedFile.getAbsolutePath());
                currentPhoto.setImageByteArray(imageByteArray);

                Toast.makeText(CameraActivity.this,
                                "Latitude = "
                                + String.valueOf(mCurrentLocation.getLatitude())
                                + "; longitude = "
                                + String.valueOf(mCurrentLocation.getLongitude()), Toast.LENGTH_SHORT).show();
                currentPhoto.setLatitude(mCurrentLocation.getLatitude());
                currentPhoto.setLongitude(mCurrentLocation.getLongitude());

            } catch (FileNotFoundException e) {
                Log.d(TAG, "File not found: " + e.getMessage());
            } catch (IOException e) {
                Log.d(TAG, "Error accessing file: " + e.getMessage());
            }
        }
    };

    private void checkNetworkStatus() {
        isWifiOrMobileOn = isWifiOrMobileConnected(this);
    }

    private class UploadPhotoCompleteListener implements AsyncTaskCompleteListener {
        @Override
        public void onTaskComplete() {
            Log.d(TAG, "onTaskComplete, mehehe");
        }
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        return super.onCreateOptionsMenu(menu);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();
        switch(id) {
            case R.id.action_settings:
                deserializeTest();
            default:
                return super.onOptionsItemSelected(item);
        }
    }
}
