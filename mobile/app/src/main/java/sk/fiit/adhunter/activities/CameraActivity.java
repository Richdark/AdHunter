package sk.fiit.adhunter.activities;

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
import android.widget.ImageButton;
import android.widget.LinearLayout;
import android.widget.TextView;

import retrofit.mime.TypedByteArray;
import retrofit.mime.TypedString;
import sk.fiit.adhunter.AsyncTaskCompleteListener;
import sk.fiit.adhunter.models.CurrentPhoto;
import sk.fiit.adhunter.models.Photo;
import sk.fiit.adhunter.models.User;
import sk.fiit.adhunter.utils.Config;
import sk.fiit.adhunter.utils.FileUtils;
import sk.fiit.adhunter.utils.SerializationUtils;
import sk.fiit.adhunter.utils.Strings;
import sk.fiit.adhunter.views.CameraPreview;
import sk.fiit.adhunter.R;
import sk.fiit.adhunter.abs.BaseActivity;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;

/**
 * Created by jerry on 10. 10. 2014.
 */
public class CameraActivity extends BaseActivity implements View.OnClickListener, Callback<Response> {
    private static final String TAG = CameraActivity.class.getSimpleName();
    public static final int MEDIA_TYPE_COMPRESSED = 2; //BASE64

    public static List<Photo> sPhotoList = new ArrayList<Photo>();
    private Camera mCamera;
    private CameraPreview mPreview;
    private boolean isWifiOrMobileOn;
    private boolean isPreviewStopped;

    private ImageButton mCaptureButton;
    private ImageButton mUploadButton;
    private ImageButton mAddButton;
    private LinearLayout mAddressLayout;
    private TextView mAddressText;
    private Button mLogOutButton;
    private CurrentPhoto mCurrentPhoto;

    private int numberOfFailures = 0;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }

    @Override
    protected void onStart() {
        super.onStart();
    }

    @Override
    protected void onStop() {
        super.onStop();
    }

    @Override
    protected void onPause() {
        super.onPause();
        releaseCamera();
    }

    @Override
    protected void onResume() {
        super.onResume();

        if(!checkCameraHardware()) finish();
        if(SerializationUtils.serializedFileExists(this, Strings.SERIALIZED_LIST)) {
            sPhotoList = (ArrayList)deserializeList();
        }
        setContentView(R.layout.activity_camera);

        mCamera = getCameraInstance();
        setPreviews();
        initViews();
        checkNetworkStatus();
    }

    private void setPreviews() {
        //Create our Preview and set it as the content of our activity
        mPreview = new CameraPreview(this, mCamera);
        FrameLayout framePreview = (FrameLayout) findViewById(R.id.camera_preview);
        framePreview.addView(mPreview);
    }

    /**
     * Initializes all the views used in this activity.
     */
    private void initViews() {
        isPreviewStopped = false;
        mCaptureButton = (ImageButton) findViewById(R.id.button_capture);
        mCaptureButton.setOnClickListener(this);
        mUploadButton = (ImageButton) findViewById(R.id.button_upload);
        mUploadButton.setOnClickListener(this);
        mAddButton = (ImageButton) findViewById(R.id.button_add);
        mAddButton.setOnClickListener(this);
        mLogOutButton = (Button) findViewById(R.id.button_logout);
        mLogOutButton.setOnClickListener(this);
        mAddressLayout = (LinearLayout) findViewById(R.id.address_layout);
        mAddressText = (TextView) findViewById(R.id.address_text);
    }

    @Override
    public void onClick(View view) {

        if(view.getId() == R.id.button_capture) {
            if(isPreviewStopped) {
                mCamera.startPreview();

                mCaptureButton.setBackgroundResource(R.drawable.circle_selector);
                mCaptureButton.setImageResource(R.drawable.ic_image_camera_alt);
                mUploadButton.setVisibility(View.GONE);
                mAddButton.setVisibility(View.GONE);
                mAddressLayout.setVisibility(View.GONE);
                isPreviewStopped = false;
            } else {
                //get an image from the camera; here the user gets first time after taking photo
                if(mLocation != null) {
                    mCamera.takePicture(null, null, mPicture);
                    isPreviewStopped = true;

                    mCaptureButton.setBackgroundResource(R.drawable.circle_selector);
                    mCaptureButton.setImageResource(R.drawable.ic_av_replay);

                    mUploadButton.setVisibility(View.VISIBLE);
                    mAddButton.setVisibility(View.VISIBLE);

                } else {
                    toastLong(getString(R.string.gps_not_found));
                }
            }
        } else if(view.getId() == R.id.button_upload) {
            if(isWifiOrMobileConnected(CameraActivity.this)) {
                //photo uploads; button_upload is being showed ONLY after photo has been taken, so the photo surely exists
//                new UploadPhotoTask(CameraActivity.this, new UploadPhotoCompleteListener()).execute(mCurrentPhoto);
                getServiceInterface().uploadPhoto(new TypedByteArray("image/jpeg", mCurrentPhoto.getImageByteArray()),
                        new TypedString(String.valueOf(mCurrentPhoto.getLatitude())),
                        new TypedString(String.valueOf(mCurrentPhoto.getLongitude())),
                        new TypedString(mCurrentPhoto.getComment()),
                        new TypedString(mCurrentPhoto.getBillboardType()),
                        this);
            } else {
                //save photo to the ArrayList and notify user about uploading photo next time he connects to the internet
                sPhotoList.add(mCurrentPhoto);
                serializeList(sPhotoList);
                toastLong(getString(R.string.not_connected));
            }

        } else if(view.getId() == R.id.button_add) {
            Intent intent = new Intent(CameraActivity.this, AdditionalnfoActivity.class);
            startActivity(intent);
            finish();
        } else if(view.getId() == R.id.button_logout) {
            logoutUser();
        }

    }

    private void logoutUser() {
        getServiceInterface().logoutUser(Config.DEVICE_ID, new Callback<Response>() {
            @Override
            public void success(Response response, Response response2) {
                User.deleteApplicationDirectory();
                Intent i = new Intent(CameraActivity.this, LoginActivity.class);
                startActivity(i);
                finish();
            }

            @Override
            public void failure(RetrofitError error) {
                numberOfFailures++;
                if(numberOfFailures > 3) {
                    toastShort(getString(R.string.logout_failed));
                    return;
                }

                try {
                    Thread.sleep(200);
                    getServiceInterface().logoutUser(Config.DEVICE_ID, this);
                } catch (InterruptedException e) {
                    e.printStackTrace();
                }
            }
        });
    }

    private void deserializeTest() {
        try {
            List<Photo> testList;
            FileInputStream fis = this.openFileInput(Strings.SERIALIZED_LIST);
            testList = (ArrayList)SerializationUtils.deserialize(fis);
            sPhotoList = testList;
            toastLong("Size = " + testList.size());
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
            return true;
        } else {
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
            e.printStackTrace();
        }
        return c;
    }

    private PictureCallback mPicture = new PictureCallback() {

        @Override
        public void onPictureTaken(byte[] bytes, Camera camera) {
            mCamera.stopPreview();
            File compressedFile = FileUtils.getOutputMediaFile(MEDIA_TYPE_COMPRESSED, isWifiOrMobileOn);
            if(compressedFile == null) {
                toastShort("Error creating media file, check storage permissions!");
                return;
            }

            //Fill the file with image/video bytes
            try {
                //Transform to Base64 file
                String imageDataString = Base64.encodeToString(bytes, Base64.DEFAULT);
                byte[] imageByteArray = Base64.decode(imageDataString, Base64.DEFAULT);

                //Create Base64 image
                FileOutputStream fos = new FileOutputStream(compressedFile);
                fos.write(imageByteArray);
                fos.close();

                mCurrentPhoto = CurrentPhoto.getInstance();
                mCurrentPhoto.setImageByteArray(imageByteArray);
                mCurrentPhoto.setLatitude(mLocation.getLatitude());
                mCurrentPhoto.setLongitude(mLocation.getLongitude());

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

    @Override
    public void success(Response response, Response response2) {
        toastShort(Strings.parseHtmlResponse(response, "h1"));
        mCamera.startPreview();
    }

    @Override
    public void failure(RetrofitError error) {
        log(TAG, "failure = " + error.getMessage());
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
