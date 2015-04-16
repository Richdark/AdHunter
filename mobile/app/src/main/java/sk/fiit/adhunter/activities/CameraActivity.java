package sk.fiit.adhunter.activities;

import android.content.Intent;
import android.content.pm.PackageManager;
import android.hardware.Camera;
import android.hardware.Camera.PictureCallback;
import android.location.Location;
import android.media.AudioManager;
import android.media.SoundPool;
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
import android.widget.ProgressBar;
import android.widget.TextView;

import retrofit.mime.TypedByteArray;
import retrofit.mime.TypedString;
import sk.fiit.adhunter.models.CurrentPhoto;
import sk.fiit.adhunter.models.Photo;
import sk.fiit.adhunter.models.User;
import sk.fiit.adhunter.services.io.GetUploadResponse;
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
public class CameraActivity extends BaseActivity implements View.OnClickListener {
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
    private LinearLayout mAddressLayout, mLoadingGPSLayout;
    private TextView mAddressText, mLatitude, mLongitude, mRefreshInterval;
//    private TextView mLatitude, mLongitude;
    private Button mLogOutButton, mSettingsButton;
    private CurrentPhoto mCurrentPhoto;

    private SoundPool mSoundPool;
    private int mSoundId;

    private ProgressBar mGPSProgressBar;

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
        if(!isGPSEnabled()) {
            showGPSAlert();
        }

        mSoundPool = new SoundPool(3, AudioManager.STREAM_NOTIFICATION, 0); // STREAM_NOTIFICATION !!!
        mSoundId = mSoundPool.load(this, R.raw.snapshot, 1);

    }

    private void setPreviews() {
        //Create our Preview and set it as the content of our activity
        mPreview = new CameraPreview(this, mCamera);
        FrameLayout framePreview = (FrameLayout) findViewById(R.id.camera_preview);
        framePreview.addView(mPreview);
    }

    /**
     * Initializes all the views and listeners used in this activity.
     */
    private void initViews() {
        isPreviewStopped = false;

        mCaptureButton = (ImageButton) findViewById(R.id.button_capture);
        mCaptureButton.setOnClickListener(this);
        mUploadButton = (ImageButton) findViewById(R.id.button_upload);
        mUploadButton.setOnClickListener(this);
        mAddButton = (ImageButton) findViewById(R.id.button_add);
        mAddButton.setOnClickListener(this);
//        mLogOutButton = (Button) findViewById(R.id.button_logout);
//        mLogOutButton.setOnClickListener(this);
//        mSettingsButton = (Button) findViewById(R.id.button_settings);
//        mSettingsButton.setOnClickListener(this);
//        mAddressLayout = (LinearLayout) findViewById(R.id.address_layout);
//        mAddressText = (TextView) findViewById(R.id.address_text);

        mLatitude = (TextView) findViewById(R.id.Activity_Camera_latitude);
        mLongitude = (TextView) findViewById(R.id.Activity_Camera_longitude);
//        mRefreshInterval = (TextView) findViewById(R.id.Activity_Camera_refreshInterval);
//
        mLoadingGPSLayout = (LinearLayout) findViewById(R.id.loading_gps_layout);
        mGPSProgressBar = (ProgressBar) findViewById(R.id.Activity_Camera_loadingGPSProgressBar);
    }

    @Override
    public void onClick(View view) {

        int id = view.getId();

        switch (id) {
            case R.id.button_capture:
                if(isPreviewStopped) {
                    mCamera.startPreview();

                    mCaptureButton.setBackgroundResource(R.drawable.circle_selector);
                    mCaptureButton.setImageResource(R.drawable.ic_image_camera_alt);
                    mUploadButton.setAnimation(createAnimation(android.R.anim.fade_out));
                    mUploadButton.setVisibility(View.GONE);
                    mAddButton.setAnimation(createAnimation(android.R.anim.fade_out));
                    mAddButton.setVisibility(View.GONE);
                    isPreviewStopped = false;

                } else {
                    //get an image from the camera; here the user gets first time after taking photo
                    if(mLocation != null) {

                        mCamera.takePicture(null, null, mPicture);
                        isPreviewStopped = true;
                        playCameraSound();

                        mCaptureButton.setBackgroundResource(R.drawable.circle_selector);
                        mCaptureButton.setImageResource(R.drawable.ic_av_replay);

                        mUploadButton.setAnimation(createAnimation(android.R.anim.fade_in));
                        mUploadButton.setVisibility(View.VISIBLE);
                        mAddButton.setAnimation(createAnimation(android.R.anim.fade_in));
                        mAddButton.setVisibility(View.VISIBLE);

                    } else {
                        toastLong(getString(R.string.gps_not_found));
                    }
                }
                break;

            case R.id.button_upload:
                if(isConnected()) {
                    showProgressDialog(this, "Vaša fotka sa práve odosiela na server...");
                    //photo uploads; button_upload is being showed ONLY after photo has been taken, so the photo surely exists
                    getServiceInterface().uploadPhoto(new TypedByteArray("image/jpeg", mCurrentPhoto.getImageByteArray()),
                            new TypedString(String.valueOf(mCurrentPhoto.getLatitude())),
                            new TypedString(String.valueOf(mCurrentPhoto.getLongitude())),
                            new TypedString(mCurrentPhoto.getComment()),
                            new TypedString(mCurrentPhoto.getBillboardType()),
                            new TypedString(mCurrentPhoto.getOwner()),
                            uploadResponse);
                } else {

                    if (isLocationTheSameAsPrevious(mCurrentPhoto.getLatitude(), mCurrentPhoto.getLongitude())) {
                        toastShort("Chyba: Vaše súradnice sú zhodné so súradnicami poslednej fotky!");
                        return;
                    }

                    //save photo to the ArrayList and notify user about uploading photo next time he connects to the internet
                    sPhotoList.add(mCurrentPhoto);
                    serializeList(sPhotoList);
                    toastLong(getString(R.string.not_connected));
                }
                break;

            case R.id.button_add:
                Intent intent = new Intent(CameraActivity.this, AdditionalInfoActivity.class);
                startActivity(intent);
                finish();
                break;

//            case R.id.button_logout:
//                logoutUser();
//                break;
//
//            case R.id.button_settings:
//                startActivity(new Intent(CameraActivity.this, SettingsActivity.class));
//                break;

            default:
                break;

        }

    }

    public boolean isLocationTheSameAsPrevious(double latitude, double longitude) {
        int sizeOfList = sPhotoList.size();
        return sizeOfList > 0 && sPhotoList.get(sizeOfList - 1).getLatitude() == latitude
                && sPhotoList.get(sizeOfList - 1).getLongitude() == longitude;
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
            toastLong("Počet neodoslaných fotiek = " + testList.size());
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

            // Fill the file with image/video bytes
            try {
                //Transform to Base64 file
                String imageDataString = Base64.encodeToString(bytes, Base64.DEFAULT);
                byte[] imageByteArray = Base64.decode(imageDataString, Base64.DEFAULT);

                //Create Base64 image
                FileOutputStream fos = new FileOutputStream(compressedFile);
                fos.write(imageByteArray);
                fos.close();

                CurrentPhoto.getInstance().clearInstance();
                mCurrentPhoto = CurrentPhoto.getInstance();
                mCurrentPhoto.setImageByteArray(imageByteArray);
                mCurrentPhoto.setLatitude(mLocation.getLatitude());
                log(TAG, "latitude right after = " + mLocation.getLatitude());
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

    private Callback<GetUploadResponse> uploadResponse = new Callback<GetUploadResponse>() {
        @Override
        public void success(GetUploadResponse getUploadResponse, Response response2) {
            dismissProgressDialog();
            toastShort(getUploadResponse.status);
            mCamera.startPreview();
        }

        @Override
        public void failure(RetrofitError error) {
            toastLong(error.getMessage());
        }
    };

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
//        return super.onCreateOptionsMenu(menu);
        super.onCreateOptionsMenu(menu);
        getMenuInflater().inflate(R.menu.base, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();
        switch(id) {
            case R.id.action_number_of_photos_not_sent:
                deserializeTest();
                return true;
            case R.id.action_settings:
                startActivity(new Intent(CameraActivity.this, SettingsActivity.class));
                return true;
            case R.id.action_logout:
                logoutUser();
                return true;
            default:
                return super.onOptionsItemSelected(item);
        }
    }

    public void playCameraSound() {
        mSoundPool.play(mSoundId, 1.0f, 1.0f, 1, 0, 1);
    }

    @Override
    public void onLocationChanged(Location location) {
        super.onLocationChanged(location);

        if(mLoadingGPSLayout != null && isCorrectGPS) {
            if(mLoadingGPSLayout.getVisibility() == View.VISIBLE) {
                mGPSProgressBar.setVisibility(View.GONE);
                mLoadingGPSLayout.setAnimation(createAnimation(android.R.anim.slide_out_right));
                mLoadingGPSLayout.setVisibility(View.GONE);

                mLatitude.setText("latitude: " + String.valueOf(mLocation.getLatitude()));
                mLatitude.startAnimation(createAnimation(android.R.anim.slide_in_left));
                mLatitude.setVisibility(View.VISIBLE);

                mLongitude.setText("longitude: " + String.valueOf(mLocation.getLongitude()));
                mLongitude.startAnimation(createAnimation(android.R.anim.slide_in_left));
                mLongitude.setVisibility(View.VISIBLE);

            }
        }
//
        if(mLatitude != null && mLongitude != null) { //  && mRefreshInterval != null
            if(mLocation != null) {
                mLatitude.setText("latitude: " + String.valueOf(mLocation.getLatitude()));
                mLongitude.setText("longitude: " + String.valueOf(mLocation.getLongitude()));
//                mRefreshInterval.setText("refresh interval: " + mTimeDifference);
            }
        }
    }

}
