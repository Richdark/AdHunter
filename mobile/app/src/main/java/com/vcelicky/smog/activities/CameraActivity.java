package com.vcelicky.smog.activities;

import android.content.Intent;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Matrix;
import android.graphics.Point;
import android.hardware.Camera;
import android.hardware.Camera.PictureCallback;
import android.location.Address;
import android.location.Geocoder;
import android.media.ExifInterface;
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
import android.widget.Toast;

import com.vcelicky.smog.AsyncTaskCompleteListener;
import com.vcelicky.smog.models.Photo;
import com.vcelicky.smog.models.User;
import com.vcelicky.smog.tasks.UploadPhotoTask;
import com.vcelicky.smog.utils.Config;
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
import java.util.Locale;

import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;

/**
 * Created by jerry on 10. 10. 2014.
 */
public class CameraActivity extends BaseActivity implements View.OnClickListener{
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

    public static Photo mCurrentPhoto;

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
        Log.d(TAG, "onPause()");
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
        initListeners();
        checkNetworkStatus();
    }

    private void setPreviews() {
        //Create our Preview and set it as the content of our activity
        mPreview = new CameraPreview(this, mCamera);
        FrameLayout framePreview = (FrameLayout) findViewById(R.id.camera_preview);
        framePreview.addView(mPreview);
    }

    /**
     * Initializes all the listeners used in this activity.
     */
    private void initListeners() {
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

                    if(isWifiOrMobileConnected(CameraActivity.this)) {
                        Geocoder geo = new Geocoder(CameraActivity.this, Locale.getDefault());
                        try {
                            List<Address> addresses = geo.getFromLocation(mLocation.getLatitude(), mLocation.getLongitude(), 1);
                            mAddressLayout.startAnimation(loadAnimation(android.R.anim.fade_in));
                            mAddressLayout.setVisibility(View.VISIBLE);
                            mAddressText.setText(addresses.get(0).getAddressLine(0));
                        } catch (IOException e) {
                            e.printStackTrace();
                        }
                    }

                } else {
                    toastLong(getString(R.string.gps_not_found));
                }
            }
        } else if(view.getId() == R.id.button_upload) {
            if(isWifiOrMobileConnected(CameraActivity.this)) {
                //photo uploads; button_upload is being showed ONLY after photo has been taken, so the photo surely exists
                new UploadPhotoTask(CameraActivity.this, new UploadPhotoCompleteListener()).execute(mCurrentPhoto);
            } else {
                //save photo to the ArrayList and notify user about uploading photo next time he connects to the internet
                sPhotoList.add(mCurrentPhoto);
                serializeList(sPhotoList);
                toastLong(getString(R.string.not_connected));
            }

        } else if(view.getId() == R.id.button_add) {
            Intent intent = new Intent(CameraActivity.this, AdditionalnfoActivity.class);
            startActivity(intent);
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
                    toastShort("Odhlásenie neprebehlo úspešne. Uistite sa, že ste pripojený na internet a skúste to znova.");
                    return;
                }

                try {
                    Thread.sleep(200);
                    log(TAG, "sleep(200)");
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
            mCamera.stopPreview();
//            log(TAG, "rotation of device = " + getWindowManager().getDefaultDisplay().getRotation());
            File compressedFile = FileUtils.getOutputMediaFile(MEDIA_TYPE_COMPRESSED, isWifiOrMobileOn);
            if(compressedFile == null) {
                Log.d(TAG, "Error creating media file, check storage permissions!");
                return;
            }
//            Log.d(TAG, compressedFile.getAbsolutePath());

            //Fill the file with image/video bytes
            try {
                //Transform to Base64 file
                String imageDataString = Base64.encodeToString(bytes, Base64.DEFAULT);
                byte[] imageByteArray = Base64.decode(imageDataString, Base64.DEFAULT);

                //Create Base64 image
                FileOutputStream fos = new FileOutputStream(compressedFile);
                fos.write(imageByteArray);

//                BitmapFactory.Options bounds = new BitmapFactory.Options();
//                bounds.inJustDecodeBounds = true;
//                BitmapFactory.decodeFile(compressedFile.getAbsolutePath(), bounds);
//
//                BitmapFactory.Options opts = new BitmapFactory.Options();
//                Bitmap bm = BitmapFactory.decodeFile(compressedFile.getAbsolutePath(), opts);
//                ExifInterface exif = new ExifInterface(compressedFile.getAbsolutePath());
//                String orientString = exif.getAttribute(ExifInterface.TAG_ORIENTATION);
//                Log.d(TAG, "orientString = " + orientString);
//                int orientation = orientString != null ? Integer.parseInt(orientString) :  ExifInterface.ORIENTATION_NORMAL;
//
//                int rotationAngle = 0;
//                if (orientation == ExifInterface.ORIENTATION_ROTATE_90) {
//                    rotationAngle = 90;
//                    log(TAG, "orientation == 90");
//                } else if (orientation == ExifInterface.ORIENTATION_ROTATE_180) {
//                    rotationAngle = 180;
//                    log(TAG, "orientation == 180");
//                } else if(orientation == ExifInterface.ORIENTATION_ROTATE_270) {
//                    rotationAngle = 270;
//                    log(TAG, "orientation == 270");
//                } else {
//                    log(TAG, "orientation == 0");
//                }
//
//                Matrix matrix = new Matrix();
//                matrix.setRotate(rotationAngle, (float) bm.getWidth() / 2, (float) bm.getHeight() / 2);
//                Bitmap rotatedBitmap = Bitmap.createBitmap(bm, 0, 0, bounds.outWidth, bounds.outHeight, matrix, true);
//                rotatedBitmap.compress(Bitmap.CompressFormat.JPEG, 100, fos);

                fos.close();

                mCurrentPhoto = new Photo();
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
